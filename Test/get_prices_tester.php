<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define ("Allow Scripts", true);

$date = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),(date("d")+1),date("Y")));

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

$event_number = "pisano";
$client_number = "ryanserpan";
$cust_email = "development@proimagesoftware.com";
$group_id = 0;
mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `photo_event_price`.`photo_price`, `photo_event_price`.`photo_color`, `photo_event_price`.`photo_blk_wht`, `photo_event_price`.`photo_sepia`, `photo_event`.`photo_to_lab`, `photo_event`.`photo_at_lab`, `photo_event`.`photo_at_photo` 
	FROM `cust_customers`
	INNER JOIN `photo_event` 
		ON `photo_event`.`cust_id` = `cust_customers`.`cust_id` 
	INNER JOIN `photo_event_price` 
		ON `photo_event_price`.`photo_event_price_id` = `photo_event`.`event_price_id`
	WHERE `cust_handle` = '$client_number' 
		AND `event_use` = 'y'
		AND `event_num` = '$event_number'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$prices = explode(",",$row_get_info['photo_price']);

echo '<?xml version="1.0" encoding="iso-8859-1"?>
<prices><list>';
	foreach($prices as $k => $v){
		$price = explode(":",$v);
		$query_get_info = "SELECT `prod_name`, `prod_price`, `prod_desc` FROM `prod_products` WHERE `prod_id` = '$price[0]' AND `prod_use` = 'y'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$total_get_info = mysql_num_rows($get_info);
		if($total_get_info > 0){
			echo '	<price id="'.$price[0].'" name="'.$row_get_info['prod_name'].'" price="'.$price[1].'">'.rawurlencode($row_get_info['prod_desc']).'</price>';
		}
	}
echo '</list>';
$discdate = date("Y-m-d");
$query_get_disc = "SELECT `prod_discount_codes`.*, COUNT(`orders_invoice_codes`.`disc_id`) AS `invoce_total`
	FROM `photo_event`
	INNER JOIN `cust_customers` 
		ON `photo_event`.`cust_id` = `cust_customers`.`cust_id`
	INNER JOIN `prod_discount_codes`
		ON `prod_discount_codes`.`cust_id` = `cust_customers`.`cust_id`
	LEFT JOIN `orders_invoice_codes`
		ON (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
			OR `orders_invoice_codes`.`disc_id` IS NULL)
	WHERE `cust_customers`.`cust_handle` = '$client_number'
		AND (`prod_discount_codes`.`disc_exp` = '0000-00-00 00:00:00' OR `prod_discount_codes`.`disc_exp` >= '".$discdate."')
		AND (`prod_discount_codes`.`disc_num_uses` = '0' OR `prod_discount_codes`.`disc_num_uses` > `prod_discount_codes`.`disc_num_used`)
		AND `prod_discount_codes`.`disc_use` = 'y'
		AND `prod_discount_codes`.`disc_type` != 'g'
	GROUP BY `prod_discount_codes`.`disc_id`
UNION 
	SELECT `prod_discount_codes`.*, COUNT(`orders_invoice_codes`.`disc_id`) AS `invoce_total`
	FROM `photo_event`
	INNER JOIN `cust_customers` 
		ON `photo_event`.`cust_id` = `cust_customers`.`cust_id`
	INNER JOIN `prod_discount_codes`
		ON `prod_discount_codes`.`cust_id` = `cust_customers`.`cust_id`
	LEFT JOIN `orders_invoice_codes`
		ON (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
			OR `orders_invoice_codes`.`disc_id` IS NULL)
	WHERE `cust_customers`.`cust_handle` = '$client_number'
		AND (`prod_discount_codes`.`disc_exp` = '0000-00-00 00:00:00' OR `prod_discount_codes`.`disc_exp` >= '".$discdate."')
		AND (`prod_discount_codes`.`disc_num_uses` = '0' OR `prod_discount_codes`.`disc_num_uses` > `prod_discount_codes`.`disc_num_used`)
		AND `prod_discount_codes`.`disc_use` = 'y'
		AND `prod_discount_codes`.`disc_type` = 'g'
		AND `prod_discount_codes`.`disc_email` = '$cust_email'
	GROUP BY `prod_discount_codes`.`disc_id`";
		
