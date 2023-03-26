<?php

namespace Payme\Support\Interfaces;

/**
 * An interface for classes that serve to validate data.
 *
 * This interface defines a method that should be implemented by objects
 * that want to act as data validators.
 *
 * @interface ValidatorInterface
 */
interface ValidatorInterface {
    /**
     * Do the validation of some data according to a given rule.
     *
     * @method bool validate(mixed $input)
     * @param mixed $input The data to be validated
     * @return bool Returns true if data is valid, otherwise it returns false
     */
    public static function validate(mixed $input): bool;
}