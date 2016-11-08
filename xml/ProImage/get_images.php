<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$r_path=''; for($n=0;$n<(count(explode("/",eregi_replace("//*","/",substr($_SERVER['PHP_SELF'],1))))-1);$n++) $r_path .= "../";
define ("Allow Scripts", true);

$data = $GLOBALS['HTTP_RAW_POST_DATA'];

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');
require_once($r_path.'scripts/cart/encrypt.php');

//$data = '<data number="Goldrush" photographer="RyanSerpan" email="development@proimagesoftware.com" id="1"></data>';	
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$eNum = clean_variable($tree['DATA']['ATTRIBUTES']['NUMBER'],true);
$eNum = str_replace("&amp;","&",$eNum);
$photo = clean_variable($tree['DATA']['ATTRIBUTES']['PHOTOGRAPHER'],true);
$Email = clean_variable($tree['DATA']['ATTRIBUTES']['EMAIL'],true);
$code = $eNum.$photo;
$code = preg_replace("/[^a-zA-Z0-9]/", "",$code);
$id = clean_variable($tree['DATA']['ATTRIBUTES']['ID'],true);

mysql_select_db($database_cp_connection, $cp_connection);

$query_favs = "SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_code` = '".$eNum.$photo."' AND `fav_email` = '".$Email."' AND `fav_occurance` = '2' ORDER BY `fav_id` DESC LIMIT 0,1";
$favs = mysql_query($query_favs, $cp_connection) or die(mysql_error());
$favsArry = mysql_fetch_assoc($favs);
$favsArry = explode(".",$favsArry['fav_xml']);

echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo '<data>';
echo '<images>';
if($id == "-1" || intval($id) == -1){
	$query_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`
		FROM `photo_cust_favories`
		LEFT JOIN `photo_cust_favories_message`
			ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
				OR `photo_cust_favories_message`.`fav_id` IS NULL)
		WHERE `fav_code` = '".$code."'
			AND `fav_occurance` = '2'
		ORDER BY `photo_cust_favories_message`.`fav_date` DESC";
	$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
	
	while($row_info = mysql_fetch_assoc($info)){
		echo '<fav id="'.$row_info['fav_id'].'" email="'.$row_info['fav_email'].'" date="'.$row_info['fav_date'].'" others="'.$row_info['fav_others'].'" favs="'.$row_info['fav_xml'].'">'.$row_info['fav_message'].'</fav>';
	}
	
	$query_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`
		FROM `photo_cust_favories`
		LEFT JOIN `photo_cust_favories_message`
			ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
				OR `photo_cust_favories_message`.`fav_id` IS NULL)
		WHERE `fav_code` = '".$code."'
			AND `fav_occurance` = '1'
		ORDER BY `photo_cust_favories_message`.`fav_date` DESC";
	$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
	while($row_info = mysql_fetch_assoc($info)){
		$TMPdata = '<?xml version="1.0" encoding="iso-8859-1"?><temp id="run">'.$row_info['fav_xml'].'</temp>';
		$TMPparser = new XMLParser($TMPdata, 'raw', 1);
		$TMPtree = $TMPparser->getTree();
		$TMPXML = array();
		if(isset($TMPtree['TEMP']['IMAGE'][0])){
			foreach($TMPtree['TEMP']['IMAGE'] as $r){
				$TMPXML[] = str_replace("F","",$r['ATTRIBUTES']['ID']);
			}
		} else {
			$TMPXML[] = str_replace("F","",$TMPtree['TEMP']['IMAGE']['ATTRIBUTES']['ID']);
		}
		echo '<fav id="'.$row_info['fav_id'].'" email="'.$row_info['fav_email'].'" date="'.$row_info['fav_date'].'" others="'.$row_info['fav_others'].'" favs="'.implode(".",$TMPXML).'">'.$row_info['fav_message'].'</fav>';
	}
} else {
	$query_info = "SELECT `photo_event_images`.* 
			FROM `photo_event_images` 
			WHERE `group_id` = '".$id."' 
				AND `image_active` = 'y' 
			ORDER BY `image_name` ASC, `image_large` ASC";
	$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
	$total_info = mysql_num_rows($info);
	if($total_info > 0){
		$LGroup = NULL;
		while($row_info = mysql_fetch_assoc($info)){
			$name = ($row_info['image_name'] == "") ? $row_info['image_large'] : $row_info['image_name'];
			
			//$data1 = array("id" => $row_info['image_id'], "width" => 195, "height" => 195);
			//$data2 = array("id" => $row_info['image_id'], "width" => 630, "height" => 630);
			//$data3 = array("id" => $row_info['image_id'], "width" => 960, "height" => 640);
				
			$folder = substr($row_info['image_folder'],0,-11);
			if($LGroup == NULL || $LGroup != $row_info['group_id']) { $c = 1; $p = 1; $LGroup = $row_info['group_id']; }
			echo '<image id="'.$row_info['image_id'].'" groupid="'.$row_info['group_id'].'" fav="'.((in_array($row_info['image_id'],$favsArry))?'y':'n').'">'.rawurlencode($name).'</image>';
		}
	}
}
echo '</images>';
echo '</data>';
mysql_free_result($info);
?>
