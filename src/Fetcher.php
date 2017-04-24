<?php

namespace DnsUpdatr;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class Fetcher
{
    const API_URI = 'ipv4bot.whatismyipaddress.com';

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

    protected function getClient()
    {
        return new Client();
    }
}
