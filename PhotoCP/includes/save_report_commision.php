<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security_2.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_phone.php';
require_once $r_path.'scripts/fnct_format_date.php';

set_time_limit(0);
ini_set('max_execution_time',(60*60));

$Date = date("Y-m-d H:i:s");
$StartDate = date("m/d/Y", mktime(0,0,0,date("m"),1,date("Y")));
$EndDate = date("m/d/Y", mktime(0,0,0,(date("m")+1),0,date("Y")));
$Start = get_variable($_POST['From'], $StartDate, true); //
$End = get_variable($_POST['To'], $EndDate, true);
$Start = date("Y-m-d H:i:s", mktime(0,0,0,substr($Start,0,2),substr($Start,3,2),substr($Start,6,4)));
$End = date("Y-m-d H:i:s", mktime(0,0,-1,substr($End,0,2),(substr($End,3,2)+1),substr($End,6,4)));

$Events = (isset($_POST['Events']))? intval(clean_variable($_POST['Events'], true)) : 
					((isset($path[2])) ? intval($path[2]) : 0);
$addthis = "";
$addthis2 = "";
$innerthis = "";
$selectthis = "";
$merchaddthis = "";
$merchaddthis2 = "";

$addthis = " AND `cust_customers`.`cust_id` = '$CustId'";
$merchaddthis = " AND `cust_id` = '$CustId'";
if($Events > 0){
	$addthis2 = " AND `photo_event_group`.`event_id` = '".$Events."'";
	$innerthis = ' INNER JOIN `photo_event_group` ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` ';
	$selectthis = ', `photo_event_group`.`event_id`';
	$merchaddthis2 = " AND `event_id` = '".$Events."' ";
}
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("(SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_add_2`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_cell`, `cust_customers`.`cust_fax`, `cust_customers`.`cust_work`, `cust_customers`.`cust_ext`, `cust_customers`.`cust_800`, `cust_customers`.`cust_email`, `cust_customers`.`cust_email_2`, `cust_customers`.`cust_website`, `cust_customers`.`cust_rev`, 
									
				`orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_date`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_paid_date`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, `orders_invoice`.`invoice_grand`, `profit_sharing`.`invoice_rev`, `orders_invoice_codes`.`cust_code`, `orders_invoice_codes`.`prod_id`, `orders_invoice_codes`.`item_count`, `orders_invoice_codes`.`disc_total` ".$selectthis." 
	FROM `orders_invoice`
	LEFT JOIN `orders_invoice_codes`
			ON (`orders_invoice_codes`.`invoice_id` = `orders_invoice`.`invoice_id`
				OR `orders_invoice_codes`.`invoice_id` IS NULL) 
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		".$innerthis." 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` 
	LEFT JOIN `orders_invoice` AS `profit_sharing` 
		ON (`profit_sharing`.`cust_id` = `cust_customers`.`cust_id` OR `profit_sharing`.`cust_id` IS NULL)
	WHERE `orders_invoice`.`cust_id` > '0' 
		AND `orders_invoice`.`invoice_paid_date` >= '$Start' 
		AND `orders_invoice`.`invoice_paid_date` <= '$End' ".$addthis.$addthis2." 
	GROUP BY `orders_invoice`.`invoice_id` )
