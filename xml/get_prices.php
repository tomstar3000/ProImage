<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define ("Allow Scripts", true);
//require_once($r_path.'control_panel/scripts/test_login.php');

$data = file_get_contents("php://input");

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

//$data = '<event number="Flowers" photographer="Chad"></event>';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
/*
Array(
    [EVENT] => Array(
		Array ( 
			[ATTRIBUTES] => Array (
				[NUMBER] => Timmy
			)
			[VALUE] => 
		}
	}
}
*/
$event_number = clean_variable($tree['EVENT']['ATTRIBUTES']['NUMBER'],true);
$client_number = clean_variable($tree['EVENT']['ATTRIBUTES']['PHOTOGRAPHER'],true);
$group_id = 0;
mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `photo_event_price`.`photo_price`, `photo_event_price`.`photo_color`, `photo_event_price`.`photo_blk_wht`, `photo_event_price`.`photo_sepia`, `photo_event`.`photo_to_lab`, `photo_event`.`photo_at_lab`, `photo_event`.`photo_at_photo` 
	FROM `cust_customers`
	INNER JOIN `photo_event` 
		ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
	INNER JOIN `photo_event_price` 
		ON `photo_event_price`.`photo_event_price_id` = `photo_event`.`event_price_id`
	WHERE `cust_handle` = '$client_number' 
		AND `event_num` = '$event_number'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$prices = explode(",",$row_get_info['photo_price']);

echo '<?xml version="1.0" encoding="iso-8859-1"?>
<prices>';
	foreach($prices as $k => $v){
		$price = explode(":",$v);
		$query_get_info = "SELECT `prod_name`, `prod_price`, `prod_desc` FROM `prod_products` WHERE `prod_id` = '$price[0]' AND `prod_use` = 'y'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$total_get_info = mysql_num_rows($get_info);
		if($total_get_info > 0){
			echo '	<price id="'.$price[0].'" name="'.$row_get_info['prod_name'].'" price="'.$price[1].'">'.$row_get_info['prod_desc'].'</price>';
		}
	}
echo '</prices>';

mysql_free_result($get_info);
?>