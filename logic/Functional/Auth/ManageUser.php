<?php

namespace Logic\Functional\Auth;

use Logic\BaseCase;
use Logic\UseDatabaseTrait;

class ManageUser extends BaseCase
{

    use UseDatabaseTrait;

    /** @ */
    public function returns_a_user_that_is_the_current_user()
    {
        $user = $this->createUser();
        $token = $this->getValidToken($user);
        $headers = ['HTTP_AUTHORIZATION' => 'Token ' . $token];

        $response = $this->request('GET', '/api/admin', null, $headers);

        $body = json_decode((string)$response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode(), "Response must return 200 status code");
        $this->assertEquals($body['user']['username'], $user->username);
    }

    /** @ */
    public function unauthenticated_requests_may_not_get_user_data()
    {
        $response = $this->request('GET', '/api/admin');
        $this->assertEquals(401, $response->getStatusCode(), "Response must return 401 status code");
    }

    /** @ */
    public function an_authenticated_user_can_update_his_details()
    {
        $user = $this->createUserWithValidToken([
            'username' => 'superUserDo',
            'email'    => 'oldemail@example.com',
            'password' => password_hash('secretPassword', PASSWORD_DEFAULT),
            'bio'      => null,
            'image'    => null,
            'moto'    =>  null,
            'address' =>  null,
            'mission' =>  null,
            'vision'  =>  null,
            'phone'   =>  null,
            'about'   =>  null,
            'search_term' =>  null,
        ]);
        $this->assertEquals('superUserDo', $user->username);
        $headers = ['HTTP_AUTHORIZATION' => 'Token ' . $user->token];

        $payload = [
            'user' =>
                [
                    'username' => 'substituteUserAndDo',
                    'password' => 'newPassword',
                    'email'    => 'oldemail@example.com',
                    'bio'      => 'New Bio',
                    'image'    => 'NewImage',
                    'moto'    =>  'NewMoto',
                    'address' =>  'NewAddress',
                    'mission' =>  'NewMission',
                    'vision'  =>  'NewVision',
                    'phone'   =>  'NewPhone',
                    'about'   =>  'NewAbout',
                    'search_term' =>  'NewSearch_term',
                ],
        ];

        $response = $this->request('PUT', '/api/admin', $payload, $headers);

        $user = $user->fresh();

        $this->assertEquals(200, $response->getStatusCode(), "Response must return 200 status code");
        $this->assertEquals('substituteUserAndDo', $user->username);
        $this->assertEquals('oldemail@example.com', $user->email);
        $this->assertEquals('New Bio', $user->bio);
        $this->assertEquals('NewImage', $user->image);
        $this->assertEquals('NewMoto', $user->moto);
        $this->assertEquals('NewAddress', $user->address);
        $this->assertEquals('NewMission', $user->mission);
        $this->assertEquals('NewVision', $user->vision);
        $this->assertEquals('NewPhone', $user->phone);
        $this->assertEquals('NewAbout', $user->about);
        $this->assertEquals('NewSearch_term', $user->search_term);
        $this->assertTrue(password_verify('newPassword', $user->password));
    }
}
