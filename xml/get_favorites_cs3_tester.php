<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";

define ("Allow Scripts", true);
//$data = file_get_contents("php://input");
$data = $GLOBALS['HTTP_RAW_POST_DATA'];

require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

$data = file_get_contents("php://input");
$data = '<event number="Lustig" photographer="karinaheneghanphotographics" email="chad.serpan@aevium.com" />';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$code = $tree['EVENT']['ATTRIBUTES']['NUMBER'];
$photo = $tree['EVENT']['ATTRIBUTES']['PHOTOGRAPHER'];

mysql_select_db($database_cp_connection, $cp_connection);
$query_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`
	FROM `photo_cust_favories`
	LEFT JOIN `photo_cust_favories_message`
		ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
			OR `photo_cust_favories_message`.`fav_id` IS NULL)
	WHERE `fav_code` = '".$code.$photo."'
		AND `fav_occurance` = '2'
	ORDER BY `photo_cust_favories_message`.`fav_date` DESC";
$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo '<event><favs>';
while($row_info = mysql_fetch_assoc($info)){
	echo '<fav id="'.$row_info['fav_id'].'" email="'.$row_info['fav_email'].'" date="'.$row_info['fav_date'].'" others="'.$row_info['fav_others'].'" favs="'.$row_info['fav_xml'].'">'.$row_info['fav_message'].'</fav>';
}
$query_info = "SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`
	FROM `photo_cust_favories`
	LEFT JOIN `photo_cust_favories_message`
		ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
			OR `photo_cust_favories_message`.`fav_id` IS NULL)
	WHERE `fav_code` = '".$code.$photo."'
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
echo '</favs></event>'; ?>
