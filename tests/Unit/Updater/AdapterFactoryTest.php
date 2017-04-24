<?php

namespace DnsUpdatr\Tests;

use DnsUpdatr\Updater\AdapterFactory;
use DnsUpdatr\Updater\Exception;

class AdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerFactory
     */
    public function testFactory($expected, $type, $types, $exception = false)
    {
        $sut = $this->getMockBuilder('\DnsUpdatr\Updater\AdapterFactory')
            ->disableOriginalConstructor()
            ->setMethods(['getTypeMap'])
            ->getMock();

        $sut->expects($this->once())
            ->method('getTypeMap')
            ->will($this->returnValue($types));

        if ($exception) {
            $this->expectException('\DnsUpdatr\Updater\Exception');
        }

        $result = $sut->factory($type, []);
        $this->assertInstanceOf($expected, $result);
    }

    public function providerFactory()
    {
        return [
            'simple' => [
                'expected' => '\DnsUpdatr\Updater\AdapterFactory',
                'type' => 'the-type',
                'types' => [
                    'the-type' => '\DnsUpdatr\Updater\AdapterFactory',
                ],
                'exception' => false,
            ],

            'has exception' => [
                'expected' => null,
                'type' => 'bad-type',
                'types' => [
                    'the-type' => '\DnsUpdatr\Updater\AdapterFactory',
                ],
                'exception' => true,
            ],
        ];
    }
}
