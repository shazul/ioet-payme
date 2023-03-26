<?php

declare(strict_types=1);

namespace Payme\Controllers;

use Payme\Services\{PayrollCalculatorService, ReportFormatterService};
use Payme\Support\Database;
use Payme\Support\Utils\FileHandler;

/**
 * Controller class with reusable methods.
 *
 * This controller provides common methods for the payroll generation process.
 *
 * @package Payme\Controllers
 */
class BaseController {
    /**
     * Processes a local file.
     *
     * This method manages the processing of a local file to get the amounts to be paid.
     *
     * @param string $file The filename of the file to be processed
     * @return void
     *
     * @output string Error message in case the process failed.
     */
    protected function processFile(string $file): void
    {
        try {
            $db                     = Database::getInstance();
            $fileHandler            = new FileHandler($file);
            $calculator             = new PayrollCalculatorService($db);

            $fileData = $fileHandler->read()->toArray();

            $reportFormatter = new ReportFormatterService($fileData);
            $reportArray = $reportFormatter->format();

            if (!empty($reportArray)) {
                $calculator->calculate($reportArray);
            } else {
                echo "<br>Some errors were detected in the report file. Please fix them and try again.";
            }
        } catch (\Exception $e) {
            echo 'Something happened in the main process: ',  $e->getMessage(), "\n";
        }
    }
}