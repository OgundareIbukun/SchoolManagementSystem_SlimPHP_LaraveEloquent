<?php

namespace Logic\Functional\Auth;

use Main\Models\User;
use Logic\BaseCase;
use Logic\UseDatabaseTrait;

class Login extends BaseCase
{

    use UseDatabaseTrait;

    /** @ */
    public function a_user_can_obtain_a_jwt_token_after_log_in()
    {
        $user = User::create([
            'username' => 'first_user',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'email'    => 'user@example.com',
        ]);

        $payload = [
            'user' => ['email' => $user->email, 'password' => 'secret'],
        ];

        $response = $this->request('POST',
            '/api/admin/login',
            $payload

        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', json_decode((string)$response->getBody(), true)['user']);
    }
}
