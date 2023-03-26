<?php

use Payme\Validators\EndTimeValidator;

require_once __DIR__.'/../autoloader.php';

class EndTimeValidatorTest extends PHPUnit\Framework\TestCase
{
    public function testEndTimeIsGreater()
    {
        $input = [
            'start'     => '10:00',
            'end'       => '12:00'
        ];
        $result = EndTimeValidator::validate($input);

        $this->assertEquals($result, true);
    }

    public function testEndTimeIsLess()
    {
        $input = [
            'start'     => '13:00',
            'end'       => '12:00'
        ];
        $result = EndTimeValidator::validate($input);

        $this->assertEquals($result, false);
    }
}