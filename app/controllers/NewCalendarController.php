<?php

class NewCalendarController extends BaseController {

	protected function user_prep(){
		$user = $user = Confide::user();
		$this->user_id = $user->id;
		$account_q = AccountUser::where('user_id',$this->user_id);
		$account = $account_q->get();
		$account_id = $account[0]->account_id;
		$this->account_id = $account_id;
	}

	public function index($year = 0, $month = 0, $day = 0 ){
		$this->user_prep();

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

		$number_of_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		$campaigns_start_date = false;
		$campaigns_end_date = false;
		$campaigns_end = $year . '-'. $month . '-' . $number_of_days . ' 00:00:00';
	
		$campaigns = $this->pull_campaigns($campaigns_start_date, $campaigns_end_date, $campaigns_end);

		$prev_month = ($month === 1) ? 12 : $month-1;

		$next_month = ($month === 12) ? 1 : $month+1;

		return View::make('2016.calendar.index', array(
			'calendar' => $calendar_layout, 
			'default_month' => $default_month,
			'default_year' => $default_year,
			'next_month' => $next_month,
			'prev_month' => $prev_month,
			'campaigns' => json_encode($campaigns),
			'user_id' => $this->user_id,
			'account_id'=> $this->account_id
			) );
	}

	public function campaigns($year = 0 , $month = 0 ){
		$this->user_prep();

		$campaigns = $this->pull_campaigns();

		return View::make('2016.calendar.campaigns', array(
			'campaigns' => json_encode($campaigns),
			'user_id' => $this->user_id,
			'account_id'=> $this->account_id
		));
	}

	public function weekly($year = 0, $month = 0, $day = 0){
		$this->user_prep();

		if(!$day){
			$day = date('d');
		}

		if(!$year){
			$year = date('Y');
		}

		if(!$month){
			$month = date('n');
		}

		$date_string =  strtotime($year . '-' . $month . '-' . $day);

		$week_number = date('W', $date_string);
		$week_string = strtotime( $year . 'W' . $week_number );
		//echo 'week: ' . $week_number;

		$start_weekdate = date('Y-m-d', $week_string );
		$end_weekdate = date('Y-m-d', strtotime("+1 week", $week_string ) );

		$start_weektimestamp = $week_string;
		$end_weektimestamp =  strtotime("+1 week",  $week_string  );

		function generate_weekly_calendar($start = 0){

			//weekly header
			$week_string = '<table class="calendar">';
			$week_string .= '<thead class="calendar-week"><th disabled></th>';
			$curr_time = $start;
			for($d = 0; $d < 7; $d++){
				$week_string .= '<th>' . date('D j, M',$curr_time) . "</th>";
				$curr_time = strtotime("+1 day", $curr_time);
			}
			$week_string .= '</thead><tbody class="calendar-week-hours">';

			//hourly rows
			$start_time_row = date('H', strtotime('10:00:00') );
			$end_time_row = date('H', strtotime('23:00:00') );

			$curr_hour = $start_time_row;
			for($curr_hour = $start_time_row; $curr_hour < $end_time_row; $curr_hour++){
				$week_string .= '<tr>';
				//daily columns
				$day_column = '<td disabled>' . date('gA', strtotime($curr_hour . ':00:00' ) ) .'</td>';
				for($dc = 0; $dc < 7; $dc++){
					$day_column .= '<td></td>';
				}
				$week_string .= $day_column . '</tr>';
			}
			$week_string .= '</tbody></table>';
			return $week_string;
		}

		$weekly_calendar_string =  generate_weekly_calendar( $start_weektimestamp );

		$day_of_week = date('l', $date_string);
		$display_month = date('F', $date_string);
		$display_day = date('d', $date_string);

		$next_day_string = date( "Y/m/d", strtotime( "+1 day", $date_string  ) );
		$prev_day_string = date( "Y/m/d", strtotime( "-1 day",  $date_string ) );


		$query_date_start = date("Y-m-d", $date_string) .' 00:00:00';
		$query_date_end = date("Y-m-d", strtotime("+1 day", $date_string) ) . ' 00:00:00';

		$content_q = Content::where('submit_date','>',$query_date_start)
						->where('submit_date','<',$query_date_end);

		$content = $content_q->get();

		//$display_date = date()

		return View::make('2016.calendar.weekly',array(
			'display_month' => $display_month,
			'numeric_month' => $month,
			'display_year' => $year,
			'display_day' => $display_day,
			'display_day_of_week' => $day_of_week,

			'next_day_string' => $next_day_string,
			'prev_day_string' => $prev_day_string,

			'user_id' => $this->user_id,
			'account_id' => $this->account_id,

			'content_items' => $content,
			'weekly_calendar' => $weekly_calendar_string
		));
	}

	public function daily($year = 0, $month = 0, $day = 0){
		$this->user_prep();

		if(!$day){
			$day = date('d');
		}

		if(!$year){
			$year = date('Y');
		}

		if(!$month){
			$month = date('n');
		}

		$date_string =  strtotime($year . '-' . $month . '-' . $day);

		$day_of_week = date('l', $date_string);
		$display_month = date('F', $date_string);
		$display_day = date('d', $date_string);

		$next_day_string = date( "Y/m/d", strtotime( "+1 day", $date_string  ) );
		$prev_day_string = date( "Y/m/d", strtotime( "-1 day",  $date_string ) );


		$query_date_start = date("Y-m-d", $date_string) .' 00:00:00';
		$query_date_end = date("Y-m-d", strtotime("+1 day", $date_string) ) . ' 00:00:00';

		$content_q = Content::where('submit_date','>',$query_date_start)
						->where('submit_date','<',$query_date_end);

		$content = $content_q->get();

		return View::make('2016.calendar.daily',array(
			'display_month' => $display_month,
			'numeric_month' => $month,
			'display_year' => $year,
			'display_day' => $display_day,
			'display_day_of_week' => $day_of_week,

			'next_day_string' => $next_day_string,
			'prev_day_string' => $prev_day_string,

			'user_id' => $this->user_id,
			'account_id' => $this->account_id,

			'content_items' => $content 
		));
	}

	protected function pull_campaigns($start_date = false, $end_date = false, $end = false, $status = false){

	 	$query = Campaign::where('account_id', $this->account_id)
	      ->with('tags')
	      ->with('user')
	      ->with('campaign_type')
	      ->with('guest_collaborators')
	      ->with('collaborators.image');

	    if ($status) {
	      $query->where('status', $status);
	    }

	    if($end_date) {
	      $query->where('end_date', '>=', $end_date);
	    }

	    if($start_date) {
	        $query->where('end_date', '>=', $start_date);
	    }

	    if($end) {
	        $query->where('start_date', '<=', $end);
	    }

	    $user = Confide::User();
	    if(!$this->hasAbility([], ['calendar_view_campaigns_other'], $this->account_id)) {
	      $query->where('user_id', $this->user_id);
	    }

	    $results = $query->get();
	    // print_r($user->id);
	    // print_r($results);
	    // exit;
	    return $results;

	}
}
