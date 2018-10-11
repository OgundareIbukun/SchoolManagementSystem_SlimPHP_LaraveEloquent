<?php

namespace Main\Transformers;

use Main\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
            'id'            => (int)$user->id,
            'email'         => $user->email,
            'createdAt'     => optional($user->created_at)->toIso8601String(),
            'updatedAt'     => optional($user->update_at)->toIso8601String(),
            'username'      => $user->username,
            'bio'           => $user->bio,
            'moto'          => $user->moto,
            'address'       => $user->address,
            'mission'       => $user->mission,
            'vision'        => $user->vision,
            'about'         => $user->about,
            'search_term'   => $user->search_term,
            'image'         => $user->image,
            'token'         => $user->token,

        ];
    }
}
