<?php

declare(strict_types=1);

namespace Payme\Support\Utils;

/**
 * File Handler
 *
 * This class provides methods to read a file and convert it to an array
 *
 * @package Payme\Support\Utils
 */
class FileHandler {
    /**
     * The filename of the file to be read
     *
     * @var string
     */
    private $filename;

    /**
     * The file content to be converted/processed
     *
     * @var string
     */
    private $content;

    /**
     * Constructor
     *
     * Creates a new FileHandler instance with the supplied filename.
     *
     * @param string The filename of the file to be used
     * @return void
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * Reads a file
     *
     * This method reads a stored file and assigns it to a property.
     *
     * @return self Returns the instance of this class
     */
    public function read(): self
    {
        $this->content = file_get_contents($this->filename);
        return $this;
    }

    /**
     * Converts an enter-separated string to an array
     *
     * This method splits a string stored in a property into an array
     *
     * @return array Returns an array that contains the lines from the file
     */
    public function toArray(): array
    {
        return explode("\n", $this->content);
    }
}
