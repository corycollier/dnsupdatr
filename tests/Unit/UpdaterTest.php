<?php

namespace DnsUpdatr\Tests;

use DnsUpdatr\Updater;

class UpdaterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $sut = new Updater([]);
    }

    public function testInit()
    {
        $expected = 'expected';
        $options = ['adapter' => null, 'options' => null];

        $sut = $this->getMockBuilder('\DnsUpdatr\Updater')
            ->disableOriginalConstructor()
            ->setMethods(['getOptions', 'getFactory', 'setAdapter'])
            ->getMock();

        $factory = $this->getMockBuilder("\DnsUpdatr\Updater\AdapterFactory")
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();

        $adapter = $this->getMockBuilder('\DnsUpdatr\Updater\AdapterInterface')
            ->disableOriginalConstructor()
            ->setMethods(['update', 'create'])
            ->getMock();

        $factory->expects($this->once())
            ->method('factory')
            ->with($this->equalTo($options['adapter']), $this->equalTo($options['options']))
            ->will($this->returnValue($adapter));

        $sut->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));

        $sut->expects($this->once())
            ->method('getFactory')
            ->will($this->returnValue($factory));

        $sut->expects($this->once())
            ->method('setAdapter')
            ->with($this->equalTo($adapter))
            ->will($this->returnValue($expected));

        $sut->init();
    }

    /**
     * @dataProvider providerUpdate
     */
    public function testUpdate($name, $domain, $ip)
    {
        $sut = $this->getMockBuilder('\DnsUpdatr\Updater')
            ->disableOriginalConstructor()
            ->setMethods(['getAdapter'])
            ->getMock();

        $adapter = $this->getMockBuilder('\DnsUpdatr\Updater\AdapterInterface')
            ->disableOriginalConstructor()
            ->setMethods(['update', 'create'])
            ->getMock();

        $adapter->expects($this->once())
            ->method('update')
            ->with($this->equalTo($name), $this->equalTo($domain), $this->equalTo($ip));

        $sut->expects($this->once())
            ->method('getAdapter')
            ->will($this->returnValue($adapter));

        $sut->update($name, $domain, $ip);


    }

    public function providerUpdate()
    {
        return [
            'simple' => [
                'name' => 'name',
                'domain' => 'domain',
                'ip' => 'ip',
            ]
        ];
    }
}
