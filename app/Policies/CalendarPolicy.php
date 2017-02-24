<?php

namespace App\Policies;

use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $calendars = $user->calendars()->count();
        $maxCalendars = Limit::whereName('calendars')->first()->value;

        return $calendars < $maxCalendars;
    }
}
