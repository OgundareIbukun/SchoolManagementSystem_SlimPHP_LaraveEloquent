<?php

namespace Logic;

use Main\Models\User;
use Main\Models\Student;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
abstract class BaseCase extends TestCase
{

    /** @var  \Slim\App */
    protected $app;

    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a  is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->createApplication();

        $traits = array_flip(class_uses_recursive(static::class));
        if (isset($traits[UseDatabaseTrait::class])) {
            $this->runMigration();
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a  is executed.
     */
    protected function tearDown()
    {
        $traits = array_flip(class_uses_recursive(static::class));
        if (isset($traits[UseDatabaseTrait::class])) {
            $this->rollbackMigration();
        }
        unset($this->app);
        parent::tearDown();
    }

    /**
     * Process the application given a request method and URI
     *
     * @param string            $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string            $requestUri    the request URI
     * @param array|object|null $requestData   the request data
     *
     * @param array             $headers
     *
     * @return \Psr\Http\Message\ResponseInterface|\Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null, $headers = [])
    {
        // Create a mock environment for ing with
        $environment = Environment::mock(
            array_merge(
                [
                    'REQUEST_METHOD'   => $requestMethod,
                    'REQUEST_URI'      => $requestUri,
                    'Content-Type'     => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                ],
                $headers
            )
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Process the application and Return the response
        return $this->app->process($request, $response);
    }

    /**
     * Make a request to the Api
     *
     * @param       $requestMethod
     * @param       $requestUri
     * @param null  $requestData
     * @param array $headers
     *
     * @return \Psr\Http\Message\ResponseInterface|\Slim\Http\Response
     */
    public function request($requestMethod, $requestUri, $requestData = null, $headers = [])
    {
        return $this->runApp($requestMethod, $requestUri, $requestData, $headers);
    }

    /**
     * Generate a new JWT token for the given user
     *
     * @param \Main\Models\User $user
     *
     * @return mixed
     */
    public function getValidToken(User $user)
    {
        $user->update([
            'token' =>
                $token = $this->app->getContainer()->get('auth')->generateToken($user),
        ]);

        return $token;
    }

    /**
     * Create a new User
     *
     * @param array $overrides
     *
     * @return User
     */
    public function createUser($overrides = [])
    {
        $faker = Factory::create();
        $attributes = [
            'username' => $faker->userName,
            'email'    => $faker->email,
            'password' => $password = password_hash($faker->password, PASSWORD_DEFAULT),
        ];
        $overrides['password'] = isset($overrides['password']) ? $overrides['password'] : $password;

        return User::create(array_merge($attributes, $overrides));
    }

    /**
     * Create A User with valid JWT Token
     *
     * @param array $overrides
     *
     * @return User
     */
    public function createUserWithValidToken($overrides = [])
    {
        $user = $this->createUser($overrides);
        $this->getValidToken($user);

        return $user->fresh();
    }

    /**
     * Generate a new JWT token for the given user
     *
     * @param \Main\Models\Student $student
     *
     * @return mixed please come back to fix this????????????????????????????
     */
    // public function getValidToken(Student $student)
    // {
    //     $student->update([
    //         'token' =>
    //             $token = $this->app->getContainer()->get('auth')->generateToken($student),
    //     ]);

    //     return $token;
    // }

    /**
     * Create a new User
     *
     * @param array $overrides
     *
     * @return Student
     */
    public function createStudent($overrides = [])
    {
        $faker = Factory::create();
        $attributes = [
            'first_name' => $faker->first_name,
            'second_name' => $faker->second_name,
            'last_name' => $faker->last_name,
            'username' => $faker->userName,
            'email' => $faker->email,
            'password' => $password = password_hash($faker->password, PASSWORD_DEFAULT),
        ];
        $overrides['password'] = isset($overrides['password']) ? $overrides['password'] : $password;

        return Student::create(array_merge($attributes, $overrides));
    }




    /**
     * Create A Student with valid JWT Token
     *
     * @param array $overrides
     *
     * @return Student
     */
    public function createStudentWithValidToken($overrides = [])
    {
        $student = $this->createStudent($overrides);
        $this->getValidToken($student);

        return $student->fresh();
    }

    protected function createApplication()
    {
        // Use the application settings
        $settings = require __DIR__ . '/../src/settings.php';

        // Instantiate the application
        $this->app = $app = new App($settings);

        // Set up dependencies
        require ROOT . 'src/dependencies.php';

        // Register middleware
        if ($this->withMiddleware) {
            require ROOT . 'src/middleware.php';
        }

        // Register routes
        require ROOT . 'src/routes.php';
    }
}
