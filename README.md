# DnsUpdatr

This code serves as a way to allow for dynamic dns updating.

## Usage
```php
<?php
require 'vendor/autoload.php';

use DnsUpdatr\Fetcher;
use DnsUpdatr\Updater;

$token   = 'token-value' // @see https://cloud.digitalocean.com/settings/api/tokens;
$fetcher = new Fetcher();
$ip      = $fetcher->getIpAddress();

$updater = new Updater([
    'adapter' => 'digital-ocean',
    'options' => [
        'token' => $token,
    ]
]);

$updater->init();
$updater->update('testing', 'example.com', $ip);