UNION
	(SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname`, `cust_customers`.`cust_add`, `cust_customers`.`cust_suite_apt`, `cust_customers`.`cust_add_2`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_cell`, `cust_customers`.`cust_fax`, `cust_customers`.`cust_work`, `cust_customers`.`cust_ext`, `cust_customers`.`cust_800`, `cust_customers`.`cust_email`, `cust_customers`.`cust_email_2`, `cust_customers`.`cust_website`, `cust_customers`.`cust_rev`, 
	 
	 `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_date`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_paid_date`, `orders_invoice`.`invoice_total`, `orders_invoice`.`invoice_disc`, `orders_invoice`.`invoice_tax`, `orders_invoice`.`invoice_ship`, `orders_invoice`.`invoice_grand`, `profit_sharing`.`invoice_rev`, `orders_invoice_codes`.`cust_code`, `orders_invoice_codes`.`prod_id`, `orders_invoice_codes`.`item_count`, `orders_invoice_codes`.`disc_total` ".$selectthis." 
	FROM `orders_invoice` 
	LEFT JOIN `orders_invoice_codes`
			ON (`orders_invoice_codes`.`invoice_id` = `orders_invoice`.`invoice_id`
				OR `orders_invoice_codes`.`invoice_id` IS NULL) 
	INNER JOIN `orders_invoice_border` 
		ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		".$innerthis." 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` 
	LEFT JOIN `orders_invoice` AS `profit_sharing` 
		ON (`profit_sharing`.`cust_id` = `cust_customers`.`cust_id` OR `profit_sharing`.`cust_id` IS NULL) 
	WHERE `orders_invoice`.`cust_id` > '0' 
		AND `orders_invoice`.`invoice_paid_date` >= '$Start' 
		AND `orders_invoice`.`invoice_paid_date` <= '$End' ".$addthis.$addthis2." 
	GROUP BY `orders_invoice`.`invoice_id` )
	
	ORDER BY `cust_fname`, `cust_lname`, `invoice_paid_date` ASC;");
	
