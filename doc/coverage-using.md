How to use coverage graph checker
=================================

[Back to menu](../readme.md)

Use with bundle
------------------
Before you need to [install the bundle](./bundle-installation.md) without configurations.
To use the bundle you must configure the client.
This is the available [configuration](./bundle-configuration.md).

When you have configured your bundle, put in your `config_dev.php` 
the `graph_client.client.coverage.enabled: true`

You will have the coverage in your Symfony Toolbar.

Use without bundle
------------------
You need to create a wrapper with you data (can be an array or a \stdClass)
```php
<?php

require './vendor/autoload.php';

use AlloCine\GraphClient\CoverageChecker\ArrayWrapper;

// With a class
$baz = new \stdClass();
$baz->baz = 0;
$bar = new \stdClass();
$bar->bar = $baz;
$foo = new \stdClass();
$foo->foo = $bar;
$foo->foobar = 1;
$wrapper = new ArrayWrapper($foo);

// With an array
$wrapper2 = new ArrayWrapper([
    'foo' => [
        'bar' => ['baz']
    ],
    'foobar' => 1
]);
```
After you can use values in you wrapper like an array or a class
```php
// Like an array
echo $wrapper['foobar'];

// Like a class
echo $wrapper->foobar;
```
When you have finish to use your variables in you template for example, you can get the coverage
status.

* With AbsoluteChecker

The absolute checker will test the bytes length of unused fields. You can change the limit
by default at 0

```php
$checker = new AbsoluteChecker();
$errors = $checker->check($wrapper);
```

* With RelativeChecker

The relative checker will test the total ratio on unused fields. You can change the limit
by default at 100

```php
$checker = new RelativeChecker();
$errors = $checker->check($wrapper);
```

