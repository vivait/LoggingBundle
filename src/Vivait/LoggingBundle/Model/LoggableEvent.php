<?php

namespace Vivait\LoggingBundle\Model;

interface LoggableEvent
{

    /**
     * Get the text that should be written to the log.
     *
     * @return string
     */
    public function getLogLine();

    /**
     * The logging level to use, i.e. 'warning', 'error'
     *
     * @return string
     */
    public function getLevel();

    /**
     * Context for the log.
     * Return an empty array for none.
     *
     * @return array
     */
    public function getContext();
}
