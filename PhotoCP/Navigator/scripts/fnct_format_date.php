<?
function format_date($date, $date_format, $time_format, $show_date, $show_time){
// Short = Jan, 1, 2000
// Long = January , 1, 2000
// DayShort = Mon Jan. 1, 2000
// DayLong = Monday January 1, 2000
// Dash = 01/01/2006
// Military = 21:00
// Standard = 3:00 PM
	$replace = array("/", "-", ".", ":"," ");
	$date = str_replace($replace, "", $date);
	$year = substr($date, 0, 4);
	$month = substr($date, 4, 2);
	$day = substr($date, 6, 2);
	$hour = substr($date, 8, 2);
	$minute = substr($date, 10, 2);
	$second = substr($date, 12, 2);
	$date = "";
	if($show_date){
		if($date_format == "Short"){
			$date = date("M. j, Y", mktime(0, 0, 0, $month, $day, $year));
		} else if ($date_format == "Long"){
			$date = date("F j, Y", mktime(0, 0, 0, $month, $day, $year));
		} else if ($date_format == "DayShort"){
			$date = date("D M. j, Y", mktime(0, 0, 0, $month, $day, $year));
		} else if ($date_format == "DayLong"){
			$date = date("l F j, Y", mktime(0, 0, 0, $month, $day, $year));
		} else if ($date_format == "Dash"){
			$date = date("m/d/Y", mktime(0, 0, 0, $month, $day, $year));
		} else if ($date_format == "DashShort"){
			$date = date("m/d/y", mktime(0, 0, 0, $month, $day, $year));
		}
	} 
	if($show_time){
		if($time_format == "Military"){
			$date .= " ".date("G:i", mktime($hour, $minute, $second, 1, 1, 2000));
		} else if ($time_format == "Standard"){
			$date .= " ".date("g:i A", mktime($hour, $minute, $second, 1, 1, 2000));
		}
	}
	return trim($date);
}
?>
