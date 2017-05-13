<?php

namespace DnsUpdatr\Updater;

interface AdapterInterface
{
    const ERR_RECORD_EXISTS  = 'The record [%s] already exists for domain [%s] with address [%s]';
    const ERR_INVALID_NAME   = 'The provided name [%s] does not have a record with the domain [%s]';

    /**
     * Update hook. Calls create if the record doesn't already exist.
     *
     * @param  string $name   The name to update.
     * @param  string $domain The domain name to update (example.com).
     * @param  string $ip     The IPv4 value to update to.
     *
     * @return AdapterInterface Returns $this, for object-chaining.
     */
    public function update($name, $domain, $ip);

    /**
     * Create hook.
     *
     * @param  string $name   The name to create.
     * @param  string $domain The domain name to update (example.com).
     * @param  string $ip     The IPv4 value to create a record for.
     *
     * @return AdapterInterface Returns $this, for object-chaining.
     */
    public function create($name, $domain, $ip);
}
