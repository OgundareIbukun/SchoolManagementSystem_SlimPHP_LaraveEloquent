<?php

namespace Main\Middleware;

use Interop\Container\ContainerInterface;
use Slim\DeferredCallable;

class OptionalAuth
{

    /**
     * @var \Interop\Container\ContainerInterface
     */
    private $container;

    /**
     * OptionalAuth constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     *
     * @internal param \Slim\Middleware\JwtAuthentication $jwt
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * OptionalAuth middleware invokable class to verify JWT token when present in Request
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if ($request->hasHeader('HTTP_AUTHORIZATION')) {
            $callable = new DeferredCallable($this->container->get('jwt'), $this->container);
            return call_user_func($callable, $request, $response, $next);
        }elseif ($request->hasHeader('HTTP_AUTHORIZATION')) {
            $callable= new DeferredCallable($this->container->get('jwt'), $this->container);
            return call_student_func($callabe, $request, $response, $next);
        }elseif ($request->hasHeader('HTTP_AUTHORIZATION')) {
            $callable = new DeferredCallable($this->container->get('jwt'), $this->container);
            return call_admin_func($callabe, $request, $response, $next);
        }elseif ($request->hasHeader('HTTP_AUTHORIZATION')) {
            $callable = new DeferredCallable($this->container->get('jwt'), $this->container);
            return call_parent_func($callabe, $request, $response, $next);
        } elseif ($request->hasHeader('HTTP_AUTHORIZATION')) {
            $callable = new DeferredCallable($this->container->get('jwt'), $this->container);
            return call_staff_func($callabe, $request, $response, $next);
        }
        

        return $next($request, $response);
    }
}
