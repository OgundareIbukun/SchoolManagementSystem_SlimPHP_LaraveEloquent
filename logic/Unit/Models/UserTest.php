<?php

namespace Logic\Unit\Models;

use Main\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Logic\BaseCase;
use Logic\UseDatabaseTrait;

class UserTest extends BaseCase
{

    use UseDatabaseTrait;

    /** @ */
    public function a_user_can_have_many_students()
    {
        $user = new User();

        $this->assertInstanceOf(HasMany::class, $user->students());
        $this->assertInstanceOf(Student::class, $user->students()->getRelated());
    }

    

    /** @ */
    public function it_return_default_image_profile_when_user_does_not_have_an_image()
    {
        $defaultImageUrl = '';
        $userWithoutImage = $this->createUser();
        $userWithImage = $this->createUser(['image' => 'http://image.jpg']);

        $this->assertEquals($defaultImageUrl, $userWithoutImage->image);
        $this->assertEquals('http://image.jpg', $userWithImage->image);
    }
}