<?php

namespace Main\Transformers;

use Main\Models\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
{

    public function transform(Student $student)
    {
        return [
            'id'            => (int)$student->id,
            'email'         => $student->email,
            'createdAt'     => optional($student->created_at)->toIso8601String(),
            'updatedAt'     => optional($student->update_at)->toIso8601String(),
            'username'      => $student->username,
            'bio'           => $student->bio,
            'moto'          => $student->moto,
            'address'       => $student->address,
            'mission'       => $student->mission,
            'vision'        => $student->vision,
            'about'         => $student->about,
            'search_term'   => $student->search_term,
            'image'         => $student->image,
            'token'         => $student->token,

        ];
    }
}