<?

/* US Holiday Calculations in PHP
 * Version 1.02
 * by Dan Kaplan <design@abledesign.com>
 * Last Modified: April 15, 2001
 * ------------------------------------------------------------------------
 * The holiday calculations on this page were assembled for
 * use in MyCalendar:  http://abledesign.com/programs/MyCalendar/
 * 
 * USE THIS LIBRARY AT YOUR OWN RISK; no warranties are expressed or
 * implied. You may modify the file however you see fit, so long as
 * you retain this header information and any credits to other sources
 * throughout the file.  If you make any modifications or improvements,
 * please send them via email to Dan Kaplan <design@abledesign.com>.
 * ------------------------------------------------------------------------
*/

// Gregorian Calendar = 1583 or later
class CalcHoliday{
		var $HoldArry = array(
			array('New Year\'s Day',
							'format_date($YEAR, 1, 1)',
							true),
			array('New Year\'s Day Observed',
							'observed_day($YEAR, 1, 1)',
							true),
			array('Martin Luther King Day',
							'get_holiday($YEAR, 1, 1, 3)', // Third Monday in January
							true),
			array('Valentine\'s Day',
							'format_date($YEAR, 2, 14)',
							true),
			array('President\'s Day',
							'get_holiday($YEAR, 2, 1, 3)', // Third Monday in February
							true),
			array('St. Patrick\'s Day',
							'format_date($YEAR, 3, 17)',
							true),
			array('Easter',
							'calculate_easter($YEAR)',
							true),
			array('Cinco De Mayo',
							'format_date($YEAR, 5, 5)',
							true),
			array('Memorial Day',
							'get_holiday($YEAR, 5, 1)', // Last Monday in May
							true),
			array('Independence Day',
							'format_date($YEAR, 7, 4)',
							true),
			array('Independence Day Observed',
							'observed_day($YEAR, 7, 4)',
							true),
			array('Labor Day',
							'get_holiday($YEAR, 9, 1, 1)', // First Monday in September
							true),
			array('Columbus Day',
							'get_holiday($YEAR, 10, 1, 2)', // Second Monday in October
							true),
			array('Halloween',
							'format_date($YEAR, 10, 31)',
							true),
			array('Thanksgiving',
							'get_holiday($YEAR, 11, 4, 4)', // Fourth Thursday in November
							true),
			array('Black Friday',
							'get_holiday($YEAR, 11, 5, 4)', // Fourth Friday in November
							true),
			array('Christmas day',
							'format_date($YEAR, 12, 25)',
							true)						
			);
	function findHoliday($HOL, $YEAR){
		foreach($this->HoldArry as $v){
			if($v[0] == $HOL){ eval('$Date = $this->'.$v[1].';');
				return $Date; break;
			}
		}
	}
	function getHolidays($YEAR){
		$Holidays = array();
		foreach($this->HoldArry as $v){
			if($v[2] == true) eval('$Holidays[\''.str_replace("'","\'",$v[0]).'\'] = $this->'.$v[1].';');
		}
		return $Holidays;
	}
	function isHoliday($DATE){
		$YEAR = substr($DATE,0,4);
		foreach($this->HoldArry as $v){
			if($v[2] == true) {
				eval('$Holiday = $this->'.$v[1].';');
				if($Holiday == $DATE) return true;
			}
		}
		return false;
	}
	function setActive($HOL,$VAL){
		foreach($this->HoldArry as $k => $v){
			if($v[0] == $HOL) { $this->HoldArry[$k][2] = $VAL; break; }
		}
	}
	function format_date($year, $month, $day) {
			// pad single digit months/days with a leading zero for consistency (aesthetics)
			// and format the date as desired: YYYY-MM-DD by default
	
			if (strlen($month) == 1) {
					$month = "0". $month;
			}
			if (strlen($day) == 1) {
					$day = "0". $day;
			}
			$date = $year ."-". $month ."-". $day;
			return $date;
	}
	
