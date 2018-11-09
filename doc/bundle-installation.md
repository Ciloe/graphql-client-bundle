How to install the project ([SF 4.1](#install-on-sf-4.1-and-greatest) OR [SF 3.4/4.0](#install-on-sf-3.4-and-4.0))
==========================

[Back to menu](../README.md)

## Install on SF 3.4 and 4.0

At first, you need to add the project to your composer : 
`composer require ciloe/graph-client-bundle`

And add the bundle to your `AppKernel.php` located in 
`your/project/folder/app/`

```php
<?php
    // ... Som codes
    public function registerBundles()
    {
        $bundles = array(
            //Some bundles
            new AlloCine\GraphClient\Bundle\GraphClientBundle(),
            new Csa\Bundle\GuzzleBundle\CsaGuzzleBundle(), //You must load this bundle after Graph Client bundle
        );
        
        return $bundles;
    }
```

That's all. Now you need to [configure](./bundle-configuration.md) it.

## Install on SF 4.1 and greatest

At first, you need to add the project to your composer : 
`composer require ciloe/graph-client-bundle`

And add the bundle to your `bundles.php` located in 
`your/project/folder/config/`

```php
<?php
  return [
      // Some bundles
      GraphClientBundle\GraphClientBundle::class => ['all' => true],
  ];
```

That's all. Now you need to [configure](./bundle-configuration.md) it.
