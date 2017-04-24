<?php

namespace DnsUpdatr\Updater;

interface AdapterInterface
{
    public function update($name, $domain, $ip);
}
