<?php

namespace Main\Services\Auth;

use Main\Models\User;
use Main\Models\Student;
use Main\Models\Admin;
use DateTime;
use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager;
use Slim\Collection;
use Slim\Http\Request;

class Auth
{

    const SUBJECT_IDENTIFIER = 'username';

    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    private $db;
    /**
     * @var array
     */
    private $appConfig;

    /**
     * Auth constructor.
     *
     * @param \Illuminate\Database\Capsule\Manager $db
     * @param array|\Slim\Collection               $appConfig
     */
    public function __construct(Manager $db, Collection $appConfig)
    {
        $this->db = $db;
        $this->appConfig = $appConfig;
    }

    /**
     * Generate a new JWT token
     *
     * @param \Main\Models\User $user
     *
     * @return string
     * @internal param string $subjectIdentifier The username of the subject user.
     *
     */
    public function generateToken(User $user)
    {
        $now = new DateTime();
        $future = new DateTime("now +2 hours");

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => base64_encode(random_bytes(16)),
            'iss' => $this->appConfig['app']['url'],  // Issuer
            "sub" => $user->{self::SUBJECT_IDENTIFIER},
        ];

        $secret = $this->appConfig['jwt']['secret'];
        $token = JWT::encode($payload, $secret, "HS256");

        return $token;
    }

    /**
     * Attempt to find the user based on email and verify password
     *
     * @param $email
     * @param $password
     *
     * @return bool|\Main\Models\User
     */
    public function attempt($email, $password)
    {
        if ( ! $user = User::where('email', $email)->first()) {
            return false;
        }elseif (! $admin = Admin::where('email', $email)->first()) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            return $user;
        }elseif (password_verify($password, $admin->password)) {
            return $admin;
        }

        return false;
    }

    /**
     * Retrieve a user by the JWT token from the request
     *
     * @param \Slim\Http\Request $request
     *
     * @return User|null
     */
    public function requestUser(Request $request)
    {
        // Should add more validation to the present and validity of the token?
        if ($token = $request->getAttribute('token')) {
            return User::where(static::SUBJECT_IDENTIFIER, '=', $token->sub)->first();
        };
    }

    /**
     * Retrieve a user by the JWT token from the request
     *
     * @param \Slim\Http\Request $request
     *
     * @return Admin|null
     */
    public function requestAdmin(Request $request)
    {
        // Should add more validation to the present and validity of the token?
        if ($token = $request->getAttribute('token')) {
            return Admin::where(static::SUBJECT_IDENTIFIER, '=', $token->sub)->first();
        };
    }

}