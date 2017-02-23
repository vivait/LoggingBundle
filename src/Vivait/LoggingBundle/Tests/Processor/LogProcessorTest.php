<?php

namespace Vivait\LoggingBundle\Tests\Processor;

use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vivait\LoggingBundle\Processor\LogProcessor;

class LogProcessorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function theExtraDataWillGetAddedToTheRecord()
    {
        $mockedHeaderBag = $this->getMockBuilder(HeaderBag::class)->disableOriginalConstructor()->getMock();
        $mockedHeaderBag
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue('MyUser Agent String 1.2.3'))
        ;

        $mockedRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockedRequest->expects($this->once())->method('getClientIp')->will($this->returnValue('127.0.0.1'));
        
        $property = new \ReflectionProperty(Request::class, 'headers');
        $property->setAccessible(true);
        $property->setValue($mockedRequest, $mockedHeaderBag);

        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->will($this->returnValue($mockedRequest));

        $processor = new LogProcessor($requestStack, 'env', 'appname');

        $actual = $processor->processRecord([]);
        $expected = [
            'extra' => [
                'UA'          => 'MyUser Agent String 1.2.3',
                'IP'          => '127.0.0.1',
                'Environment' => 'env',
                'App'         => 'appname'
            ]
        ];

        $this->assertEquals($expected, $actual, 'Incorrect output received.');
    }

    /**
     * @test
     */
    public function ifThereIsNoRequestThenThereWillBeQuestionMarksInstead()
    {
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->will($this->returnValue(null));

        $processor = new LogProcessor($requestStack, 'env', 'appname');

        $actual = $processor->processRecord([]);
        $expected = [
            'extra' => [
                'UA'          => '?????',
                'IP'          => '?????',
                'Environment' => 'env',
                'App'         => 'appname'
            ]
        ];

        $this->assertEquals($expected, $actual, 'Incorrect output received.');
    }
}
