<?php

namespace Main\Controllers\User;

use Main\Transformers\UserTransformer;
use Interop\Container\ContainerInterface;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class UserController
{

    /** @var \Main\Services\Auth\Auth */
    protected $auth;
    /** @var \League\Fractal\Manager */
    protected $fractal;
    /** @var \Illuminate\Database\Capsule\Manager */
    protected $db;
    /** @var \Main\Validation\Validator */
    protected $validator;

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
        $this->validator = $container->get('validator');
        $this->db = $container->get('db');
    }

    public function show(Request $request, Response $response)
    {
        if ($admin = $this->auth->requestUser($request)) {
            $data = $this->fractal->createData(new Item($user, new UserTransformer()))->toArray();

            return $response->withJson($admin);
        };
    }


    public function update(Request $request, Response $response)
    {
        if ($user = $this->auth->requestUser($request)) {
            $requestParams = $request->getParam('user');

            $validation = $this->validateUpdateRequest($requestParams, $user->id);

            if ($validation->failed()) {
                return $response->withJson(['errors' => $validation->getErrors()], 422);
            }

            $user->update([
                'email'       => isset($requestParams['email']) ? $requestParams['email'] : $user->email,
                'username'    => isset($requestParams['username']) ? $requestParams['username'] : $user->username,
                'bio'         => isset($requestParams['bio']) ? $requestParams['bio'] : $user->bio,
                'image'       => isset($requestParams['image']) ? $requestParams['image'] : $user->image,
                'moto'        => isset($requestParams['moto']) ? $requestParams['moto'] : $user->moto,
                'address'     => isset($requestParams['address']) ? $requestParams['address'] : $user->address,
                'mission'     => isset($requestParams['mission']) ? $requestParams['mission'] : $user->mission,
                'vision'      => isset($requestParams['vision']) ? $requestParams['vision'] : $user->vision,
                'about'       => isset($requestParams['about']) ? $requestParams['about'] : $user->about,
                'phone'       => isset($requestParams['phone']) ? $requestParams['phone'] : $user->phone,
                'search_term' => isset($requestParams['search_term']) ? $requestParams['search_term'] : $user->search_term,
                'password'    => isset($requestParams['password']) ? password_hash($requestParams['password'],
                    PASSWORD_DEFAULT) : $user->password,

]);
            $data = $this->fractal->createData(new Item($user, new UserTransformer()))->toArray();

            return $response->withJson($data);
        };
    }

    /**
     * @param array
     *
     * @return \Main\Validation\Validator
     */
    protected function validateUpdateRequest($values, $userId)
    {
        return $this->validator->validateArray($values,
            [
                'email'    => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->email()
                        ->existsWhenUpdate($this->db->table('users'), 'email', $userId)
                ),
                'username' => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->existsWhenUpdate($this->db->table('users'), 'username', $userId)
                ),
                'password' => v::optional(v::noWhitespace()->notEmpty()),
            ]);
    }
}
