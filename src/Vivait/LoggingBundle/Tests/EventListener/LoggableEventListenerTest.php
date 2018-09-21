<?php

namespace Vivait\LoggingBundle\Tests;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;
use Vivait\LoggingBundle\Event\GenericLogEvent;
use Vivait\LoggingBundle\EventListener\LoggableEventListener;

class LoggableEventListenerTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function theListenerWillLogLoggableEvents()
    {
        $mockedLogger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $mockedLogger->expects($this->once())->method('log')->with(
            $this->equalTo('critical'),
            $this->equalTo('My logged string')
        );

        $myEvent = new GenericLogEvent('My logged string', 'critical');

        $listener = new LoggableEventListener($mockedLogger);
        $listener->onEvent($myEvent);
    }

    /**
     * @test
     */
    public function theListenerWillNotLogEventsThatDoNotImplementLoggableEvent()
    {
        $mockedLogger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $mockedLogger->expects($this->never())->method('log');

        $myEvent = new Event();

        $listener = new LoggableEventListener($mockedLogger);
        $listener->onEvent($myEvent);
    }
}
