<?php

declare(strict_types=1);

namespace Payme\Services;

use Payme\Validators\EndTimeValidator;
use Payme\Validators\ReportFormatValidator;

/**
 * Service class for data formatting
 *
 * This service provides methods for adapting the data received before it can be used for calculations
 *
 * @package Payme\Services
 */
class ReportFormatterService {
    /**
     * The report from the received file in an array (one line per employee)
     *
     * @var array
     */
    private $data;

    /**
     * Constructor
     *
     * Creates a new ReportFormatterService instance with the supplied data from the file.
     *
     * @param array $data The data taken from the processed file
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data  = $data;
    }

    /**
     * Formats the data taken from the file
     *
     * This method formats and launches validation in order to prepare the data for calculations.
     *
     * @return array The data ready for calculation, if there is an error an empty array is returned
     */
    public function format(): array
    {
        $reportArray = [];
        try {
            foreach ($this->data as $reportLine) {
                // validate format in file
                if (!ReportFormatValidator::validate($reportLine)) {
                    throw new \Exception("Invalid line: " . $reportLine);
                }

                $equalPosition = strpos($reportLine, '=');
                $employeeName = substr($reportLine, 0, $equalPosition);

                $employeeHoursReport = explode(',', substr($reportLine, $equalPosition + 1));

                $dailyArray = [];
                foreach ($employeeHoursReport as $dailyReport) {
                    $dailyArray['day']      = substr($dailyReport, 0, 2);
                    $dailyArray['start']    = substr($dailyReport, 2, 5);
                    $dailyArray['end']      = substr($dailyReport, 8, 5);

                    // validate end time is always later than start
                    if (!EndTimeValidator::validate($dailyArray)) {
                        throw new \Exception("Invalid times in line: " . $reportLine);
                    }

                    $reportArray[$employeeName][] = $dailyArray;
                }
            }
            return $reportArray;
        } catch (\Exception $e) {
            echo 'Formatting data from file: ',  $e->getMessage(), "\n";
            return [];
        }
    }

}