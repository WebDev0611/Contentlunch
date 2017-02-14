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
        $userCalendars = $request->user()->calendars()->with('contentTypes')->get();
        $action = 'calendar';
        if (strpos($request->route()->getActionName(), 'weekly') !== false) {
            $action = 'weekly';
        }
        if (strpos($request->route()->getActionName(), 'daily') !== false) {
            $action = 'daily';
        }

        if ($request->route('id') !== null) {
            if ($userCalendars->contains($request->route('id'))) {
                return $next($request);
            }
            else {
                abort(404);
            }
        }
        else if (count($userCalendars) == 0) {
            // If user doesn't have a calendar yet, create one
            $calendar = Calendar::createDefaultCalendar($request);

            return redirect($action . '/' . $calendar->id);
        }
        else {
            return redirect($action . '/' . $userCalendars->first()->id);
        }
    }
}
