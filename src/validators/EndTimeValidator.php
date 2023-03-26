<?php

declare(strict_types=1);

namespace Payme\Validators;

use Payme\Support\Interfaces\ValidatorInterface;

/**
 * End time Validator
 *
 * This class defines a static method that checks if a time string is bigger than another one.
 *
 * @package Payme\Validators
 */
class EndTimeValidator implements ValidatorInterface {
    /**
     * Checks if a given time is bigger than another one with a precision of minutes.
     *
     * @method bool validate(mixed $input) Returns true if end time is bigger than start time.
     * @param mixed $input An array that contains both times to be compared
     * @return bool Returns true if end date is bigger than start date, otherwise returns false.
     */
    public static function validate(mixed $input): bool
    {
        try {
            $time1      = \DateTime::createFromFormat("H:i", substr($input['start'], 0, 5));
            $startTime  = $time1->format("H:i");
            $time2      = \DateTime::createFromFormat("H:i", substr($input['end'], 0, 5));
            $endTime    = $time2->format("H:i");
            return $startTime < $endTime;
        } catch (\Exception|\Error $e) {
            echo 'Comparing times in validator: ',  $e->getMessage(), "\n";
        }
        return false;
    }
}