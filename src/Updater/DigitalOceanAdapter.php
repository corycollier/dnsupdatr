<?php

namespace DnsUpdatr\Updater;

use GuzzleHttp\Client;

class DigitalOceanAdapter implements AdapterInterface
{
    const URI_ALL_RECORDS    = 'https://api.digitalocean.com/v2/domains/%s/records';
    const URI_SINGLE_RECORD  = 'https://api.digitalocean.com/v2/domains/%s/records/%d';

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options The options for the adapter.
     */
    public function __construct($options = [])
    {
        $this->options = $options;
    }

    /**
     * Update hook. Calls create if the record doesn't already exist.
     *
     * @param  string $name   The name to update.
     * @param  string $domain The domain name to update (example.com).
     * @param  string $ip     The IPv4 value to update to.
     *
     * @return DigitalOceanAdapter Returns $this, for object-chaining.
     */
    public function update($name, $domain, $ip)
    {
        $client = $this->getClient();
        $record = $this->getRecord($name, $domain);

        if (! $record) {
            return $this->create($name, $domain, $ip);
        }
        // if the record already exists, don't update
        if ($record['data'] === $ip) {
            error_log(sprintf(AdapterInterface::ERR_RECORD_EXISTS, $name, $domain, $ip));
            return $this;
        }

        $response = $client->request('PUT', sprintf(self::URI_SINGLE_RECORD, $domain, $record['id']), [
            'headers' => $this->getHeaders(),
            'json' => [
                'data' => $ip,
                'ttl' => 300,
            ],
        ]);

        return $this;
    }

    /**
     * Create hook.
     *
     * @param  string $name   The name to create.
     * @param  string $domain The domain name to update (example.com).
     * @param  string $ip     The IPv4 value to create a record for.
     *
     * @return DigitalOceanAdapter Returns $this, for object-chaining.
     */
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

        return $this;

    }

    /**
     * Getter for the options property.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Gets a record entry for a given name, in a domain.
     *
     * @param  string $name The name of the record
     * @param  string $domain The domain who to check records for.
     *
     * @return array An array of key/value pairs representing the record.
     */
    public function getRecord($name, $domain)
    {
        $records = $this->getRecords($domain);
        foreach ($records['domain_records'] as $record) {
            if ($record['data'] === $domain && $record['name'] === $name) {
                return $record;
            }
        }
    }

    /**
     * Gets all of the record for a domain.
     *
     * @param  string $domain The name of the domain to check records for.
     *
     * @return array A list of record values.
     */
    public function getRecords($domain)
    {
        $client = $this->getClient();
        $response = $client->request('GET', sprintf(self::URI_ALL_RECORDS, $domain), [
            'headers' => $this->getHeaders(),
        ]);
        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Utility method to get an array of header values to use when creating requests.
     *
     * @return array  An array of authorization and content-type headers.
     */
    public function getHeaders()
    {
        $options = $this->getOptions();
        return [
            'Authorization' => 'Bearer ' . $options['token'],
            'Content-Type'  => 'application/json'
        ];
    }

    /**
     * Gets a new GuzzleHttp\Client.
     *
     * @return Client
     */
    protected function getClient()
    {
        return new Client();
    }
}
