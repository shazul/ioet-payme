<?php

declare(strict_types=1);

namespace Payme\Services;

use Payme\Support\Database;

/**
 * Service class for calculation
 *
 * This service provides methods for calculating the amount to be paid to an employee
 *
 * @package Payme\Services
 */
class PayrollCalculatorService {
    /**
     * The amount to be paid to the employee
     *
     * @var int
     */
    private $total;

    /**
     * The Database connection
     *
     * @var Database
     */
    private $db;

    /**
     * Shows the current validity of the process
     *
     * @var bool
     */
    private $validProcess;

    /**
     * Constructor
     *
     * Creates a new PayrollCalculatorService instance with the supplied DB connection.
     *
     * @param Database $db The Database connection.
     * @return void
     */
    public function __construct(Database $db)
    {
        $this->total        = 0;
        $this->db           = $db;
        $this->validProcess = true;
    }

    /**
     * Calculates the amount to be paid.
     *
     * This method starts the calculation process and displays the result of it.
     *
     * @param array $reportArray
     * @return void
     * @throws Exception if the process of getting a time range from DB fails.
     *
     * @output string The amount to be paid to every employee and its name
     */
    public function calculate(array $reportArray): void
    {
        foreach ($reportArray as $workerName => $reportLine) {
            $this->total = 0;
            foreach ($reportLine as $reportTimeRange) {
                $result = $this->getMatchingTimeRange($reportTimeRange);
                if (!$result) {
                    $this->validProcess = false;
                    throw new \Exception("There was a problem in the calculation process");
                }
            }
            if ($this->validProcess) {
                echo "The amount to pay $workerName is: $this->total USD<br>";
            } else {
                echo "Well, something happened and we couldn't calculate the payroll.";
            }
        }
    }

    /**
     * Gets a time range from the DB
     *
     * Gets a time range that fits the starting time reported by the employee.
     *
     * @param array $fileTimeRange A time range taken from the employee report file.
     * @return bool Returns true if a time range was found in DB, false if not.
     */
    protected function getMatchingTimeRange(array $fileTimeRange): bool
    {
        try {
            $query = "SELECT * FROM hourly_rates WHERE :day = ANY(weekdays) AND start_time <= :start AND end_time > :start";
            $params = ['day' => $fileTimeRange['day'], 'start' => $fileTimeRange['start']];
            $rateTimeRange = $this->db->query($query, $params);
            if (empty($rateTimeRange)) {
                throw new \Exception("No day/time range matches in DB.");
            }

            $this->manageCalculations($fileTimeRange, $rateTimeRange);
            return true;
        } catch (\Exception $e) {
            echo 'Getting time range from DB: ',  $e->getMessage(), "\n";
            return false;
        }
    }

    /**
     * Manages the amount calculations for a given time range.
     *
     * This method determines and launches the calculations needed when an employee
     * reports a time range that covers more than one hourly rate.
     *
     * @param array $fileTimeRange Time range reported by the employee
     * @param array $dbTimeRange Time range from DB that matches starting time reported
     * @return void
     */
    protected function manageCalculations(array $fileTimeRange, array $dbTimeRange): void
    {
        if ($dbTimeRange['end_time'] >= $fileTimeRange['end']) {
            $this->total += $this->getAmount($fileTimeRange['start'], $fileTimeRange['end'], $dbTimeRange['rate']);
        } else {
            $this->total += $this->getAmount($fileTimeRange['start'], $dbTimeRange['end_time'], $dbTimeRange['rate']);

            // Call again getMatchingTimeRange to process another rate
            // because reported time goes beyond one time range in a day.
            // Reported start time is being reset to the beginning of next hourly rate range.
            $fileTimeRange['start'] = substr($dbTimeRange['end_time'], 0, 5);
            $this->getMatchingTimeRange($fileTimeRange);
        }
    }

    /**
     * Does the actual calculation for a given range and its hourly rate.
     *
     * This method calculates the amount to be paid for a given time range and hourly rate.
     *
     * @param string $start Beginnning of time range expressed in HH:mm
     * @param string $end End of time range expressed in HH:mm
     * @param mixed $rate Hourly rate expressed as a float
     *
     * @return float The amount to be paid for the time range provided
     */
    protected function getAmount(string $start, string $end, mixed $rate): float
    {
        try {
            $startTime = \DateTime::createFromFormat('H:i', substr($start, 0, 5));
            $startTime->format('H:i');
            $endTime = \DateTime::createFromFormat('H:i', substr($end, 0, 5));
            $endTime->format('H:i');

            $workedTime = $endTime->diff($startTime);
            $workedTimeFloat = $workedTime->h + round($workedTime->i / 60, 4);

            return round($workedTimeFloat * floatval($rate), 2);
        } catch (\Exception $e) {
            echo 'Getting amount for a range: ',  $e->getMessage(), "\n";
        }
    }

}