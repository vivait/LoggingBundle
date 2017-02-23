<?php

namespace Vivait\LoggingBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;
use Vivait\LoggingBundle\Model\LoggableEvent;

class LoggableEventListener
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Event|LoggableEvent $event
     */
    public function onEvent(Event $event)
    {
        if ( ! $event instanceof LoggableEvent) {
            return;
        }

        $this->logger->log($event->getLevel(), $event->getLogLine(), $event->getContext());
    }
}
