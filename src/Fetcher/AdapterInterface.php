<?php
namespace DnsUpdatr\Fetcher;

interface AdapterInterface
{
    /**
     * Gets the IP address for this machine.
     *
     * @return string The IPv4 address for this machine.
     */
    public function getIpAddress();
}
