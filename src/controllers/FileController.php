<?php

declare(strict_types=1);

namespace Payme\Controllers;

use Payme\Controllers\BaseController;

/**
 * Controller class for handling local files.
 *
 * This controller provides methods for managing the payroll generation from a local file.
 *
 * @package Payme\Controllers
 */
class FileController extends BaseController {
    /**
     * The filename of the file that contains the hours report.
     *
     * @var string
     */
    private $file;

    /**
     * Constructor
     *
     * Creates a new FileController instance with the specified file location.
     *
     * @param array $fileConfig The file location.
     * @return void
     */
    public function __construct(array $fileConfig)
    {
        $this->file = __DIR__.'/../'.$fileConfig['local_folder'].$fileConfig['local_filename'];
    }

    /**
     * Generates a payroll report
     *
     * This method generates the amount that must be paid according to the employee report
     *
     * @return void
     */
    public function generatePayroll(): void
    {
        $this->processFile($this->file);
    }
}