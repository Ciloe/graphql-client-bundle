Basic Usage with variables
==========================

[Backward](../bundle-usage.md)

Show [basic usage](./basic.md)

For example, a basic call with variables in the controller : 
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
            ->setVariables(['number' => 3])
            ->query('query ($number: Int!) {
          viewer {
            name
             repositories (first: $number) {
               nodes {
                 name
               }
             }
           }
        }');

        return $this->render('acme/index.html.twig', [
            'results' => $result
        ]);
    }
}
```

Show [usage with chained queries](./basic-with-chained.md)