$get_disc = mysql_query($query_get_disc, $cp_connection) or die(mysql_error());

echo '<photographer>';
while($row_get_disc = mysql_fetch_assoc($get_disc)){
	if($row_get_disc['disc_counted'] == 0 || (($row_get_disc['disc_num_uses'] > 0 && $row_get_disc['disc_counted'] != 0 && $row_get_disc['disc_counted']<$row_get_disc['disc_num_uses']) || $row_get_disc['disc_num_uses'] == 0)){
		echo '<discount id="'.$row_get_disc['disc_id'].'" name="'.$row_get_disc['disc_name'].'" code="'.$row_get_disc['disc_code'].'" price="'.$row_get_disc['disc_price'].'" perc="'.$row_get_disc['disc_percent'].'" buy="'.$row_get_disc['disc_item'].'" get="'.$row_get_disc['disc_for'].'" multiple="'.$row_get_disc['disc_mult'].'" limit="'.$row_get_disc['disc_limit'].'" prodid="'.$row_get_disc['prod_id'].'" rollover="'.$row_get_disc['disc_roll_over'].'" rolltotal="'.$row_get_disc['disc_total'].'" image="'.(($row_get_disc['disc_image'] != "" ) ? 'images/discounts/'.$row_get_disc['disc_image']:'').'"></discount>';
	}
}
echo '</photographer>';

require_once $r_path.'scripts/fnct_holidays.php';

$Holidays = new CalcHoliday;
$BlackF = $Holidays->findHoliday('Black Friday',date("Y"));

$query_get_disc = "SELECT `prod_discount_codes`.*, '1'  AS `type`".
//, ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW())) AS `EndToDay`, 
//ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) AS `StartToDay`
	"FROM `photo_evnt_mrkt` 
	INNER JOIN `photo_event` 
		ON ( `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'{')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('}',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'[+]%')
				OR `photo_event`.`event_mrk_ids` LIKE CONCAT('%[+]',`photo_evnt_mrkt`.`event_mrk_id`,'{') )
	INNER JOIN `prod_discount_codes`
		ON (`prod_discount_codes`.`evnt_mrk_id`  = `photo_evnt_mrkt`.`event_mrk_id`
				AND 
					 (`photo_event`.`event_mrk_codes` LIKE CONCAT('}',`prod_discount_codes`.`disc_id`,'{')
				OR `photo_event`.`event_mrk_codes` LIKE CONCAT('}',`prod_discount_codes`.`disc_id`,'[+]%')
				OR `photo_event`.`event_mrk_codes` LIKE CONCAT('%[+]',`prod_discount_codes`.`disc_id`,'[+]%')
				OR `photo_event`.`event_mrk_codes` LIKE CONCAT('%[+]',`prod_discount_codes`.`disc_id`,'{') ))
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	WHERE `photo_evnt_mrkt`.`event_mrk_use` = 'y' 
		AND `event_use` = 'y'
		AND `prod_discount_codes`.`disc_type` != 'g'
		AND (
					(
						(`photo_evnt_mrkt`.`event_mrk_days_early_purchase` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_early_purchase` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_after_start` != '0'
							AND `photo_evnt_mrkt`.`event_mrk_days_after_start` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` = '0'
							AND NOW() >= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_after_start` != '0'
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` != '0'
							AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()) + `photo_evnt_mrkt`.`event_mrk_days_after_start`) <= `photo_evnt_mrkt`.`event_mrk_days_cart_expire`
							AND NOW() >= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_before_end` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_before_end` >= ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_before_end_2` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_before_end_2` >= ABS(TO_DAYS(`photo_event`.`event_end`) - TO_DAYS(NOW()))
						)
					OR (`photo_evnt_mrkt`.`event_mrk_days_cart_expire` != '0' 
							AND `photo_evnt_mrkt`.`event_mrk_days_cart_expire` >= ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()))
							AND NOW() <= `photo_event`.`event_date`
						)
					OR (`photo_evnt_mrkt`.`event_mrk_starts` = 'y'
							AND ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) = '0'
							AND (`photo_evnt_mrkt`.`event_mrk_days_after_start` = '0'
								OR ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW())) <= `photo_evnt_mrkt`.`event_mrk_days_after_start`)
							AND (`photo_evnt_mrkt`.`event_mrk_days_cart_expire` = '0'
								OR ABS(TO_DAYS(`photo_event`.`event_date`) - TO_DAYS(NOW()) + `photo_evnt_mrkt`.`event_mrk_days_after_start`) <= `photo_evnt_mrkt`.`event_mrk_days_cart_expire`)
						)
					)
					".((date("Y-m-d") >= $BlackF)?	"OR `photo_evnt_mrkt`.`event_mrk_black_friday` = 'y'":"")."
					".((date("md") >= '1210')?			"OR `photo_evnt_mrkt`.`evnt_mrk_dec_10` = 'y'":"")."
					".((date("md") >= "1101")?			"OR `photo_evnt_mrkt`.`event_mrk_re_release` = 'y'":"")."
				)
		AND `cust_customers`.`cust_handle` = '".$client_number."'
		AND `photo_event`.`event_num` = '".$event_number."'
