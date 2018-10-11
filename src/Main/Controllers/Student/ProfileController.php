<?php

namespace Main\Controllers\Student;

use Main\Models\Student;
use Main\Transformers\ProfileTransformer;
use Main\Transformers\StudentTransformer;
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
        $admin = User::where('username', $args['username'])->firstOrFail();
        $requestUser = $this->auth->requestUser($request);
        $followingStatus = false;

        return $response->withJson($admin);
    }

    public function showAll(Request $request, Response $response, array $args)
    {
        $admin = User::all();
        $requestUser = $this->auth->requestUser($request);
        $followingStatus = false;

        if ($requestUser) {
            $followingStatus = $requestUser->isFollowing($user->id);
        }

        return $response->withJson($admin);
    }

    public function follow(Request $request, Response $response, array $args)
    {
        $requestUser = $this->auth->requestUser($request);
        $user = User::query()->where('username', $args['username'])->firstOrFail();

        $requestUser->follow($user->id);

        return $response->withJson($user);
    }

    public function unfollow(Request $request, Response $response, array $args)
    {
        $requestUser = $this->auth->requestUser($request);
        $user = User::query()->where('username', $args['username'])->firstOrFail();

        $requestUser->unFollow($user->id);

        return $response->withJson($user);
    }

}
