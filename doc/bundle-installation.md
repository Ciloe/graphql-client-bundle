How to install the project
==========================

[Back to menu](../readme.md)

After, you need to add the project to your composer : 
`composer require ciloe/graph-client-bundle`

And add the bundle to you `AppKernel.php` located in 
`your/project/folder/app/`

```php
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

That all. Now you need to [configure](./bundle-configuration.md) it
