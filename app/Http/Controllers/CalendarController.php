<?php

namespace App\Http\Controllers;

use View;
use App\User;
use Auth;
use App\Campaign;
use App\Task;

class CalendarController extends Controller {
	public $user_id;
	public $account_id;
	public $campaigns;
	public $tasks;

	public function __construct(){
    	$user = Auth::user();
    	$this->user_id = $user->id;
    	$this->account_id = 0;
    	$this->campaigns = Auth::user()->campaigns()->get();
    	$this->tasks = Auth::user()->tasks()->get();
	} 

	public function index($year = 0, $month = 0, $day = 0 ){
		/* draws a calendar */
		function draw_calendar($month,$year){

			/* draw table */
			$calendar = '<table class="calendar">';

			/* table headings */
			$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
			$calendar.= '<thead class="calendar-month"><tr><th>'.implode('</th><th>',$headings).'</th></tr></thead><tbody class="calendar-month-days">';

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
				$d_string = $year . '-' . $month . '-' . $list_day;
				$calendar.= '<td id="date-' . $d_string . '" data-cell-date="' . $d_string . '">';
					/* add in the day number */ //<time class="calendar-month-date">13</time>
					$calendar.= '<time class="calendar-month-date">'.$list_day.'</time>';

					/** BACKBONE RENDERS AFTER HERE !! **/
					
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
			$month = date('n', strtotime( $year . '-' . $month ) );
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
	
		//$campaigns = $this->pull_campaigns($campaigns_start_date, $campaigns_end_date, $campaigns_end);

		$campaigns = array();

		$prev_month = ($month === 1) ? 12 : $month-1;

		$next_month = ($month === 12) ? 1 : $month+1;

		return View::make('calendar.index', array(
			'calendar' => $calendar_layout, 
			'default_month' => $default_month,
			'default_year' => $default_year,
			'next_month' => $next_month,
			'prev_month' => $prev_month,
			'campaigns' => $this->campaigns,
			'user_id' => $this->user_id,
			'account_id'=> $this->account_id,
			'tasks' => $this->tasks->toJson(),
			) );
	}

	public function campaigns($year = 0 , $month = 0 ){

		$campaigns = $this->campaigns;
		if(!$year){
			$year = date('Y');
		}
		if(!$month){
			$month = date('m');
		}

		function generate_campaign_calendar($year = 0){
			//for each month, main container
			$main_calendar_string = '<table class="calendar-timeline"><thead class="calendar-timeline-months"><tr>';
			//calendar heading
			for( $m = 1; $m < 13; $m++ ){
				$main_calendar_string .= '<th>' . date('F', strtotime($year . '-' . $m ) ) . '</th>';
			}
			$main_calendar_string .= '</tr></thead>';
			
			//days for each of the months

			$main_calendar_string .= '<tbody class="calendar-timeline-days-container"><tr>';
			//this iterates month cells
			for( $m = 1; $m < 13; $m++ ){
				$main_calendar_string .= '<td>';

				//get days in the month
				$days_in_month = date('t', strtotime($year . '-' . $m ));

				//the months days table - header
				$main_calendar_string .= '<table class="calendar-timeline-days"><thead><tr>';
				for($d = 1; $d <= $days_in_month; $d++ ){
					$main_calendar_string .= '<th>' . $d . "</th>\n";
				}
				$main_calendar_string .= '</tr></thead>';
				//end the header days

				//create the days data holders
				$main_calendar_string .= '<tbody><tr>';

				//loop through and create the days - will need to identify this to backbone or resuse this logic
				for($d = 1; $d <= $days_in_month; $d++ ){
					$main_calendar_string .= '<td id="campaign-day-' . $year . '-' . $m . '-'. $d . '"></td>' . "\n";
				}

				//end of the days data holders
				$main_calendar_string .= '</tr></tbody>';


				//end of the months days and data
				$main_calendar_string .= '</table>';



				$main_calendar_string .= '</td>';
			}

			$main_calendar_string .= '</tr></tbody>';

			//close the whole thiing up
			$main_calendar_string .= '</table>';

			return $main_calendar_string;
		}

		// echo generate_campaign_calendar($year);
		// exit;

		return View::make('calendar.campaigns', array(
			'campaigns' => $campaigns->toJson(),
			'user_id' => $this->user_id,
			'account_id'=> $this->account_id,
			'campaign_calendar' => generate_campaign_calendar($year),
			'tasks' => $this->tasks->toJson()
		));
	}

	public function weekly($year = 0, $month = 0, $day = 0){

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
		$end_weekdate = date('Y-m-d', strtotime("+6 days", $week_string ) );

		$start_weektimestamp = $week_string;
		$end_weektimestamp =  strtotime("+1 week",  $week_string  );

		function generate_weekly_calendar($year = 0, $month = 0, $day = 0, $start = 0){

			$date_tracker = array();
			//weekly header
			$week_string = '<table class="calendar">';
			$week_string .= '<thead class="calendar-week"><th disabled></th>';
			$curr_time = $start;
			for($d = 0; $d < 7; $d++){
				$date_tracker[$d] = date('Y-n-j',$curr_time);
				$week_string .= '<th>' . date('D j, M',$curr_time) . "</th>";
				$curr_time = strtotime("+1 day", $curr_time);
			}
			$week_string .= '</thead><tbody class="calendar-week-hours">';

			//hourly rows
			$start_time_row = date('H', strtotime('08:00:00') );
			$end_time_row = date('H', strtotime('23:00:00') );

			$curr_hour = $start_time_row;
			for($curr_hour = $start_time_row; $curr_hour < $end_time_row; $curr_hour++){
				$week_string .= '<tr>';
				//daily columns
				$day_column = '<td disabled>' . date('gA', strtotime($curr_hour . ':00:00' ) ) .'</td>';
				for($dc = 0; $dc < 7; $dc++){
					$day_column .= '<td id="date-' . $date_tracker[$dc] . '-' . $curr_hour . '0000' . '" data-cell-date-time="' . $date_tracker[$dc] . '-' . $curr_hour . '0000' . '"></td>';
				}
				$week_string .= $day_column . '</tr>';
			}
			$week_string .= '</tbody></table>';
			return $week_string;
		}

		$month = date('n', $date_string);
		$weekly_calendar_string =  generate_weekly_calendar( $year, $month, $day, $start_weektimestamp );

		$day_of_week = date('l', $date_string);
		$display_month = date('F', $date_string);
		$display_day = date('d', $date_string);		

		$next_week_string = date( "Y/m/d", strtotime( "+1 week", strtotime($start_weekdate) ) );
		$prev_week_string = date( "Y/m/d", strtotime( "-1 week", strtotime($start_weekdate) ) );

		$query_date_start = date("Y-m-d", $date_string) .' 00:00:00';
		$query_date_end = date("Y-m-d", strtotime("+1 day", $date_string) ) . ' 00:00:00';

//		$content_q = Content::where('submit_date','>',$query_date_start)
//						->where('submit_date','<',$query_date_end);

		$content = '';// $content_q->get();

		return View::make('calendar.weekly',array(
			'display_month' => $display_month,
			'numeric_month' => $month,
			'display_year' => $year,
			'display_day' => $display_day,
			'display_day_of_week' => $day_of_week,
			'weekly_display_string' => date('M j', strtotime($start_weekdate)) . '-' . date('M j', strtotime($end_weekdate)) . ' ' . $year, 

			'next_day_string' => $next_week_string,
			'prev_day_string' => $prev_week_string,

			'user_id' => $this->user_id,
			'account_id' => $this->account_id,
			'campaigns' => $this->campaigns->toJson(),
			'content_items' => $content,
			'weekly_calendar' => $weekly_calendar_string,
			'tasks' => $this->tasks->toJson()
		));
	}

	public function daily($year = 0, $month = 0, $day = 0){

		if(!$day){
			$day = date('d');
		}

		if(!$year){
			$year = date('Y');
		}

		if(!$month){
			$month = date('n');
		}elseif($month && $year){
			$month = date('n', strtotime($year . '-' . $month) );
		}

		$date_string =  strtotime($year . '-' . $month . '-' . $day);

		$day_of_week = date('l', $date_string);
		$display_month = date('F', $date_string);
		$display_day = date('d', $date_string);

		$next_day_string = date( "Y/m/d", strtotime( "+1 day", $date_string  ) );
		$prev_day_string = date( "Y/m/d", strtotime( "-1 day",  $date_string ) );


		$query_date_start = date("Y-m-d", $date_string) .' 00:00:00';
		$query_date_end = date("Y-m-d", strtotime("+1 day", $date_string) ) . ' 00:00:00';

//		$content_q = Content::where('submit_date','>',$query_date_start)
//						->where('submit_date','<',$query_date_end);

		$content = '';//$content_q->get();

		function generate_daily_calendar($year, $month, $day){
			$daily_timetable = '<table class="calendar"><tbody class="calendar-day">';
            
			$start_time_row = date('H', strtotime('10:00:00') );
			$end_time_row = date('H', strtotime('23:00:00') );

			$curr_hour = $start_time_row;
			for($curr_hour = $start_time_row; $curr_hour < $end_time_row; $curr_hour++){
				$daily_timetable .= '<tr>';
				//daily columns
				$day_column = '<td disabled>' . date('gA', strtotime($curr_hour . ':00:00' ) ) .'</td>';
				
				//content goes here
				$day_column .= '<td id="date-'.$year.'-'.$month.'-'.$day.'-' . $curr_hour . '0000' . '" data-cell-date-time="'.$year.'-'.$month.'-'.$day.'-' . $curr_hour . '0000' . '"></td>';

				$daily_timetable .= $day_column . '</tr>';
			}
			$daily_timetable .= '</tbody></table>';

			return $daily_timetable;
		}

		return View::make('calendar.daily',array(
			'display_month' => $display_month,
			'numeric_month' => $month,
			'display_year' => $year,
			'display_day' => $display_day,
			'display_day_of_week' => $day_of_week,

			'next_day_string' => $next_day_string,
			'prev_day_string' => $prev_day_string,

			'user_id' => $this->user_id,
			'account_id' => $this->account_id,
			'campaigns' => $this->campaigns->toJson(),
			'content_items' => $content,
			'daily_calendar' => generate_daily_calendar($year,$month,$day),
			'tasks' => $this->tasks->toJson()
		));
	}

	protected function pull_campaigns($start_date = false, $end_date = false, $end = false, $status = false){

	 	$query = Campaign::where('user_id', Auth::id() );
	    $results = $query->get();
	    // print_r($user->id);
	    // print_r($results);
	    // exit;
	    return $results;

	}
}
