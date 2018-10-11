<?php

namespace Logic\Profile;

use Logic\BaseCase;
use Logic\UseDatabaseTrait;

class Profile extends BaseCase
{

    use UseDatabaseTrait;

    /** @ */
    public function get_profile_returns_profile_without_authentication()
    {
        $user = $this->createUser();

        $response = $this->request('GET', '/api/profiles/' . $user->username);
        $body = json_decode((string)$response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $body);
        $this->assertEquals($user->username, $body['profile']['username']);
    }

    /** @ */
    public function get_profile_returns_profile_with_optional_authentication()
    {
        $user = $this->createUser();
        $requestUser = $this->createUserWithValidToken();
        $headers = ['HTTP_AUTHORIZATION' => 'Token ' . $requestUser->token];

        $response = $this->request('GET', '/api/profiles/' . $user->username, null, $headers);
        $body = json_decode((string)$response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $body);
    }

    /** @ */
    public function get_profile_returns_401_when_an_invalid_token_is_attached()
    {
        $user = $this->createUser();
        $headers = ['HTTP_AUTHORIZATION' => 'Token Invalid Token'];

        $response = $this->request('GET', '/api/profiles/' . $user->username, null, $headers);

        $this->assertEquals(401,
            $response->getStatusCode(),
            "Response status code must be 401 because of an invalid token");
    }


    /** @ */
    public function get_profile_returns_correct_following_status()
    {
        $user = $this->createUser();
        $requestUser = $this->createUserWithValidToken();
        $headers = ['HTTP_AUTHORIZATION' => 'Token ' . $requestUser->token];

        $response = $this->request('GET', '/api/profiles/' . $user->username, null, $headers);
        $body = json_decode((string)$response->getBody(), true);
        $this->assertFalse($body['profile']['following']);

        $requestUser->follow($user->id);

        $response = $this->request('GET', '/api/profiles/' . $user->username, null, $headers);
        $body = json_decode((string)$response->getBody(), true);
        $this->assertTrue($body['profile']['following']);
    }

    /** @ */
    public function it_returns_404_status_code_when_profile_is_not_found()
    {
        $response = $this->request('GET', '/api/profiles/not-found-profile');
        $this->assertEquals(404, $response->getStatusCode());
    }

}