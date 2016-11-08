<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
define ("CronJob", true);

set_time_limit(0);
ini_set('max_execution_time',600);

$is_creditcard = true;
$is_gateway = "Authorize_AIM";
$is_live = true;
$is_error = false;
$is_process = true;
$is_capture = "AUTH_CAPTURE";
$is_method = "CC";
$approval = false;
$Error = false;
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
else if (strtoupper(substr(PHP_OS,0,3))=='MAC')	$eol="\r"; 
else $eol="\n";

//$r_path .= "var/www/www.proimagesoftware.com/";
$r_path = "/srv/proimage/current/";
require_once $r_path.'scripts/cart/encrypt.php';
require_once $r_path.'scripts/fnct_send_email.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once $r_path.'scripts/fnct_holidays.php';
require_once $r_path.'Connections/cp_connection.php';

mysql_select_db($database_cp_connection, $cp_connection);
require_once($r_path.'control_panel/scripts/query_change_list.php');
$today = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d"),date("Y")));
$tendate = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")+10,date("Y")));
$thirtydate = date("Y-m-d H:i:s", mktime(0,0,0,date("m")-1,date("d")-1,date("Y")));
$thirtydate1 = date("Y-m-d H:i:s", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$date = date("Y-m-d H:i:s", mktime(12,0,0,date("m"),date("d"),date("Y")));
$date1 = date("Y-m-d H:i:s", mktime(12,0,0,date("m"),(date("d")-1),date("Y"))); // Date of expired events
$finaldate = date("Y-m-d H:i:s", mktime(12,0,0,date("m"),(date("d")+15),date("Y")));
$diedate = array();
for($n = 2; $n<=29; $n++){ array_push($diedate,date("Y-m-d H:i:s", mktime(12,0,0,date("m"),date("d")-$n,date("Y")))); }

function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path_parts = pathinfo($CSSLink);
	//$path = $path_parts['basename'];
	//$path_parts = pathinfo($Template);
	$path = $path_parts['dirname']."/".$path_parts['basename'];
	$Handle = fopen($r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
	while (!feof($Handle)) $CSS .= fread($Handle, 8192);
	fclose($Handle);
	return "";
}
function FindCSS2($StyleSheet){
	global $CSS;
	$CSS .= $StyleSheet;
	return "";
}
function cleanUpHTML($text) {
	$text = ereg_replace(" style=[^>]*","", $text);
	return ($text);
}

require_once $r_path.'scripts/cron_job/_specialEvents.php';
?>
