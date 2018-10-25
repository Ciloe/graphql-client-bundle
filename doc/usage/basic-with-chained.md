Basic Usage with variables
==========================

[Backward](../bundle-usage.md)

Show [basic with variables](./basic-with-variables.md)

For example, a basic call with chained queries in the controller : 
```php
<?php

namespace App\Controller;

use GraphQLClientPhp\Client\BasicClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AcmeController extends Controller
{
    /**
     * @Route("/acme", name="acme")
     */
    public function index()
    {
        /** @var BasicClient $graph */
        $graph = $this->get('graphql_client_bundle.client');

        $result = $graph
            ->addVariable('number', 5)
            ->addQuery('query ($number: Int!) {
          viewer {
            name
             repositories (first: $number) {
               nodes {
                 name
               }
             }
           }
        }')
            ->addVariable('number', 6)
            ->addQuery('query ($number: Int!) {
          viewer {
            name
             repositories (first: $number) {
               nodes {
                 name
               }
             }
           }
        }')
            ->addVariable('number', 10)
            ->addQuery('query ($number: Int!) {
          viewer {
            name
             repositories (first: $number) {
               nodes {
                 name
               }
             }
           }
        }')
        ->sendQueries(true); // Use true for async call

        return $this->render('acme/index.html.twig', [
            'results' => $result
        ]);
    }
}
```

