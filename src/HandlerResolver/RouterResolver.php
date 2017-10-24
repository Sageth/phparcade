<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 10/15/2017
 * Time: 1:12 PM
 */
namespace PhpArcade\HandlerResolver;

use DI\Container;
use Phroute\Phroute\HandlerResolverInterface;

class RouterResolver implements HandlerResolverInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function resolve($handler)
    {
        /*
         * Only attempt resolve uninstantiated objects which will be in the form:
         *
         *      $handler = ['App\Controllers\Home', 'method'];
         */
        if(is_array($handler) and is_string($handler[0]))
        {
            $handler[0] = $this->container->get($handler[0]);
        }

        return $handler;
    }
}