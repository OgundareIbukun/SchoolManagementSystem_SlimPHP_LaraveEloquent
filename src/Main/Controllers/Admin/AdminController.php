<?php

namespace Main\Controllers\Admin;

use Main\Transformers\AdminTransformer;
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
            $data = $this->fractal->createData(new Item($admin, new AdminTransformer()))->toArray();

            return $response->withJson(['admin' => $data]);
        };
    }


    public function update(Request $request, Response $response)
    {
        if ($admin = $this->auth->requestAdmin($request)) {
            $requestParams = $request->getParam('admin');

            $validation = $this->validateUpdateRequest($requestParams, $admin->id);

            if ($validation->failed()) {
                return $response->withJson(['errors' => $validation->getErrors()], 422);
            }

            $admin->update([
                'email' => isset($requestParams['email']) ? $requestParams['email'] : $admin->email,
                'username' => isset($requestParams['username']) ? $requestParams['username'] : $admin->username,
                'bio' => isset($requestParams['bio']) ? $requestParams['bio'] : $admin->bio,
                'image' => isset($requestParams['image']) ? $requestParams['image'] : $admin->image,
                'moto' => isset($requestParams['moto']) ? $requestParams['moto'] : $admin->moto,
                'address' => isset($requestParams['address']) ? $requestParams['address'] : $admin->address,
                'mission' => isset($requestParams['mission']) ? $requestParams['mission'] : $admin->mission,
                'vision' => isset($requestParams['vision']) ? $requestParams['vision'] : $admin->vision,
                'about' => isset($requestParams['about']) ? $requestParams['about'] : $admin->about,
                'phone' => isset($requestParams['phone']) ? $requestParams['phone'] : $admin->phone,
                'search_term' => isset($requestParams['search_term']) ? $requestParams['search_term'] : $admin->search_term,
                'password' => isset($requestParams['password']) ? password_hash(
                    $requestParams['password'],
                    PASSWORD_DEFAULT
                ) : $admin->password,

            ]);
            $data = $this->fractal->createData(new Item($admin, new AdminTransformer()))->toArray();

            return $response->withJson($data);
        };
    }

    /**
     * @param array
     *
     * @return \Main\Validation\Validator
     */
    protected function validateUpdateRequest($values, $adminId)
    {
        return $this->validator->validateArray(
            $values,
            [
                'email' => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->email()
                        ->existsWhenUpdate($this->db->table('admins'), 'email', $adminId)
                ),
                'username' => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->existsWhenUpdate($this->db->table('admins'), 'username', $adminId)
                ),
                'pswd' => v::optional(v::noWhitespace()->notEmpty()),
            ]
        );
    }
}
