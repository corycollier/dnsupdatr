<?php

namespace DnsUpdatr;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Fetcher
{
    const API_URI = 'http://ipv4bot.whatismyipaddress.com';

    /**
     * Gets the IP address for this machine.
     *
     * @return string The IPv4 address for this machine.
     */
    public function getIpAddress()
    {
        $client = $this->getClient();
        try {
            $response = $client->request('GET', self::API_URI);
            return (string)$response->getBody();
        } catch (TransferException $exception) {
            error_log($exception->getMessage());
        }
    }

    /**
     * Gets a new GuzzleHttp\Client.
     *
     * @return GuzzleHttp\Client Used for making requests.
     */
    protected function getClient()
    {
        return new Client();
    }
}
