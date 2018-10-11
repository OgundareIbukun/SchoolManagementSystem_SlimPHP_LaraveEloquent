<?php

namespace Main\Controllers\Student;

use Main\Transformers\StudentTransformer;
use Interop\Container\ContainerInterface;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class StudentController
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
     * StudentController constructor.
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
        if ($student = $this->auth->requestStudent($request)) {
            $data = $this->fractal->createData(new Item($student, new StudentTransformer()))->toArray();

            return $response->withJson(['student' => $data]);
        };
    }

    public function update(Request $request, Response $response)
    {
        if ($student = $this->auth->requestStudent($request)) {
            $requestParams = $request->getParam('student');

            $validation = $this->validateUpdateRequest($requestParams, $student->id);

            if ($validation->failed()) {
                return $response->withJson(['errors' => $validation->getErrors()], 422);
            }

            $student->update([
                'email'    => isset($requestParams['email']) ? $requestParams['email'] : $student->email,
                'username' => isset($requestParams['username']) ? $requestParams['username'] : $student->username,
                'bio'      => isset($requestParams['bio']) ? $requestParams['bio'] : $student->bio,
                'image'    => isset($requestParams['image']) ? $requestParams['image'] : $student->image,
                'moto'     => isset($requestParams['moto']) ? $requestParams['moto'] : $student->moto,
                'address'  => isset($requestParams['address']) ? $requestParams['address'] : $student->address,
                'mission'  => isset($requestParams['mission']) ? $requestParams['mission'] : $student->mission,
                'vision'   => isset($requestParams['vision']) ? $requestParams['vision'] : $student->vision,
                'about'    => isset($requestParams['about']) ? $requestParams['about'] : $student->about,
                'phone'    => isset($requestParams['phone']) ? $requestParams['phone'] : $student->phone,
                'search_term' => isset($requestParams['search_term']) ? $requestParams['search_term'] : $student->search_term,
                'password' => isset($requestParams['password']) ? password_hash($requestParams['password'],
                    PASSWORD_DEFAULT) : $student->password,
            ]);

            $data = $this->fractal->createData(new Item($student, new StudentTransformer()))->toArray();

            return $response->withJson(['student' => $data]);
        };
    }

    /**
     * @param array
     *
     * @return \Main\Validation\Validator
     */
    protected function validateUpdateRequest($values, $studentId)
    {
        return $this->validator->validateArray($values,
            [
                'first_name'    => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->first_name()
                        ->existsWhenUpdate($this->db->table('students'), 'first_name', $studentId)
                ),

                'second_name' => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->second_name()
                        ->existsWhenUpdate($this->db->table('students'), 'second_name', $studentId)
                ),

                'middle_name' => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->middle_name()
                        ->existsWhenUpdate($this->db->table('students'), 'middle_name', $studentId)
                ),

                'username' => v::optional(
                    v::noWhitespace()
                        ->notEmpty()
                        ->existsWhenUpdate($this->db->table('students'), 'username', $studentId)
                ),
                'password' => v::optional(v::noWhitespace()->notEmpty()),
            ]);
    }
}