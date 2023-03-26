<?php

declare(strict_types=1);

namespace Payme\Validators;

use Payme\Support\Interfaces\ValidatorInterface;

/**
 * Report Format Validator
 *
 * This class defines a static method that checks if the given input matches the regular expression.
 *
 * @package Payme\Validators
 */
class ReportFormatValidator implements ValidatorInterface {
    /**
     * This constant contains the regular expression to be compared
     *
     * @const string
     */
    const REGEX = '/^[A-Z]+=(?:(?:MO|TU|WE|TH|FR|SA|SU)(?:[0-1]\d|2[0-3]):[0-5]\d-(?:[0-1]\d|2[0-3]):[0-5]\d,?)+$/';

    /**
     * Checks if the given regular expression matches the input data
     *
     * @method bool validate(mixed $input) Returns true if given data matches the regular expression.
     * @param mixed #input The data to be validated
     * @return bool Returns true if data and regex match, otherwise returns false
     */
    public static function validate(mixed $input): bool
    {
        return preg_match(self::REGEX, $input) === 1;
    }
}