$sorted_array = array();
$LastCustId = 0;
foreach($getInfo->Rows() as $r){
	$sorted_array[$r['cust_id']]['Info']['Name'] = $r['cust_fname'].' '.$r['cust_lname'];
	$sorted_array[$r['cust_id']]['Info']['CName'] = $r['cust_cname'];
	$sorted_array[$r['cust_id']]['Info']['Add'] = $r['cust_add'];
	$sorted_array[$r['cust_id']]['Info']['Suite'] = $r['cust_suite_apt'];
	$sorted_array[$r['cust_id']]['Info']['Add2'] = $r['cust_add_2'];
	$sorted_array[$r['cust_id']]['Info']['City'] = $r['cust_city'];
	$sorted_array[$r['cust_id']]['Info']['State'] = $r['cust_state'];
	$sorted_array[$r['cust_id']]['Info']['Zip'] = $r['cust_zip'];
	$sorted_array[$r['cust_id']]['Info']['Phone'] = $r['cust_phone'];
	$sorted_array[$r['cust_id']]['Info']['Cell'] = $r['cust_cell'];
	$sorted_array[$r['cust_id']]['Info']['Fax'] = $r['cust_fax'];
	$sorted_array[$r['cust_id']]['Info']['Work'] = $r['cust_work'];
	$sorted_array[$r['cust_id']]['Info']['Ext'] = $r['cust_ext'];
	$sorted_array[$r['cust_id']]['Info']['800'] = $r['cust_800'];
	$sorted_array[$r['cust_id']]['Info']['Email'] = $r['cust_email'];
	$sorted_array[$r['cust_id']]['Info']['Email2'] = $r['cust_email_2'];
	$sorted_array[$r['cust_id']]['Info']['Website'] = $r['cust_website'];
	$sorted_array[$r['cust_id']]['Totals']['Rev'] = ($r['cust_rev'] == 0) ? 10 : $r['cust_rev'];
	$sorted_array[$r['cust_id']]['Inv'] = $r['invoice_id'];
	$sorted_array[$r['cust_id']]['Paid'] = $r['invoice_paid_date'];
	
	$temp_id = $r['invoice_id'];
	//$date = date("Y-m",mktime(0,0,0,substr($r['invoice_paid_date'],5,2),1,substr($r['invoice_paid_date'],0,4)));
	$date = $Start.".".$End;
	
	$getItems = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getItems->mysql("(SELECT `photo_event_images`.`image_name`, `photo_event_images`.`image_tiny`, `photo_event_images`.`image_id`, `orders_invoice_photo`.`invoice_image_size_id`, `orders_invoice_photo`.`invoice_image_asis`, `orders_invoice_photo`.`invoice_image_sepia`, `orders_invoice_photo`.`invoice_image_bw`, `orders_invoice_photo`.`invoice_image_cost`, `orders_invoice_photo`.`invoice_image_price`, `prod_products`.`prod_name`, `prod_products`.`prod_id`, `prod_products`.`prod_price`
		FROM `orders_invoice_photo` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
		INNER JOIN `prod_products` 
			ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` 
		WHERE `orders_invoice_photo`.`invoice_id` = '$temp_id' )
	UNION
		(SELECT `photo_event_images`.`image_name`, `photo_event_images`.`image_tiny`, `photo_event_images`.`image_id`, `orders_invoice_border`.`invoice_image_size_id`, `orders_invoice_border`.`invoice_image_asis`, `orders_invoice_border`.`invoice_image_sepia`, `orders_invoice_border`.`invoice_image_bw`, `orders_invoice_border`.`invoice_image_cost`, `orders_invoice_border`.`invoice_image_price`, `prod_products`.`prod_name`, `prod_products`.`prod_id`, `prod_products`.`prod_price`
		FROM `orders_invoice_border` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		INNER JOIN `prod_products` 
			ON `prod_products`.`prod_id` = `orders_invoice_border`.`invoice_image_size_id` 
		WHERE `orders_invoice_border`.`invoice_id` = '$temp_id')
		ORDER BY `image_name` ASC;");
	$ItemCost = 0; $ItemDisc = 0;
	$DiscStored = false;
	foreach($getItems-> Rows() as $rItems){
		$key = $rItems['prod_id'].str_replace(".","",$rItems['invoice_image_price']);
		$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['NAME'] = $rItems['prod_name'];
		$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['PRICE'] = $rItems['invoice_image_price'];
		$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['COST'] = $rItems['invoice_image_cost'];
		$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Total'] += $rItems['invoice_image_asis']+$rItems['invoice_image_bw']+$rItems['invoice_image_sepia'];
		
		if($rItems['prod_id'] == $r['prod_id'] && $r['prod_id'] != 0 && $DiscStored == false){ $DiscStored = true;
			$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'] += $r['item_count'];
			$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Price'] += $r['disc_total'];
		} else {
			$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'] += 0;
			$sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Price'] += 0;
		}
	}
	unset($DiscStored);
	$ItemCost += $sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'];
	$ItemDisc += $sorted_array[$r['cust_id']]['Dates'][$date]['Items'][$key]['Disc']['Total'];
	
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Total'] += $r['invoice_total'];
	if($r['cust_code'] == 'y'){
		$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Disc'] += $r['invoice_disc'];
	} else if($r['prod_id'] == 0){
			$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Disc2'] += $r['invoice_disc'];
	}
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Tax'] += $r['invoice_tax'];
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Ship'] += $r['invoice_ship'];
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Grand'] += $r['invoice_grand'];
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Due'] = 0;
	
	$getRev = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getRev->mysql("SELECT `invoice_rev`, `invoice_date`, `prod_id` FROM `orders_invoice`
								 INNER JOIN `orders_invoice_prod` ON (`orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id`)
								 WHERE `cust_id` = '".$r['cust_id']."' AND `invoice_rev` > 0 ORDER BY `invoice_date` ASC;");
		
	if($LastCustId != $r['cust_id']) { $lastRev = 0; $RevKey=-1; $newRev=false; $LastCustId = $r['cust_id']; }
		
	foreach($getRev->Rows() as $v){
		$RevDate = date("U", mktime(substr($v['invoice_date'],11,2), substr($v['invoice_date'],14,2), substr($v['invoice_date'],17,2), substr($v['invoice_date'],5,2), substr($v['invoice_date'],8,2), substr($v['invoice_date'],0,4))); 
		
		$InvDate = date("U", mktime(substr($r['invoice_date'],11,2), substr($r['invoice_date'],14,2), substr($r['invoice_date'],17,2), substr($r['invoice_date'],5,2), substr($r['invoice_date'],8,2), substr($r['invoice_date'],0,4)));
		
		if(intval($InvDate) >= intval($RevDate)){ $updRev = intval($v['invoice_rev']); $revDate = $v['invoice_date']; $revId = $v['prod_id']; }
	}
	if($updRev != $lastRev) { $newRev = true; $RevKey++; $lastRev = $updRev;}

	$ProfitShare = $r['invoice_total'];
	//$ProfitShare -= ($ItemCost*$r['item_count']*$ItemDisc);
	$ProfitShare -= $r['invoice_disc'];
	$ProfitShare *= ($lastRev/100);
	
	if($newRev == true){ $sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] = 0;
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['ID'] = $revId;
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['Date'] = $revDate; $newRev = false; }
	$sorted_array[$r['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] += $ProfitShare;	
	$sorted_array[$r['cust_id']]['Totals']['Rev'] = $lastRev;
}

$testMerch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$testMerch->mysql("SELECT `cust_id` FROM `photo_invoices` WHERE `chrg_date` >= '$Start' AND `chrg_date` <= '$End' AND `process` = 'y' AND `fail` = 'n'".$merchaddthis.$merchaddthis2." GROUP BY `cust_id`;");

$LastCustId = 0;
$LastCustId2 = 0;

foreach($testMerch -> Rows() as $record){
	$date = $Start.".".$End;
	if(count($sorted_array[$record['cust_id']]) == 0){
		$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getInfo->mysql("SELECT `cust_customers`.*, `profit_sharing`.`invoice_id`, `profit_sharing`.`invoice_paid_date` FROM `cust_customers` 					
		LEFT JOIN `orders_invoice` AS `profit_sharing` 
			ON (`profit_sharing`.`cust_id` = `cust_customers`.`cust_id` OR `profit_sharing`.`cust_id` IS NULL)
		WHERE `cust_customers`.`cust_id` = '".$record['cust_id']."'; ");
		$getInfo = $getInfo->Rows();
		$sorted_array[$record['cust_id']]['Info']['Name'] = $getInfo[0]['cust_fname'].' '.$getInfo[0]['cust_lname'];
		$sorted_array[$record['cust_id']]['Info']['CName'] = $getInfo[0]['cust_cname'];
		$sorted_array[$record['cust_id']]['Info']['Add'] = $getInfo[0]['cust_add'];
		$sorted_array[$record['cust_id']]['Info']['Suite'] = $getInfo[0]['cust_suite_apt'];
		$sorted_array[$record['cust_id']]['Info']['Add2'] = $getInfo[0]['cust_add_2'];
		$sorted_array[$record['cust_id']]['Info']['City'] = $getInfo[0]['cust_city'];
		$sorted_array[$record['cust_id']]['Info']['State'] = $getInfo[0]['cust_state'];
		$sorted_array[$record['cust_id']]['Info']['Zip'] = $getInfo[0]['cust_zip'];
		$sorted_array[$record['cust_id']]['Info']['Phone'] = $getInfo[0]['cust_phone'];
		$sorted_array[$record['cust_id']]['Info']['Cell'] = $getInfo[0]['cust_cell'];
		$sorted_array[$record['cust_id']]['Info']['Fax'] = $getInfo[0]['cust_fax'];
		$sorted_array[$record['cust_id']]['Info']['Work'] = $getInfo[0]['cust_work'];
		$sorted_array[$record['cust_id']]['Info']['Ext'] = $getInfo[0]['cust_ext'];
		$sorted_array[$record['cust_id']]['Info']['800'] = $getInfo[0]['cust_800'];
		$sorted_array[$record['cust_id']]['Info']['Email'] = $getInfo[0]['cust_email'];
		$sorted_array[$record['cust_id']]['Info']['Email2'] = $getInfo[0]['cust_email_2'];
		$sorted_array[$record['cust_id']]['Info']['Website'] = $getInfo[0]['website'];
		$sorted_array[$record['cust_id']]['Totals']['Rev'] = ($getInfo[0]['cust_rev'] == 0) ? 10 : $getInfo[0]['cust_rev'];
		$sorted_array[$record['cust_id']]['Inv'] = $getInfo[0]['invoice_id'];
		$sorted_array[$record['cust_id']]['Paid'] = $getInfo[0]['invoice_paid_date'];
	
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Total'] = 0;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Disc'] = 0;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Disc2'] = 0;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Tax'] = 0;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Ship'] = 0;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Grand'] = 0;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Due'] = 0;
		
		$getRev = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getRev->mysql("SELECT `invoice_rev`, `invoice_date`, `prod_id` FROM `orders_invoice`
									 INNER JOIN `orders_invoice_prod` ON (`orders_invoice_prod`.`invoice_id` = `orders_invoice`.`invoice_id`)
									 WHERE `cust_id` = '".$record['cust_id']."' AND `invoice_rev` > 0 ORDER BY `invoice_date` ASC;");
		
		if($LastCustId != $record['cust_id']) { $lastRev = 0; $RevKey=-1; $newRev=false; $LastCustId = $record['cust_id']; }
			
		foreach($getRev->Rows() as $v){
			$RevDate = date("U", mktime(substr($v['invoice_date'],11,2), substr($v['invoice_date'],14,2), substr($v['invoice_date'],17,2), substr($v['invoice_date'],5,2), substr($v['invoice_date'],8,2), substr($v['invoice_date'],0,4))); 
			$InvDate = date("U");
			if(intval($InvDate) >= intval($RevDate)){ $updRev = intval($v['invoice_rev']); $revDate = date("Y-m-d H:i:s"); $revId = $v['prod_id']; }
		}
		if($updRev != $lastRev) { $newRev = true; $RevKey++; $lastRev = $updRev;}
	
		$ProfitShare = $r['invoice_total'];
		$ProfitShare -= ($ItemCost*$r['item_count']*$ItemDisc);
		$ProfitShare -= $r['invoice_disc'];
		$ProfitShare *= ($lastRev/100);
		
		if($newRev == true){ $sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] = 0;
		
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['ID'] = $revId;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey]['Date'] = $revDate; $newRev = false; }
		$sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'][$RevKey][$lastRev] += $ProfitShare;	
		$sorted_array[$record['cust_id']]['Totals']['Rev'] = $lastRev;
	}
	$sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Total'] = 0;
	$sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Refund'] = 0;
	
	$getMerch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getMerch->mysql("SELECT * FROM `photo_invoices` WHERE `chrg_date` >= '$Start' AND `chrg_date` <= '$End' AND `process` = 'y' AND `fail` = 'n' AND `cust_id` = '".$record['cust_id']."' ".$merchaddthis2." ORDER BY `chrg_date` ASC;");

	foreach($getMerch->Rows() as $r){ 	
		$sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Total'] += $r['amount'];
		if($r['refund'] == 'y') $sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Refund'] += $r['amount'];
		
		if($LastCustId2 != $record['cust_id']) { $lastMrch = 0; $LastCustId2 = $r['cust_id']; }
		
		foreach($sorted_array[$record['cust_id']]['Dates'][$date]['Totals']['Rev'] as $v){
			$RevDate = date("U", mktime(substr($v['Date'],11,2), substr($v['Date'],14,2), substr($v['Date'],17,2), substr($v['Date'],5,2), substr($v['Date'],8,2), substr($v['Date'],0,4)));
			$MrchDate = date("U", mktime(substr($r['chrg_date'],11,2), substr($r['chrg_date'],14,2), substr($r['chrg_date'],17,2), substr($r['chrg_date'],5,2), substr($r['chrg_date'],8,2), substr($r['chrg_date'],0,4)));
			if(intval($MrchDate) >= intval($RevDate)){ $updMrch = intval($v['ID']); $FeeDate = $v['Date']; $SvLvl = $v['ID']; }
		}
		if($updMrch != $lastMrch) $lastMrch = $updMrch;
		switch($SvLvl){
			case 9: 	case 328: 	case 10:  $Fee = .06; break;
			case 329:	case 11: 							$Fee = .05; break;
			default: 												$Fee = .06; break;
		}
		$sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Date'][$FeeDate]['ID'] = $Fee;
		$sorted_array[$record['cust_id']]['Dates'][$date]['Merchant']['Date'][$FeeDate]['Fee'] += $r['amount']*$Fee;
	}
}
?>