<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security_2.php';
set_time_limit(0);
ini_set('max_execution_time',(60*60));
$Date = date("Y-m-d H:i:s");
$StartDate = date("m/d/Y", mktime(0,0,0,date("m"),1,date("Y")));
$EndDate = date("m/d/Y", mktime(0,0,0,(date("m")+1),0,date("Y")));
$Start = get_variable($_POST['From'], $StartDate, true); //
$End = get_variable($_POST['To'], $EndDate, true);
$Start = date("Y-m-d H:i:s", mktime(0,0,0,substr($Start,0,2),substr($Start,3,2),substr($Start,6,4)));
$End = date("Y-m-d H:i:s", mktime(0,0,-1,substr($End,0,2),(substr($End,3,2)+1),substr($End,6,4)));
$addthis = "";
$query_get_info = "SELECT `cust_bill_state`, `cust_bill_zip`, `invoice_total`, `invoice_tax`, `invoice_ship`, `invoice_paid_date`, `ship_comp_name` 
	FROM `cust_billing` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`cust_bill_id` = `cust_billing`.`cust_bill_id` 
	INNER JOIN `billship_shipping_speeds` 
		ON `billship_shipping_speeds`.`ship_speed_id` = `orders_invoice`.`ship_speed_id` 
	INNER JOIN `billship_shipping_companies` 
		ON `billship_shipping_companies`.`ship_comp_id` = `billship_shipping_speeds`.`ship_comp_id` 
	WHERE `orders_invoice`.`cust_id` > '0' 
		AND `invoice_online` = 'n' 
		AND `orders_invoice`.`invoice_paid_date` >= '$Start' 
		AND `orders_invoice`.`invoice_paid_date` <= '$End' 
	ORDER BY `invoice_paid_date`,`cust_bill_state`,`cust_bill_state` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$total_info = mysql_num_rows($get_info);

$sorted_array = array();

if($total_info>0){
	while($row_get_info = mysql_fetch_assoc($get_info)){
		//$date = date("Y F", mktime(0,0,0,substr($row_get_info['invoice_paid_date'],5,2),1,substr($row_get_info['invoice_paid_date'],0,4)));
		$date = $Start.".".$End;
		$temp_grand = ($row_get_info['invoice_total']+$row_get_info['invoice_ship']);
		$tempstate = $row_get_info['cust_bill_state'];
		
		$query_get_taxes = "SELECT `state_name` FROM `a_states` WHERE `state_short` = '$tempstate'";
		$get_taxes = mysql_query($query_get_taxes, $cp_connection) or die(mysql_error());
		$row_get_taxes = mysql_fetch_assoc($get_taxes);
		
		$name = $row_get_taxes['state_name'];
		
		$query_get_taxes = "SELECT `billship_tax_states`.* FROM `billship_tax_states`  WHERE `tax_state` = '$tempstate'";
		$get_taxes = mysql_query($query_get_taxes, $cp_connection) or die(mysql_error());
		$row_get_taxes = mysql_fetch_assoc($get_taxes);
		$total_get_taxes = mysql_num_rows($get_taxes);
		
		$temp_state = ($total_get_taxes == 0) ? 0 : ($temp_grand*($row_get_taxes['tax_percent']/100));
		$tempzip = $row_get_info['cust_bill_zip'];
		
		$query_get_taxes = "SELECT * FROM `billship_tax_county` WHERE `tax_count_zip` = '$tempzip'";
		$get_taxes = mysql_query($query_get_taxes, $cp_connection) or die(mysql_error());
		$row_get_taxes = mysql_fetch_assoc($get_taxes);
		$total_get_taxes = mysql_num_rows($get_taxes);
		if($name != ""){
			if($total_get_taxes != 0){
				if(intval($row_get_info['invoice_tax']) > 0){
					$temp_city = $row_get_taxes['tax_count_city'];
					$temp_fd = $row_get_taxes['tax_count_fd'];
					$temp_cd = $row_get_taxes['tax_count_cd'];
					$temp_other = $row_get_taxes['tax_count_other'];
					$temp_county = intval($row_get_taxes['tax_count_percent'])-intval($temp_city)-intval($temp_fd)-intval($temp_cd)-intval($temp_other);
					$temp_city = $temp_grand*($temp_city/100);
					$temp_fd = $temp_grand*($temp_fd/100);
					$temp_cd = $temp_grand*($temp_cd/100);
					$temp_other = $temp_grand*($temp_other/100);
					$temp_county = $temp_grand*($temp_county/100);
					$sorted_array[$date]['States'][$name]['Taxes']['STATE'] += $temp_state;
					$sorted_array[$date]['States'][$name]['Taxes']['COUNT'] += $temp_county;
					$sorted_array[$date]['States'][$name]['Taxes']['CITY'] += $temp_city;
					$sorted_array[$date]['States'][$name]['Taxes']['CD'] += $temp_cd;
					$sorted_array[$date]['States'][$name]['Taxes']['FD'] += $temp_fd;
					$sorted_array[$date]['States'][$name]['Taxes']['OTHER'] += $temp_other;
					$sorted_array[$date]['States'][$name]['Taxes']['TOTAL'] += $row_get_info['invoice_tax'];
				}
			} 
			$sorted_array[$date]['Totals']['Taxes']['STATE'] += $temp_state;
			$sorted_array[$date]['Totals']['Taxes']['COUNT'] += $temp_county;
			$sorted_array[$date]['Totals']['Taxes']['CITY'] += $temp_city;
			$sorted_array[$date]['Totals']['Taxes']['CD'] += $temp_cd;
			$sorted_array[$date]['Totals']['Taxes']['FD'] += $temp_fd;
			$sorted_array[$date]['Totals']['Taxes']['OTHER'] += $temp_other;
			$sorted_array[$date]['Totals']['Taxes']['TOTAL'] += $row_get_info['invoice_tax'];
			$sorted_array[$date]['Totals']['Ship'][$row_get_info['ship_comp_name']] += $row_get_info['invoice_ship'];
			$sorted_array[$date]['Totals']['Ship']['Total'] += $row_get_info['invoice_ship'];
			$sorted_array[$date]['States'][$name]['Ship'][$row_get_info['ship_comp_name']] += $row_get_info['invoice_ship']; 
			$sorted_array[$date]['States'][$name]['Ship']['Total'] += $row_get_info['invoice_ship']; 
		}
	}
}
?>
