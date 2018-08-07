<?php

namespace App\Http\Middleware;

use App\Calendar;
use Closure;

class CalendarMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accountCalendars = \App\Account::selectedAccount()->calendars()->with('contentTypes')->get();
        $action = 'calendar';
        if (strpos($request->route()->getActionName(), 'weekly') !== false) {
            $action = 'weekly';
        }
        if (strpos($request->route()->getActionName(), 'daily') !== false) {
            $action = 'daily';
        }

        if ($request->route('id') !== null) {
            if ($accountCalendars->contains($request->route('id'))) {
                return $next($request);
            }
            else {
                return redirect(route('calendarMonthly'));
            }
        }
        else if (count($accountCalendars) == 0) {
            // If user doesn't have a calendar yet, create one
            $calendar = Calendar::createDefaultCalendar($request);

            return redirect($action . '/' . $calendar->id);
        }
        else {
            return redirect($action . '/' . $accountCalendars->first()->id);
        }
    }
}
