<?php

use Payme\Services\ReportFormatterService;

require_once __DIR__.'/../autoloader.php';

class ReportFormatterTest extends PHPUnit\Framework\TestCase
{
    public function testReturnArrayHasRightLength()
    {
        $input = [
            'ABC=MO09:30-12:30,SA12:15-14:00',
            'DEF=WE14:00-19:00',
            'XYZ=FR07:00-09:00,SA08:00-12:00,SU17:00-18:00'
        ];
        $formatter = new ReportFormatterService($input);
        $result = $formatter->format();
        $this->assertEquals(count($result), 3);
    }

    public function testReturnNestedArrayHasRightLength()
    {
        $input = [
            'ABC=MO09:30-12:30,SA12:15-14:00',
            'DEF=WE14:00-19:00',
            'XYZ=FR07:00-09:00,SA08:00-12:00,SU17:00-18:00'
        ];
        $formatter = new ReportFormatterService($input);
        $result = $formatter->format();
        $this->assertEquals(count($result['ABC']), 2);
        $this->assertEquals(count($result['DEF']), 1);
        $this->assertEquals(count($result['XYZ']), 3);
    }

    public function testReturnsEmptyOnFormatError()
    {
        $input = [
            'ABC=MOS09:30-12:30,SA12:15-14:00',
            'DEF=WE14:00-19:00',
            'XYZ=FR07:00-09:00,SA08:00-12:00,SU17:00-18:00'
        ];
        $formatter = new ReportFormatterService($input);
        $result = $formatter->format();
        $this->assertEquals(count($result), 0);
    }
}