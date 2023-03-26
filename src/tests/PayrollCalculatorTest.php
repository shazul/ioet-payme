<?php

use Payme\Services\PayrollCalculatorService;

require_once __DIR__.'/../autoloader.php';

class PayrollCalculatorTest extends PHPUnit\Framework\TestCase
{
    public function testGetRightTimeRange()
    {
        $input = [
            'day'   => 'MO',
            'start' => '10:00',
            'end'   => '13:00'
        ];

        $pdoMock = $this->getMockBuilder('Payme\Support\Database')
            ->disableOriginalConstructor()
            ->getMock();

        $pdoMock->expects($this->any())
            ->method('query')
            ->with($this->stringContains('FROM'))
            ->willReturn(['start_time' => '09:00', 'end_time' => '18:00', 'rate' => 15]);

        $calculator = new PayrollCalculatorService($pdoMock);
        $method = new ReflectionMethod(PayrollCalculatorService::class, 'getMatchingTimeRange');
        $method->setAccessible(true);
        $result = $method->invoke($calculator, $input);

        $this->assertEquals($result, true);
    }

    public function testGetNoTimeRange()
    {
        $input = [
            'day'   => 'MOS',
            'start' => '10:00',
            'end'   => '13:00'
        ];

        $pdoMock = $this->getMockBuilder('Payme\Support\Database')
            ->disableOriginalConstructor()
            ->getMock();

        $pdoMock->expects($this->any())
            ->method('query')
            ->with($this->stringContains('FROM'))
            ->willReturn([]);

        $calculator = new PayrollCalculatorService($pdoMock);
        $method = new ReflectionMethod(PayrollCalculatorService::class, 'getMatchingTimeRange');
        $method->setAccessible(true);
        $result = $method->invoke($calculator, $input);

        $this->assertEquals($result, false);
    }

    // Maybe not the best practice
    public function testGetRightAmount()
    {
        $start = '10:00';
        $end = '13:30';
        $rate = '15';

        $pdoMock = $this->getMockBuilder('Payme\Support\Database')
        ->disableOriginalConstructor()
        ->getMock();

        $calculator = new PayrollCalculatorService($pdoMock);
        $method = new ReflectionMethod(PayrollCalculatorService::class, 'getAmount');
        $method->setAccessible(true);
        $result = $method->invoke($calculator, $start, $end, $rate);

        $this->assertEquals(52.5, $result);
    }

    public function testWholeCalculationIsRight()
    {
        $input = [
            'EMP' => [
                [
                    'day'   => 'MO',
                    'start' => '10:00',
                    'end'   => '13:00'
                ],
                [
                    'day'   => 'TU',
                    'start' => '15:00',
                    'end'   => '17:00'
                ],
            ]
        ];
        $pdoMock = $this->getMockBuilder('Payme\Support\Database')
        ->disableOriginalConstructor()
        ->getMock();

        $pdoMock->expects($this->any())
            ->method('query')
            ->with($this->stringContains('FROM'))
            ->willReturn(['start_time' => '09:00', 'end_time' => '18:00', 'rate' => 15]);

        $calculator = new PayrollCalculatorService($pdoMock);

        ob_start();
        $calculator->calculate($input);
        $output = ob_get_clean();

        $this->expectOutputString("The amount to pay EMP is: 75 USD<br>");
        echo $output;
    }

    public function testErrorInCalculation()
    {
        $input = [
            'EMP' => [
                [
                    'day'   => 'MOS',
                    'start' => '10:00',
                    'end'   => '13:00'
                ],
                [
                    'day'   => 'TU',
                    'start' => '15:00',
                    'end'   => '17:00'
                ],
            ]
        ];
        $pdoMock = $this->getMockBuilder('Payme\Support\Database')
        ->disableOriginalConstructor()
        ->getMock();

        $pdoMock->expects($this->any())
            ->method('query')
            ->with($this->stringContains('FROM'))
            ->willReturn([]);

        $this->expectException(\Exception::class);
        $calculator = new PayrollCalculatorService($pdoMock);
        $calculator->calculate($input);
    }
}