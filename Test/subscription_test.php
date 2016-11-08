<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
define ("CronJob", true);

set_time_limit(0);
ini_set('max_execution_time',600);

$is_creditcard = true;
$is_gateway = "Authorize_ARB";
$is_live = true;
$is_error = false;
$is_process = true;
$is_capture = "STATUS";
$approval = false;
$Error = false;
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; else if (strtoupper(substr(PHP_OS,0,3))=='MAC')	$eol="\r"; else $eol="\n";

$r_path = "/srv/proimage/current/";
require_once $r_path.'scripts/cart/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/emogrifier.php';
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once $r_path.'Connections/cp_connection.php';

$BId = 2722;
$BFName = 'xxxxx';
$BMName = '';
$BLName = 'xxxx';
$BSuffix = '';
$BCName = '';
$BAdd = 'xxxxxx';
$BSuiteApt = '';
$BAdd2 = '';
$BCity = 'XXXXXXXXX';
$BState = 'CO';
$BZip = 'XXXXXXXXXXXXXXXXX';
$BCount = 'US';
$CCNum = 'xxxxxxxxxxxxxxxxxxxxxxxxxx';
$CC4Num = 'XXXXXXXXXXXXXXXXXXXXXX';
$CType = 'Visa';
$CCV = 790;
$CCM = 6;
$CCY = 2013;			
$grandtotal = 35;

$ReNewInterval = 1;
$ReNewUnit = 'months';
$ReNewTotalOccurance = 9999;
$ReNewStart = '2012-04-15';

$is_gateway = "Authorize_ARB";
$is_capture = "SUBSCRIBE";
include ($r_path.'scripts/cart/merchant_ini.php');

echo $subscription_id;
?>