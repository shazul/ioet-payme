<?php

declare(strict_types=1);

namespace Payme\Support\Utils;

/**
 * File Uploader
 *
 * This class provides methods to upload a file and store it in the server
 *
 * @package Payme\Support\Utils
 */
class FileUploader {
    /**
     * The array that contains the file properties
     *
     * @var array
     */
    private $file;

    /**
     * The array that contains the allowed extensions for the uploaded file
     *
     * @var array
     */
    private $allowed_exts   = ['txt'];

    /**
     * The string that will store the uploaded files
     *
     * @var string
     */
    private $upload_dir     = __DIR__.'/uploads/';

    /**
     * Constructor
     *
     * Creates a new FileUploader instance with the supplied file.
     *
     * @param array the properties of the file to be processed
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Stores an uploaded file
     *
     * This method takes an uploaded file with an allowed extension and stores it.
     *
     * @return string The path to the uploaded file
     * @throws Exception if there is an error on upload
     * @throws Exception if the file extension is not allowed
     */
    public function upload(): string
    {
        $file_name  = $this->file['name'];
        $file_ext   = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array(strtolower($file_ext), $this->allowed_exts)) {
            $file_name = uniqid('file_') . '.' . $file_ext;

            if (move_uploaded_file($this->file['tmp_name'], $this->upload_dir . $file_name)) {
                return $this->upload_dir . $file_name;
            } else {
                throw new \Exception('Error uploading file');
            }
        } else {
            throw new \Exception('Invalid file type');
        }
    }
}
