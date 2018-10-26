How to use the service
======================

[Back to configuration](./bundle-configuration.md)

First you need to [configure the bundle](./bundle-configuration.md). After you can add your
queries in the `paths` array configuration. In convention, the fragments are in sub folders.

* Query example :
`your/folder/graphql/allTheaters.graphql`
```graphql
{
    allTheater (%criterion%) {
        ...myFragment
    }
}
```
The criterion will be replaced by an array (see below)
By convention you must name the file like the query name

* Fragment example :
`your/folder/graphql/fragments/myFragments.graphql`
```graphql
fragment myFragment on QueryElement{
    id
    name
    ...otherFragment
}
```
By convention you must name the file like the fragment name

* Call service example :
Now you can use your service 
```php
$query = $this->client
            ->generateQuery('allTheater', ['criterion' => ['id'=> 10, 'search' => 'test']])
            ->getQuery();

// OR

$result = $this->client
            ->generateQuery('allTheater', ['criterion' => ['id'=> 10, 'search' => 'test']])
            ->getResults();
```
By convention the first parameter is the query name

* You can also use the prepare function :
```php
$client = $this->>client->prepare(
    <<<GRAPHQL
{
    allTheater(
        id: $id,
        search: 'test'
    ) {
        ...myFragment
    }
}
GRAPHQL
    )->generateQuery(md5('allTheater' . $id));
$query = $client->getQuery();
$result = $client->getResults();
```
Here we use an md5 to have an unique cache key for the query.

[Back to menu](../readme.md)
