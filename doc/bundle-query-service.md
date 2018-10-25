How to change the query cache adapter
=====================================

[Back to menu](../README.md) / [Back to configuration](./bundle-configuration.md)

The service `graphql_client_bundle.adapter` can be overwrite by an other declaration.

In your `my/bundle/Resources/config/service.yaml`

MemCache ([see the doc](http://api.symfony.com/3.3/Symfony/Component/Cache/Simple/MemcachedCache.html)):
```yml
    graphql_client_bundle.adapter:
        class: Symfony\Component\Cache\Adapter\MemcachedAdapter
        arguments: [\Memcached $client, $namespace = '', $defaultLifetime = 0]
```

Redis ([see the doc](http://api.symfony.com/3.3/Symfony/Component/Cache/Simple/RedisCache.html)):
```yml
    graphql_client_bundle.adapter:
        class: Symfony\Component\Cache\Adapter\RedisAdapter
        arguments: [$redisClient, $namespace = '', $defaultLifetime = 0]
```

Doctrine ([see the doc](http://api.symfony.com/3.3/Symfony/Component/Cache/Simple/DoctrineCache.html)):
```yml
    graphql_client_bundle.adapter:
        class: Symfony\Component\Cache\Adapter\DoctrineAdapter
        arguments: [CacheProvider $provider, $namespace = '', $defaultLifetime = 0]
```

APCU ([see the doc](http://api.symfony.com/3.3/Symfony/Component/Cache/Simple/ApcuCache.html)):
```yml
    graphql_client_bundle.adapter:
        class: Symfony\Component\Cache\Adapter\ApcuAdapter
        arguments: [$namespace = '', $defaultLifetime = 0, $version = null]
```

For the others adapter see [the doc](https://symfony.com/doc/current/components/cache.html)

Have fun !
