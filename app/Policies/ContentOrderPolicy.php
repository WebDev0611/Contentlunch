<?php

namespace App\Policies;

use App\User;
use App\WriterAccessOrder;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class ContentOrderPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function show(User $user, WriterAccessOrder $order)
    {
        return $order->user->id == $user->id;
    }
}
