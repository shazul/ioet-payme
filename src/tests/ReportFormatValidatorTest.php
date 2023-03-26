<?php

use Payme\Validators\ReportFormatValidator;

require_once __DIR__.'/../autoloader.php';

class ReportFormatValidatorTest extends PHPUnit\Framework\TestCase
{
    public function testFormatIsValid()
    {
        $input = 'USER=MO08:00-10:00,WE09:00-11:00';
        $result = ReportFormatValidator::validate($input);
        $this->assertEquals($result, true);
    }

    public function testEqualIsMissing()
    {
        $input = 'USERMO08:00-10:00,WE09:00-11:00';
        $result = ReportFormatValidator::validate($input);
        $this->assertEquals($result, false);
    }

    public function testDayIsNotValid()
    {
        $input = 'USER=NO08:00-10:00,WE09:00-11:00';
        $result = ReportFormatValidator::validate($input);
        $this->assertEquals($result, false);
    }

    public function testHourIsNotValid()
    {
        $input = 'USER=NO08:00-30:00,WE09:00-11:00';
        $result = ReportFormatValidator::validate($input);
        $this->assertEquals($result, false);
    }
}