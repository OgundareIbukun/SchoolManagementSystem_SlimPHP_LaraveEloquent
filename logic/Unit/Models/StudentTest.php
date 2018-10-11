<?php

namespace Logic\Unit\Models;

use Main\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Logic\BaseCase;
use Logic\UseDatabaseTrait;

class StudentTest extends BaseCase
{

    use UseDatabaseTrait;

    /** @ */
    public function a_stuend_can_have_many_subjects()
    {
        $student = new Student();

        $this->assertInstanceOf(HasMany::class, $student->subjects());
        $this->assertInstanceOf(Subject::class, $student->subjects()->getRelated());
    }

    /** @ */
    public function it_return_default_image_profile_when_user_does_not_have_an_image()
    {
        $defaultImageUrl = '';
        $studentWithoutImage = $this->createStudent();
        $studentWithImage = $this->createStudent(['image' => 'http://image.jpg']);

        $this->assertEquals($defaultImageUrl, $studentWithoutImage->image);
        $this->assertEquals('http://image.jpg', $studentWithImage->image);
    }
}