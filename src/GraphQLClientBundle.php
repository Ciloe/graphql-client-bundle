<?php

namespace GraphQLClientBundle;

use GraphQLClientBundle\DependencyInjection\GraphQLClientExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GraphQLClientBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new GraphQLClientExtension();
    }
}
