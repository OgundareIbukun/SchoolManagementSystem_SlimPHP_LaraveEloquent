<?php

namespace Main\Controllers\Admin;

use Main\Models\Admin;
use Main\Transformers\ProfileTransformer;
use Main\Transformers\AdminTransformer;
use Interop\Container\ContainerInterface;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class ProfileController
{

    /** @var \Main\Services\Auth\Auth */
    protected $auth;
    /** @var \League\Fractal\Manager */
    protected $fractal;

    /**
     * UserController constructor.
     *
     * @param \Interop\Container\ContainerInterface $container
     *
     * @internal param $auth
     */
    public function __construct(ContainerInterface $container)
    {
        $this->auth = $container->get('auth');
        $this->fractal = $container->get('fractal');
    }
//get Single Admin
    public function show(Request $request, Response $response, array $args)
    {
        $admin = Admin::where('username', $args['username'])->firstOrFail();
        $requestAdmin = $this->auth->requestAdmin($request);
        $followingStatus = false;

        return $response->withJson($admin);
    }

    public function showAll(Request $request, Response $response, array $args)
    {
        $admin = Admin::all();
        $requestAdmin = $this->auth->requestAdmin($request);
        $followingStatus = false;

        if ($requestAdmin) {
            $followingStatus = $requestAdmin->isFollowing($admin->id);
        }

        return $response->withJson($admin);
    }

    public function follow(Request $request, Response $response, array $args)
    {
        $requestAdmin = $this->auth->requestAdmin($request);
        $admin = User::query()->where('username', $args['username'])->firstOrFail();

        $requestAdmin->follow($admin->id);

        return $response->withJson($admin);
    }

    public function unfollow(Request $request, Response $response, array $args)
    {
        $requestAdmin = $this->auth->requestAdmin($request);
        $admin = User::query()->where('username', $args['username'])->firstOrFail();

        $requestAdmin->unFollow($admin->id);

        return $response->withJson($admin);
    }

}
