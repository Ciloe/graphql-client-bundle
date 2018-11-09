Basic Usage
===========

[Backward](../bundle-usage.md)

For example, a basic call in the controller : 
```php
<?php

namespace App\Controller;

use GraphClientPhp\Client\BasicClient;
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
        $graph = $this->get('graph_client_bundle.client');

        $result = $graph
            ->query('query {
          viewer {
            name
             repositories (first: 1) {
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

Show [basic with variables](./basic-with-variables.md)
