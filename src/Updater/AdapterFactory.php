<?php

namespace DnsUpdatr\Updater;

class AdapterFactory
{
    const ERR_INVALID_TYPE = 'Requested adapter [%s] does not exist';

    public function factory($type, $options = [])
    {
        $map = $this->getTypeMap();

        if (! array_key_exists($type, $map)) {
            throw new Exception(sprintf(self::ERR_INVALID_TYPE, $type));
        }

        return new $map[$type]($options);

    }

    protected function getTypeMap()
    {
        return [
            'digital-ocean' => '\DnsUpdatr\Updater\DigitalOceanAdapter',
        ];
    }
}
