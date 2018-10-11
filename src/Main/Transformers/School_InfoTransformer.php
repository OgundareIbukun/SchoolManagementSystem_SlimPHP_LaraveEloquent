<?php

namespace Main\Transformers;

use Main\Models\Admin;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{

    public function transform(Admin $admin)
    {
        return [
            'id' => (int)$admin->id,
            'updatedAt' => optional($admin->update_at)->toIso8601String(),
            'username' => $admin->userame,
            'bio' => $admin->bio,
            'moto' => $admin->moto,
            'address' => $admin->address,
            'mission' => $admin->mission,
            'vision' => $admin->vision,
            'about' => $admin->about,
            'search_term' => $admin->search_term,
            'image' => $admin->image,

        ];
    }
}
