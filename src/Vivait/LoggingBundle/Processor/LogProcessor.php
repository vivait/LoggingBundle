<?php

namespace Vivait\LoggingBundle\Processor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LogProcessor
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $kernelEnvironment;

    /**
     * @var string
     */
    private $appName;

    /**
     * @param RequestStack $requestStack
     * @param string       $kernelEnvironment
     * @param string       $appName
     */
    public function __construct(RequestStack $requestStack, $kernelEnvironment, $appName)
    {
        $this->request           = $requestStack->getCurrentRequest();
        $this->kernelEnvironment = $kernelEnvironment;
        $this->appName           = $appName;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function processRecord(array $record)
    {
        $record['extra']['UA'] = '?????';
        $record['extra']['IP'] = '?????';

        if ($this->request !== null) {
            $record['extra']['UA'] = $this->request->headers->get('user-agent');
            $record['extra']['IP'] = $this->request->getClientIp();
        }

        $record['extra']['Environment'] = $this->kernelEnvironment;
        $record['extra']['App']         = $this->appName;

        return $record;
    }
}
