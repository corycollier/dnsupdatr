# DnsUpdatr

[![Build Status](https://travis-ci.org/corycollier/dnsupdatr.svg?branch=master)](https://travis-ci.org/corycollier/dnsupdatr)

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

if ($ip) {
    $updater->init();
    $updater->update('testing', 'example.com', $ip);
}
```
Using crontab, an entry to check every 5 minutes for the IP address, and update if necessary might be
```sh
*/5 * * * * /usr/local/bin/php /path/to/sample.php > /dev/null 2>&1
```
