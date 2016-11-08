<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
$RealPath = $r_path; $r_path = '../';
define ("PhotoExpress Pro", true);
require_once $r_path.'config.php';
require_once $r_path.'../Connections/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$ID = (isset($_GET['id'])) ? clean_variable($_GET['id'],true) : '';
$DATA = isset($_GET['data']) ? unserialize(rawurldecode($_GET['data'])) : 0;
if(is_array($DATA)){
	foreach($DATA as $k => $v) $DATA[$k] = trim(clean_variable($v,true));
	
	$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$updInfo->mysql("INSERT INTO `prod_discount_codes` (`cust_id`, `disc_name`,`disc_code`,`disc_price`,`disc_roll_over`,`disc_email`,`disc_type`) VALUES ('$ID','".$DATA['Gift_Name']."','".$DATA['Gift_Code']."','".preg_replace("/[^0-9.]/","",$DATA['Gift_Amount'])."','y','".$DATA['Gift_Email']."','g');");
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<data>';
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `prod_discount_codes`.*, SUM(`orders_invoice_codes`.`disc_total`) AS `disc_total`, `photo_event_disc`.`event_disc_id`
					FROM `prod_discount_codes`
					LEFT JOIN `photo_event_disc` ON (`photo_event_disc`.`disc_id` = `prod_discount_codes`.`disc_id`
							OR `photo_event_disc`.`disc_id` IS NULL)
					LEFT JOIN `orders_invoice_codes`
						ON (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
							OR `orders_invoice_codes`.`disc_id` IS NULL)					
					WHERE `prod_discount_codes`.`prod_id` = '0' 
						AND `prod_discount_codes`.`cust_id` = '$ID' 
						AND `prod_discount_codes`.`disc_use` = 'y' 
						AND `prod_discount_codes`.`disc_type` = 'g' 
						AND (	`disc_total` IS NULL || `disc_total` < `prod_discount_codes`.`disc_price` )
						AND `photo_event_disc`.`event_disc_id` IS NULL
					GROUP BY `prod_discount_codes`.`disc_id` 
					ORDER BY `disc_name` ASC;"); 
foreach($getInfo->Rows() as $r){ 
	echo '<item id="'.$r['disc_id'].'" name="'.((strlen($r['disc_name'])>15)?substr($r['disc_name'],0,15)."...":$r['disc_name']).'" price="$'.number_format($r['disc_price'],2,".",",").'" redem="$'.number_format($r['disc_total'],2,".",",").'" code="'.$r['disc_code'].'" email="'.((strlen($r['disc_email'])>15)?substr($r['disc_email'],0,15)."...":$r['disc_email']).'"></item>';
}
echo '</data>';
?>
