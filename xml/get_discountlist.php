<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');
$data = file_get_contents("php://input");
$date = date("Y-m-d H:i:s");

//$data = '<event photographer="flyinghorse" code="dcw1"></event>';
$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$photographer = $tree['EVENT']['ATTRIBUTES']['PHOTOGRAPHER'];
$code = $tree['EVENT']['ATTRIBUTES']['CODE'];

echo '<?xml version="1.0" encoding="iso-8859-1"?>';

$query_get_disc = "SELECT `prod_discount_codes`.*, SUM(`orders_invoice_codes`.`disc_total`) AS `disc_total`, COUNT(`orders_invoice_codes`.`invoice_id`) AS `disc_counted` 
	FROM `prod_discount_codes` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `prod_discount_codes`.`cust_id`
	LEFT JOIN `orders_invoice_codes`
		ON  (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
			OR `orders_invoice_codes`.`disc_id` IS NULL)
	WHERE `cust_handle` = '$photographer'
		AND (`disc_exp` = '0000-00-00 00:00:00'
			OR `disc_exp` >= '$date')
		AND (`disc_num_uses` = '0' OR `disc_num_uses` > `disc_num_used`)
		AND `disc_use` = 'y'
	GROUP BY `prod_discount_codes`.`disc_id`";
$get_disc = mysql_query($query_get_disc, $cp_connection) or die(mysql_error());

echo '<photographer>';
while($row_get_disc = mysql_fetch_assoc($get_disc)){
	if($row_get_disc['disc_counted'] == 0 || (($row_get_disc['disc_num_uses'] > 0 && $row_get_disc['disc_counted'] != 0 && $row_get_disc['disc_counted']<$row_get_disc['disc_num_uses']) || $row_get_disc['disc_num_uses'] == 0)){
		echo '<discount id="'.$row_get_disc['disc_id'].'" name="'.$row_get_disc['disc_name'].'" code="'.$row_get_disc['disc_code'].'" price="'.$row_get_disc['disc_price'].'" perc="'.$row_get_disc['disc_percent'].'" buy="'.$row_get_disc['disc_item'].'" get="'.$row_get_disc['disc_for'].'" multiple="'.$row_get_disc['disc_mult'].'" limit="'.$row_get_disc['disc_limit'].'" prodid="'.$row_get_disc['prod_id'].'" rollover="'.$row_get_disc['disc_roll_over'].'" rolltotal="'.$row_get_disc['disc_total'].'" image="'.(($row_get_disc['disc_image'] != "" ) ? '/images/discounts/'.$row_get_disc['disc_image']:'').'"></discount>';
	}
}
echo '</photographer>';

mysql_free_result($get_disc);
unset($query_get_disc);
unset($row_get_disc);

$query_get_disc = "SELECT `prod_discount_codes`.*
	FROM `photo_event_disc`
	INNER JOIN `prod_discount_codes`
		ON `prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
	INNER JOIN `photo_event`
		ON `photo_event`.`event_id` = `photo_event_disc`.`event_id`
	INNER JOIN `cust_customers` 
		ON `photo_event`.`cust_id` = `cust_customers`.`cust_id`
	WHERE `cust_customers`.`cust_handle` = '".$photographer."'
		AND `photo_event`.`event_num` = '".$code."'
		AND (`prod_discount_codes`.`disc_exp` = '0000-00-00 00:00:00' OR `prod_discount_codes`.`disc_exp` >= '".$date."')
		AND (`prod_discount_codes`.`disc_num_uses` = '0' OR `prod_discount_codes`.`disc_num_uses` > `prod_discount_codes`.`disc_num_used`)
		AND `prod_discount_codes`.`disc_use` = 'y'";
$get_disc = mysql_query($query_get_disc, $cp_connection) or die(mysql_error());
$total_get_disc = mysql_num_rows($get_disc);

echo '<proimage total="'.$total_get_disc.'">';
while($row_get_disc = mysql_fetch_assoc($get_disc)){
	echo '<discount id="'.$row_get_disc['disc_id'].'" name="'.$row_get_disc['disc_name'].'" code="'.$row_get_disc['disc_code'].'" price="'.$row_get_disc['disc_price'].'" perc="'.$row_get_disc['disc_percent'].'" buy="'.$row_get_disc['disc_item'].'" get="'.$row_get_disc['disc_for'].'" multiple="'.$row_get_disc['disc_mult'].'" limit="'.$row_get_disc['disc_limit'].'" prodid="'.$row_get_disc['prod_id'].'" rollover="'.$row_get_disc['disc_roll_over'].'" rolltotal="'.$row_get_disc['disc_total'].'" image="'.(($row_get_disc['disc_image'] != "" ) ? '/images/discounts/'.$row_get_disc['disc_image']:'').'" notes="'.$row_get_disc['disc_note'].'"></discount>';
}
echo '</proimage>';
?>
