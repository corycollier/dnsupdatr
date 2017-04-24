<?php

namespace DnsUpdatr;

use DnsUpdatr\Updater\AdapterInterface;
use DnsUpdatr\Updater\AdapterFactory;

class Updater
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var AdapterFactory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options Options to use for the factory and adapters.
     */
    public function __construct($options = [])
    {
        $defaults = $this->getDefaultConstructorOptions();
        $options = array_merge($defaults, $options);
        $this->options = $options;
        $this->factory = new AdapterFactory();
    }

    /**
     * Init hook.
     *
     * @return Updater Returns $this, for possible object-chaining.
     */
    public function init()
    {
        $options = $this->getOptions();
        $factory = $this->getFactory();
        $adapter = $factory->factory($options['adapter'], $options['options']);

        return $this->setAdapter($adapter);
    }

    /**
     * Gets default constructor options, to ensure a baseline of options are set.
     *
     * @return array Default option key/values.
     */
    protected function getDefaultConstructorOptions()
    {
        return [
            'adapter' => 'digital-ocean',
            'options' => [],
        ];
    }

    /**
     * Getter for the options property
     * @return array The options for the Updater.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Getter for the adapter property.
     *
     * @return AdapterFactory The factory to use for creating adapters.
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Getter for the adapter property.
     *
     * @return AdapterInterface The adapter to use for operations.
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Setter for the adapter property.
     *
     * @param AdapterInterface $adapter The adapter to use for operations.
     *
     * @return Updater Returns $this, for possible object-chaining.
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Main usage method. Updates DNS.
     *
     * @param  string $name   The name to update.
     * @param  string $domain The domain name to update (example.com).
     * @param  string $ip     The IPv4 value to update to.
     *
     * @return Updater Returns $this, for possible object-chaining.
     */
    public function update($name, $domain, $ip)
    {
        $adapter = $this->getAdapter();
        $adapter->update($name, $domain, $ip);

    }
}
