<?php

namespace Vivait\LoggingBundle\Tests\Processor;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Vivait\LoggingBundle\Processor\LogProcessor;

class LogProcessorTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     * @throws \ReflectionException
     */
    public function theExtraDataWillGetAddedToTheRecord(): void
    {
        /** @var MockObject|TokenInterface $mockedTokenStorage */
        $mockedToken = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $mockedToken->expects($this->once())->method('getUsername')->will($this->returnValue('admin'));

        /** @var MockObject|TokenStorage $mockedTokenStorage */
        $mockedTokenStorage = $this->getMockBuilder(TokenStorage::class)->disableOriginalConstructor()->getMock();
        $mockedTokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($mockedToken));

        /** @var MockObject|HeaderBag $mockedTokenStorage */
        $mockedHeaderBag = $this->getMockBuilder(HeaderBag::class)->disableOriginalConstructor()->getMock();
        $mockedHeaderBag
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue('MyUser Agent String 1.2.3'))
        ;

        /** @var MockObject|Request $mockedTokenStorage */
        $mockedRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $mockedRequest->expects($this->once())->method('getClientIp')->will($this->returnValue('127.0.0.1'));

        $property = new \ReflectionProperty(Request::class, 'headers');
        $property->setAccessible(true);
        $property->setValue($mockedRequest, $mockedHeaderBag);

        /** @var RequestStack|MockObject $requestStack */
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->will($this->returnValue($mockedRequest));

        $processor = new LogProcessor($requestStack, 'env', 'appname', $mockedTokenStorage);

        $actual = $processor->processRecord([]);
        $expected = [
            'extra' => [
                'UA'          => 'MyUser Agent String 1.2.3',
                'IP'          => '127.0.0.1',
                'Environment' => 'env',
                'App'         => 'appname',
                'User'        => 'admin'
            ]
        ];

        $this->assertEquals($expected, $actual, 'Incorrect output received.');
    }

    /**
     * @test
     */
    public function ifThereIsNoRequestThenThereWillBeBlankInstead(): void
    {
        /** @var MockObject|TokenInterface $mockedTokenStorage */
        $mockedToken = $this->getMockBuilder(TokenInterface::class)->disableOriginalConstructor()->getMock();
        $mockedToken->expects($this->once())->method('getUsername')->will($this->returnValue('admin'));

        /** @var MockObject|TokenStorage $mockedTokenStorage */
        $mockedTokenStorage = $this->getMockBuilder(TokenStorage::class)->disableOriginalConstructor()->getMock();
        $mockedTokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($mockedToken));

        /** @var RequestStack|MockObject $requestStack */
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->will($this->returnValue(null));

        $processor = new LogProcessor($requestStack, 'env', 'appname', $mockedTokenStorage);

        $actual = $processor->processRecord([]);
        $expected = [
            'extra' => [
                'UA'          => '',
                'IP'          => '',
                'Environment' => 'env',
                'App'         => 'appname',
                'User'        => 'admin'
            ]
        ];

        $this->assertEquals($expected, $actual, 'Incorrect output received.');
    }

    /**
     * @test
     */
    public function ifThereIsNoSecurityTokenThenTheUserWillBeBlank(): void
    {
        /** @var MockObject|TokenStorage $mockedTokenStorage */
        $mockedTokenStorage = $this->getMockBuilder(TokenStorage::class)->disableOriginalConstructor()->getMock();
        $mockedTokenStorage->expects($this->once())->method('getToken')->will($this->returnValue(null));

        /** @var RequestStack|MockObject $requestStack */
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $requestStack->expects($this->once())->method('getCurrentRequest')->will($this->returnValue(null));

        $processor = new LogProcessor($requestStack, 'env', 'appname', $mockedTokenStorage);

        $actual = $processor->processRecord([]);
        $expected = [
            'extra' => [
                'UA'          => '',
                'IP'          => '',
                'Environment' => 'env',
                'App'         => 'appname',
                'User'        => ''
            ]
        ];

        $this->assertEquals($expected, $actual, 'Incorrect output received.');
    }
}
