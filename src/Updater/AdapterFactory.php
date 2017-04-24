<?php

namespace DnsUpdatr\Updater;

class AdapterFactory
{
    const ERR_INVALID_TYPE = 'Requested adapter [%s] does not exist';

    /**
     * Factory for creating instances of the AdapterInterface.
     *
     * @param  string $type The machine-key of the adapter.
     * @param  array $options An arry of options to pass to the adapter's constructor.
     *
     * @return AdapterInterface The adapter to use.
     */
    public function factory($type, $options = [])
    {
        $map = $this->getTypeMap();

        if (! array_key_exists($type, $map)) {
            throw new Exception(sprintf(self::ERR_INVALID_TYPE, $type));
        }

        return new $map[$type]($options);

    }

    /**
     * The recognized key/class pairs for adapters.
     *
     * @return array
     */
    protected function getTypeMap()
    {
        return [
            'digital-ocean' => '\DnsUpdatr\Updater\DigitalOceanAdapter',
        ];
    }
}
