<?php

namespace DnsUpdatr\Tests;

use DnsUpdatr\Fetcher;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class FetcherTests extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerGetIpAddress
     */
    public function testGetIpAddress($expected, $body, $exception = false)
    {
        $sut = $this->getMockBuilder('\DnsUpdatr\Fetcher')
            ->disableOriginalConstructor()
            ->setMethods(['getClient'])
            ->getMock();

        $client = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();

        $response = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMock();

        if (! $exception) {
            $response->expects($this->once())
                ->method('getBody')
                ->will($this->returnValue($body));
        }

        $client->expects($this->once())
            ->method('request')
            // ->with
            ->will($exception
                ? $this->throwException(new RequestException('testing', new Request('GET', 'http://test.com')))
                : $this->returnValue($response)
            );

        $sut->expects($this->once())
            ->method('getClient')
            ->will($this->returnValue($client));

        $result = $sut->getIpAddress();
        $this->assertEquals($expected, $result);
    }

    public function providerGetIpAddress()
    {
        return [
            'simple' => [
                'expected' => 'expected',
                'body'     => 'expected',
            ],

            'has exception ' => [
                'expected'  => null,
                'body'      => 'expected',
                'exception' => true,
            ],
        ];
    }
}
