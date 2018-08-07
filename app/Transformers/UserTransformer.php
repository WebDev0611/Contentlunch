<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'location' => $user->present()->location,
            'profile_image' => $user->present()->profile_image,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at,
            'writer_access_Project_id' => $user->writer_access_Project_id,
            'account_type' => $user->account_type,
            'is_admin' => $user->is_admin,
            'city' => $user->city,
            'country_code' => $user->country_code,
            'address' => $user->address,
            'phone' => $user->phone,
            'selected_account_id' => $user->selected_account_id,
            'stripe_customer_id' => $user->stripe_customer_id,
            'new_email' => $user->new_email,
            'email_confirmation_code' => $user->email_confirmation_code,
            'is_guest' => $user->is_guest,
        ];
    }
}
