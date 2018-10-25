How to use the service
======================

[Back to menu](../README.md) / [Back to configuration](./bundle-configuration.md)

First you need to [configure the bundle](./bundle-configuration.md). After you can add your
queries in the `paths` array configuration. In convention, the fragments are in sub folders.

To test this, you need to use the github api configuration : 

```yaml
graphql_client:
    api:
        host: "https://api.github.com"
        uri: "graphql"
        token: CHANGE_ME
    logging_enabled: false
```

See those examples : 
* [Basic usage](./usage/basic.md)
* [Basic with variables](./usage/basic-with-variables.md)
* [Basic usage with chained queries](./usage/basic-with-chained.md)
