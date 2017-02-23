<?php

namespace Vivait\LoggingBundle\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

class LogProcessor
{

    /**
     * @var RequestStack
     */
    private $requestStack;

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
        $this->requestStack      = $requestStack;
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

        $request = $this->requestStack->getCurrentRequest();
        if ($request !== null) {
            $record['extra']['UA'] = $request->headers->get('user-agent');
            $record['extra']['IP'] = $request->getClientIp();
        }

        $record['extra']['Environment'] = $this->kernelEnvironment;
        $record['extra']['App']         = $this->appName;

        return $record;
    }
}