	// the following function get_holiday() is based on the work done by
	// Marcos J. Montes: http://www.smart.net/~mmontes/ushols.html
	//
	// if $week is not passed in, then we are checking for the last week of the month
	function get_holiday($year, $month, $day_of_week, $week="") {
			if ( (($week != "") && (($week > 5) || ($week < 1))) || ($day_of_week > 6) || ($day_of_week < 0) ) {
					// $day_of_week must be between 0 and 6 (Sun=0, ... Sat=6); $week must be between 1 and 5
					return FALSE;
			} else {
					if (!$week || ($week == "")) {
							$lastday = date("t", mktime(0,0,0,$month,1,$year));
							$temp = (date("w",mktime(0,0,0,$month,$lastday,$year)) - $day_of_week) % 7;
					} else {
							$temp = ($day_of_week - date("w",mktime(0,0,0,$month,1,$year))) % 7;
					}
					
					if ($temp < 0) {
							$temp += 7;
					}
	
					if (!$week || ($week == "")) {
							$day = $lastday - $temp;
					} else {
							$day = (7 * $week) - 6 + $temp;
					}
	
					return $this->format_date($year, $month, $day);
			}
	}
	
	function observed_day($year, $month, $day) {
			// sat -> fri & sun -> mon, any exceptions?
			//
			// should check $lastday for bumping forward and $firstday for bumping back,
			// although New Year's & Easter look to be the only holidays that potentially
			// move to a different month, and both are accounted for.
	
			$dow = date("w", mktime(0, 0, 0, $month, $day, $year));
			
			if ($dow == 0) {
					$dow = $day + 1;
			} elseif ($dow == 6) {
					if (($month == 1) && ($day == 1)) {    // New Year's on a Saturday
							$year--;
							$month = 12;
							$dow = 31;
					} else {
							$dow = $day - 1;
					}
			} else {
					$dow = $day;
			}
	
			return $this->format_date($year, $month, $dow);
	}
	
	function calculate_easter($y) {
			// In the text below, 'intval($var1/$var2)' represents an integer division neglecting
			// the remainder, while % is division keeping only the remainder. So 30/7=4, and 30%7=2
			//
			// This algorithm is from Practical Astronomy With Your Calculator, 2nd Edition by Peter
			// Duffett-Smith. It was originally from Butcher's Ecclesiastical Calendar, published in
			// 1876. This algorithm has also been published in the 1922 book General Astronomy by
			// Spencer Jones; in The Journal of the British Astronomical Association (Vol.88, page
			// 91, December 1977); and in Astronomical Algorithms (1991) by Jean Meeus. 
	
			$a = $y%19;
			$b = intval($y/100);
			$c = $y%100;
			$d = intval($b/4);
			$e = $b%4;
			$f = intval(($b+8)/25);
			$g = intval(($b-$f+1)/3);
			$h = (19*$a+$b-$d-$g+15)%30;
			$i = intval($c/4);
			$k = $c%4;
			$l = (32+2*$e+2*$i-$h-$k)%7;
			$m = intval(($a+11*$h+22*$l)/451);
			$p = ($h+$l-7*$m+114)%31;
			$EasterMonth = intval(($h+$l-7*$m+114)/31);    // [3 = March, 4 = April]
			$EasterDay = $p+1;    // (day in Easter Month)
			
			return $this->format_date($y, $EasterMonth, $EasterDay);
	}
	/////////////////////////////////////////////////////////////////////////////
	// end of calculation functions; place the dates you wish to calculate below
	/////////////////////////////////////////////////////////////////////////////
}




//$Holidays = new CalcHoliday;
//echo $Holidays->findHoliday('Thanksgiving',date("Y"));
//$Holidays->setActive('Easter', false);
//print_r($Holidays->getHolidays(date("Y")));
//echo "Is 2009-04-12 a holiday: ";
//var_dump($Holidays->isHoliday('2009-04-12'));


?>