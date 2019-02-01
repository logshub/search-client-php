# Logshub search client for PHP

You can use it to manage your search services. This is main connector between
your store and logshub.com search service. Most of the stores are written in
PHP language, and we decided to create this connector to speed up integration.

### Requirements

* PHP 5.5+, 7.0+
* composer - https://getcomposer.org/

### Installation

`composer require logshub/search-client-php`

### Test

`make test`

### Example

```php
<?php
include_once 'vendor/autoload.php';

# you can find your service's details on your logshub.com dashboard.
$location = 'uk01';
$serviceId = '5b5a06d2-23bc-4186-4d1b-3a17bfa7200d';
$apihash = 'xizinISDPH';
$apiSecret = 'ZJLnvZAujIBOTiJVyfImZYLEKdesJPKUntfFztYLiefQsCMgmd';
$client = \Logshub\SearchClient\Client::fromLocation(
   $location, $apihash, $apiSecret);

$product = new \Logshub\SearchClient\Model\Product('1', [
   'name' => 'Laptop Full HD 8GB RAM',
   'price' => 899
]);
$request = new \Logshub\SearchClient\Request\IndexProducts($serviceId, [
   $product
]);
$response = $client->indexProducts($request);

echo $response->getBody() . PHP_EOL;
```

More info at the [documentation](https://www.logshub.com/docs/search-client-php.html).

### Demo usage

Content of config file, required for demo

```
# example content of /etc/logshub-search.ini - replace with your details,
# that can be found on your logshub's service details page
serviceid = "5b5a06d2-23bc-4186-4d1b-3a17bfa7200d"
location = "uk01"
apihash = "xizinISDPH"
apisecret = "ZJLnvZAujIBOTiJVyfImZYLEKdesJPKUntfFztYLiefQsCMgmd"
```

Indexing

```sh
php vendor/bin/logshub-search index \
--config /etc/logshub-search.ini \
--id 4 \
--name "Laptop for gaming asus" \
--price 15.5
```

Searching

```sh
php vendor/bin/logshub-search search \
--config /etc/logshub-search.ini \
--query "laptop"
```

Indexing CSV file

```sh
php vendor/bin/logshub-search index:csv \
--config /etc/logshub-search.ini \
--csv /home/greg/products.csv
```

### Licence

MIT
