<?php

namespace Logic\Functional\Auth;

use Logic\BaseCase;
use Logic\UseDatabaseTrait;

class RegisterUsers extends BaseCase
{

    use UseDatabaseTrait;

    /** @ */
    public function it_creates_new_user_when_provided_with_all_required_parameters()
    {
        $payload = [
            'user' => [
                'username' => 'newUser',
                'email'    => 'new@example.com',
                'password' => 'secret',
            ],
        ];

        $response = $this->request('POST', '/api/admin', $payload);
        $body = json_decode((string)$response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode(), "Response must return 200 status code");
        $this->assertDatabaseHas('users', ['username' => 'newUser']);
        unset($payload['user']['password']);
        $this->assertArraySubset($payload, $body, 'Return response must contains user data');
    }

    /** @ */
    public function registration_requires_a_user_name()
    {
        $payload = [
            'user' => [
                'email'    => 'new@example.com',
                'password' => 'secret',
            ],
        ];

        $response = $this->request('POST', '/api/admin', $payload);

        $this->assertEquals(422, $response->getStatusCode());
        $errors = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('username', $errors['errors']);
    }

    /** @ */
    public function registration_requires_a_valid_email()
    {
        $payload = [
            'user' => [
                'username' => 'username',
                'email'    => 'NotValid@email',
                'password' => 'secret',
            ],
        ];

        $response = $this->request('POST', '/api/admin', $payload);

        $this->assertEquals(422, $response->getStatusCode());
        $errors = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('email', $errors['errors']);
    }
}
