<?php

namespace DnsUpdatr;

use DnsUpdatr\Updater\AdapterInterface;
use DnsUpdatr\Updater\AdapterFactory;

class Updater
{
    protected $adapter;
    protected $factory;
    protected $options;

    public function __construct($options = [])
    {
        $defaults = $this->getDefaultConstructorOptions();
        $options = array_merge($defaults, $options);
        $this->options = $options;
        $this->factory = new AdapterFactory();
    }

    public function init()
    {
        $options = $this->getOptions();
        $factory = $this->getFactory();
        $adapter = $factory->factory($options['adapter'], $options['options']);

        return $this->setAdapter($adapter);
    }

    protected function getDefaultConstructorOptions()
    {
        return [
            'adapter' => 'digital-ocean',
            'options' => [],
        ];
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getFactory()
    {
        return $this->factory;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function update($name, $domain, $ip)
    {
        $adapter = $this->getAdapter();
        $adapter->update($name, $domain, $ip);

    }
}
