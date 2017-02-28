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
        if (!$this->account->hasLimit('calendars')) {
            return true;
        }

        $calendars = $user->calendars()->count();
        $maxCalendars = $this->account->limit('calendars');

        return $calendars < $maxCalendars;
    }
}
