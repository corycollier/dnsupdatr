<?php

namespace DnsUpdatr\Updater;

use GuzzleHttp\Client;

class DigitalOceanAdapter implements AdapterInterface
{
    const ERR_INVALID_NAME = 'The provided name [%s] does not have a record with the domain [%s]';
    const URI_ALL_RECORDS = 'https://api.digitalocean.com/v2/domains/%s/records';
    const URI_SINGLE_RECORD = 'https://api.digitalocean.com/v2/domains/%s/records/%d';

    protected $options;

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    public function update($name, $domain, $ip)
    {
        $client = $this->getClient();
        $recordId = $this->getRecordId($name, $domain);
        if (! $recordId) {
            return $this->create($name, $domain, $ip);
        }
        $response = $client->request('PUT', sprintf(self::URI_SINGLE_RECORD, $domain, $recordId), [
            'headers' => $this->getHeaders(),
            'json' => [
                'data' => $ip,
                'ttl' => 300,
            ],
        ]);
        print_r($response);
        return $this;
    }

    public function create($name, $domain, $ip)
    {
        $client = $this->getClient();
        $response = $client->request('POST', sprintf(self::URI_ALL_RECORDS, $domain), [
            'headers' => $this->getHeaders(),
            'json' => [
                'type' => 'A',
                'data' => $ip,
                'ttl' => 300,
                'name' => $name,
            ],
        ]);
        print_r($response);
        return $this;

    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getRecordId($name, $domain)
    {
        $records = $this->getRecords($domain);
        foreach ($records['domain_records'] as $record) {
            if ($record['data'] === $domain && $record['name'] === $name) {
                return $record['id'];
            }
        }
    }

    public function getRecords($domain)
    {
        $client = $this->getClient();
        $response = $client->request('GET', sprintf(self::URI_ALL_RECORDS, $domain), [
            'headers' => $this->getHeaders(),
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    public function getHeaders()
    {
        $options = $this->getOptions();
        return [
            'Authorization' => 'Bearer ' . $options['token'],
            'Content-Type'  => 'application/json'
        ];
    }

    protected function getClient()
    {
        return new Client();
    }
}
