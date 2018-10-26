How to configure the bundle
===========================

[Back to installation](./bundle-installation.md)

This is the available configuration for the bundle

config.yml
```yml
graph_client_bundle
    sources:
        paths: [] # By default it's [%kernel_root_dir%/Resources/graphql"]
        extension: "" # By default it's ".graphql"
    api: #All elements must been configured 
        host: "" # The host
        uri: "" # The api uri to contact
        token: "" # The token generated by mutation on the api
    logging_enabled: false # If you want to log queries to see in the debug toolbar
```

config_dev.yml
```yml
graph_client_bundle:
    logging_enabled: true
```

After configuration, for test mod you need to add the CSAGuzzle header blacklist


No you can use the bundle without problems. If you want to change the queries cache adapter, 
it's simple to do [here](./bundle-query-service.md)

If you want to directly use the service, you can check the [usage doc](./bundle-usage.md)