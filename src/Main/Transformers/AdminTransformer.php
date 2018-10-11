<?php

namespace Main\Transformers;

use Main\Models\Admin;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{

    public function transform(Admin $admin)
    {
        return [
            'id'        => (int)$admin->id,
            'email'     => $admin->email,
            'username'  => $admin->username,
            'token'     => $admin->token,
            'createdAt' => optional($admin->created_at)->toIso8601String(),
            'updatedAt' => optional($admin->update_at)->toIso8601String(),
        ];
    }
}
