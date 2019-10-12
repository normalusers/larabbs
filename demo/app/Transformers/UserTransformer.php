<?php


namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'eamil' => $user->email,
            'phone' => $user->phone,
            'created_at' => (string)$user->created_at,
            'updated_at' => (string)$user->updated_at,
        ];
    }

}
