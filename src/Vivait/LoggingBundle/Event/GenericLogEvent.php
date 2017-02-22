<?php

namespace Vivait\LoggingBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Vivait\LoggingBundle\Model\LoggableEvent;

class GenericLogEvent extends Event implements LoggableEvent
{

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $level;

    /**
     * @var array
     */
    private $context;

    /**
     * @param string $message
     * @param string $level
     * @param array  $context
     */
    public function __construct($message, $level, $context = [])
    {
        $this->message = $message;
        $this->level   = $level;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogLine()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }
}
