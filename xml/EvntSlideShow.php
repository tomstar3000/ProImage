<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');
require_once($r_path.'scripts/cart/encrypt.php');

//$data = '<event number="Goldrush" photographer="RyanSerpan" email="ryan.serpan@aevium.com" id="1"></event>';	

$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$event_number = clean_variable($tree['EVENT']['ATTRIBUTES']['NUMBER'],true);
$event_number = str_replace("&amp;","&",$event_number);
$photographer = clean_variable($tree['EVENT']['ATTRIBUTES']['PHOTOGRAPHER'],true);
$Email = clean_variable($tree['EVENT']['ATTRIBUTES']['EMAIL'],true);
$EvntID = clean_variable($tree['EVENT']['ATTRIBUTES']['ID'],true);
$code = $event_number.$photographer;
$code = preg_replace("/[^a-zA-Z0-9]/", "",$code);

$getCvr = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getCvr->mysql("SELECT `image_id`
								FROM `photo_event`
								INNER JOIN `photo_event_images`
									ON `photo_event_images`.`image_id` = `photo_event`.`event_image`
								WHERE `photo_event`.`event_id` = '$EvntID';");

ob_start();
echo '<data>';
echo '<images>';
$Cover = false;
if($getCvr->TotalRows() > 0){
	$Cover = true;
	$getCvr = $getCvr->Rows();
	$data = array("id" =>  $getCvr[0]['image_id'], "width" => 500, "height" => 330,"Master"=>true);
	echo '<image file="images/image.php?data='.base64_encode(encrypt_data(serialize($data))).'&t='.time().'"></image>';
	unset($data);
}

$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getFavs->mysql("SELECT `fav_xml`
	FROM `photo_cust_favories`
	WHERE `fav_code` = '".$code."'
		AND `fav_email` = '".$Email."'
		AND `fav_occurance` = '2'
	ORDER BY `fav_date` DESC;");
$getFavs = $getFavs->Rows();
if(strlen(trim($getFavs[0]['fav_xml'])) > 0){
	$getFavs = explode(".",$getFavs[0]['fav_xml']);
	foreach ($getFavs as $r){
		if($r != ''){
			$getFav = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getFav->mysql("SELECT `image_id` FROM `photo_event_images` WHERE `image_id` = '".$r."';");
			$getFav = $getFav->Rows();
			if(($Cover==true && $getFav[0]['image_id'] != $getCvr[0]['image_id']) || $Cover===false){
				$data = array("id" => $getFav[0]['image_id'], "width" => 500, "height" => 330, "Master"=>true);
				echo '<image file="images/image.php?data='.base64_encode(encrypt_data(serialize($data))).'&t='.time().'"></image>';
				unset($data);
			}
		}
	}
}
echo '</images>';
echo '</data>';

$rawData = ob_get_contents();
ob_end_clean();

$TMP = tempnam("/tmp", "TEMP"); // Create Temp File
$HND = fopen($TMP, "w"); // Open Temp File for writing
fwrite($HND, $rawData);	// Write RawFile Data to Temp File
fclose($HND);	// Close File Handle

header("Pragma: no-cache");
header("Expires: 0");
header('Content-type: text/xml');
header('Content-Length: '. filesize($TMP));

$HND = fopen($TMP, "r");
while (!feof($HND)) echo fread($HND, 8192);
fclose($HND);

unlink($TMP);
exit(0);
?>