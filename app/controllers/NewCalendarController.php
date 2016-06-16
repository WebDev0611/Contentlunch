<?php

class NewCalendarController extends BaseController {

	public function index($year = 0, $month = 0){

		/* draws a calendar */
		function draw_calendar($month,$year){

			/* draw table */
			$calendar = '<table class="calendar">';

			/* table headings */
			$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
			$calendar.= '<thead><tr><th>'.implode('</th><th>',$headings).'</th></tr></thead><tbody class="calendar-month-days">';

			/* days and weeks vars now ... */
			$running_day = date('w',mktime(0,0,0,$month,1,$year));
			$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
			$days_in_this_week = 1;
			$day_counter = 0;
			$dates_array = array();

			/* row for week one */
			$calendar.= '<tr>';

			/* print "blank" days until the first of the current week */
			for($x = 0; $x < $running_day; $x++):
				$calendar.= '<td disabled> </td>';
				$days_in_this_week++;
			endfor;

			/* keep going with days.... */
			for($list_day = 1; $list_day <= $days_in_month; $list_day++):
				$calendar.= '<td>';
					/* add in the day number */ //<time class="calendar-month-date">13</time>
					$calendar.= '<time class="calendar-month-date">'.$list_day.'</time>';

					/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
					$calendar.= str_repeat('<p> </p>',2);
					
				$calendar.= '</td>';
				if($running_day == 6):
					$calendar.= '</tr>';
					if(($day_counter+1) != $days_in_month):
						$calendar.= '<tr>';
					endif;
					$running_day = -1;
					$days_in_this_week = 0;
				endif;
				$days_in_this_week++; $running_day++; $day_counter++;
			endfor;

			/* finish the rest of the days in the week */
			if($days_in_this_week < 8):
				for($x = 1; $x <= (8 - $days_in_this_week); $x++):
					$calendar.= '<td disabled> </td>';
				endfor;
			endif;

			/* final row */
			$calendar.= '</tr>';

			/* end the table */
			$calendar.= '</tbody></table>';
			
			/* all done, return result */
			return $calendar;
		}

		$default_month = date('F');
		$default_year = date('Y');

		if($month !== 0 && $year !== 0){
			$default_month = date('F', strtotime( $year . '-' . $month ) );
			$default_year = $year;
			$calendar_layout = draw_calendar( $month, $year );

		}else{
			$year = $default_year;
			$month = date('m', strtotime( $year . '-' . $default_month ) );

			$calendar_layout = draw_calendar( date('n'), date('Y') );
		}

		$prev_month = ($month === 1) ? 12 : $month-1;

		$next_month = ($month === 12) ? 1 : $month+1;

		return View::make('2016.calendar.index', array(
			'calendar' => $calendar_layout, 
			'default_month' => $default_month,
			'default_year' => $default_year,
			'next_month' => $next_month,
			'prev_month' => $prev_month,
			) );
	}

	public function campaigns(){
		return View::make('2016.calendar.campaigns');
	}
}
