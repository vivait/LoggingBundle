<?php

namespace Vivait\LoggingBundle\Processor;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @param RequestStack $requestStack
     * @param string       $kernelEnvironment
     * @param string       $appName
     */
    public function __construct(RequestStack $requestStack, $kernelEnvironment, $appName, TokenStorage $tokenStorage)
    {
        $this->requestStack      = $requestStack;
        $this->kernelEnvironment = $kernelEnvironment;
        $this->appName           = $appName;
        $this->tokenStorage      = $tokenStorage;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function processRecord(array $record)
    {
        $record['extra']['UA'] = '';
        $record['extra']['IP'] = '';

        $request = $this->requestStack->getCurrentRequest();
        if ($request !== null) {
            $record['extra']['UA'] = $request->headers->get('user-agent');
            $record['extra']['IP'] = $request->getClientIp();
        }

        $record['extra']['Environment'] = $this->kernelEnvironment;
        $record['extra']['App']         = $this->appName;
        $record['extra']['User']        = '';

        $token = $this->tokenStorage->getToken();
        if ($token !== null) {
            $record['extra']['User'] = $token->getUsername();
        }

        return $record;
    }
}