UNION 	
	SELECT `prod_discount_codes`.*, '2' AS `type`
	FROM `photo_event_disc`
	INNER JOIN `prod_discount_codes`
		ON `prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
	INNER JOIN `photo_event`
		ON `photo_event`.`event_id` = `photo_event_disc`.`event_id`
	INNER JOIN `cust_customers` 
		ON `photo_event`.`cust_id` = `cust_customers`.`cust_id`
	WHERE `cust_customers`.`cust_handle` = '".$client_number."'
		AND `photo_event`.`event_num` = '".$event_number."'
		AND `photo_event`.`event_use` = 'y'
		AND `prod_discount_codes`.`disc_type` != 'g'
		AND (`prod_discount_codes`.`disc_exp` = '0000-00-00 00:00:00' OR `prod_discount_codes`.`disc_exp` >= '".$discdate."')
		AND (`prod_discount_codes`.`disc_num_uses` = '0' OR `prod_discount_codes`.`disc_num_uses` > `prod_discount_codes`.`disc_num_used`)
		AND `prod_discount_codes`.`disc_use` = 'y'";
$get_disc = mysql_query($query_get_disc, $cp_connection) or die(mysql_error());
$total_get_disc = mysql_num_rows($get_disc);

echo '<proimage total="'.$total_get_disc.'">';
while($row_get_disc = mysql_fetch_assoc($get_disc)){
	if($row_get_disc['type'] == '1' && intval($row_get_disc['disc_num_uses']) > 0 && intval($row_get_disc['invoce_total']) >= intval($row_get_disc['disc_num_uses'])){
		// Return Nothing	
	} else {
		echo '<discount id="'.$row_get_disc['disc_id'].'" name="'.$row_get_disc['disc_name'].'" code="'.$row_get_disc['disc_code'].'" price="'.$row_get_disc['disc_price'].'" perc="'.$row_get_disc['disc_percent'].'" buy="'.$row_get_disc['disc_item'].'" get="'.$row_get_disc['disc_for'].'" multiple="'.$row_get_disc['disc_mult'].'" limit="'.$row_get_disc['disc_limit'].'" prodid="'.$row_get_disc['prod_id'].'" rollover="'.$row_get_disc['disc_roll_over'].'" rolltotal="'.$row_get_disc['disc_total'].'" image="'.(($row_get_disc['disc_image'] != "" ) ? 'images/discounts/'.$row_get_disc['disc_image']:'').'" notes="'.$row_get_disc['disc_note'].'"></discount>';
	}
}
echo '</proimage>';
echo '</prices>';
?>
