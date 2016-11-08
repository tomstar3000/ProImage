<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
//require_once($r_path.'control_panel/scripts/test_login.php');

$data = file_get_contents("php://input");

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

//$data = '<event number="fun" photographer="RyanSerpan"></event>';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$event_number = clean_variable($tree['EVENT']['ATTRIBUTES']['NUMBER'],true);
$photographer = clean_variable($tree['EVENT']['ATTRIBUTES']['PHOTOGRAPHER'],true);
$Email = clean_variable($tree['EVENT']['ATTRIBUTES']['EMAIL'],true);
$code = $event_number.$photographer;
$cookieName = 'PhotoExpress_'.$code;
$group_id = 0;
mysql_select_db($database_cp_connection, $cp_connection);
$query_check = "SELECT `event_id` FROM `photo_event` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_num` = '$event_number' AND `cust_handle` = '$photographer' AND `event_use` = 'y' ORDER BY `event_id`";
$check = mysql_query($query_check, $cp_connection) or die(mysql_error());

$total_check = mysql_num_rows($check);
if($total_check==0) die();

$event_number = array();
$event_number2 = array();
while($row_check = mysql_fetch_assoc($check)){ array_push($event_number, "`event_id` ='".$row_check['event_id']."'");
array_push($event_number2, "`photo_event`.`event_id` ='".$row_check['event_id']."'"); }

$event_number = implode(" OR ",$event_number);
$event_number2 = implode(" OR ",$event_number2);
$query_get_info = "SELECT `event_name`,`event_copyright`,`event_opacity`,`event_frequency`,`event_price_id`,`event_short`
	FROM `photo_event` 
	WHERE $event_number
	ORDER BY `event_name`;";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

function buildGroup($prntId, $n){
	if($n >= 4) return;
	global $cp_connection, $event_number;
	$query_get_info = "SELECT * FROM `photo_event_group`
	WHERE ($event_number)
		AND `parnt_group_id` = '$prntId'
		AND `group_use` = 'y'
	ORDER BY `group_name` ASC;";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$total_get_info = mysql_num_rows($get_info);
	if($total_get_info == 0) return;
	while($row_get_info = mysql_fetch_assoc($get_info)){
		echo '<group id="'.$row_get_info['group_id'].'" name="'.$row_get_info['group_name'].'">';
		buildGroup($row_get_info['group_id'], $n+1);
		echo '</group>';
	}
}
function buildImages(){
	global $cp_connection, $event_number2, $cookieName, $Email, $code;
	$query_get_info = "SELECT `photo_event_group`.`group_id`, `photo_event_images`.* 
		FROM `photo_event` 
		INNER JOIN `photo_event_group` 
			ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
		WHERE ($event_number2) 
			AND `group_use` = 'y' 
			AND `image_active` = 'y' 
		ORDER BY `group_name` ASC, `photo_event`.`event_id` ASC, `image_name` ASC, `image_large` ASC";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$total_get_info = mysql_num_rows($get_info);
	if($total_get_info == 0) return;
	echo '<images>';
	while($row_get_info = mysql_fetch_assoc($get_info)){
		$name = ($row_get_info['image_name'] == "") ? $row_get_info['image_large'] : $row_get_info['image_name'];
		$folder = substr($row_get_info['image_folder'],0,-11);
		echo '<image id="'.$row_get_info['image_id'].'" groupid="'.$row_get_info['group_id'].'" tiny="'.$row_get_info['image_tiny'].'" small="'.$row_get_info['image_small'].'" zoom="'.$row_get_info['image_small'].'" folder="'.$folder.'">'.$name.'</image>';
	}
	$query_get_info = "SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_email` = '$Email' AND `fav_code` = '$code' LIMIT 0,1";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$total_get_info = mysql_num_rows($get_info);
	if($total_get_info > 0) {
		$row_get_info = mysql_fetch_assoc($get_info);
		echo $row_get_info['fav_xml'];
	} else if(isset($_COOKIE[$cookieName])){
		$cookieValue = urldecode(base64_decode($_COOKIE[$cookieName]));
		echo $cookieValue;
	} 
	echo '</images>';
}

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo '<event name="'.$row_get_info['event_name'].'" price_id="'.$row_get_info['event_price_id'].'" desc="'.str_replace($replace,$with,$row_get_info['event_short']).'" copy="'.$row_get_info['event_copyright'].'" opacity="'.$row_get_info['event_opacity'].'" freq="'.$row_get_info['event_frequency'].'">';
buildGroup(0, 1);

$query_get_info = "SELECT `fav_code` FROM `photo_cust_favories` WHERE `fav_email` = '$Email' AND `fav_code` = '$code' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_get_info = mysql_num_rows($get_info);
if(isset($_COOKIE[$cookieName]) || $total_get_info > 0) echo '<group id="0" name="Favorites"></group>';
echo '</event>';
buildImages();

mysql_free_result($get_info);
?>
