<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
$RealPath = $r_path; $r_path = '../';
define ("PhotoExpress Pro", true);
require_once $r_path.'config.php';
require_once $r_path.'../Connections/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';


$DATA = isset($_GET['user']) ? clean_variable($_GET['user'],true) : 0;
$ACT = isset($_GET['act']) ? clean_variable($_GET['act'],true) : '1';
$ID = isset($_GET['id']) ? clean_variable($_GET['id'],true) : '0';
if($ACT == '2'){
	$ID = explode(".",$ID);
	if($ID[0] == "2"){
			$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getInfo->mysql("SELECT * FROM `photo_invoices` WHERE `invoice_id` = '".$ID[1]."';");
			$getInfo = $getInfo->Rows();
					
			$FName = $getInfo[0]['fname'];
			$MInt = '';
			$LName = $getInfo[0]['lname'];
			$Add = $getInfo[0]['add'];
			$Add2 = $getInfo[0]['add2'];
			$SApt = $getInfo[0]['suite_apt'];
			$City = $getInfo[0]['city'];
			$State = $getInfo[0]['state'];
			$Zip = $getInfo[0]['zip'];
			$Country = $getInfo[0]['country'];
			$Phone = $getInfo[0]['phone'];
			$P1 = substr($getInfo[0]['phone'],0,3);
			$P2 = substr($getInfo[0]['phone'],3,3);
			$P3 = substr($getInfo[0]['phone'],6,4);
			$Email = $getInfo[0]['email'];
		} else {	
			$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getInfo->mysql("SELECT * FROM `cust_customers` WHERE `cust_id` = '".$ID[1]."';");
			$getInfo = $getInfo->Rows();
			
			$FName = $getInfo[0]['cust_fname'];
			$MInt = $getInfo[0]['cust_mint'];
			$LName = $getInfo[0]['cust_lname'];
			$Add = $getInfo[0]['cust_add'];
			$Add2 = $getInfo[0]['cust_add2'];
			$SApt = $getInfo[0]['cust_suite_apt'];
			$City = $getInfo[0]['cust_city'];
			$State = $getInfo[0]['cust_state'];
			$Zip = $getInfo[0]['cust_zip'];
			$Country = $getInfo[0]['cust_country'];
			$Phone = $getInfo[0]['cust_phone'];
			$P1 = substr($getInfo[0]['cust_phone'],0,3);
			$P2 = substr($getInfo[0]['cust_phone'],3,3);
			$P3 = substr($getInfo[0]['cust_phone'],6,4);
			$Email = $getInfo[0]['cust_email'];
		}
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header('Content-type: text/xml');
	
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<data>';
	echo '<info id="'.$ID[1].'" fname="'.$FName.'" mint="'.$MInt.'" lname="'.$LName.'" email="'.$Email.'" add="'.$Add.'" add2="'.$Add2.'" suite="'.$SApt.'" city="'.$City.'" state="'.$State.'" zip="'.$Zip.'" country="'.$Country.'" p1="'.$P1.'" p2="'.$P2.'" p3="'.$P3.'"></info>';
	echo '</data>';
} else {
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT * FROM (
				SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email`, '1' AS `type`
				FROM `photo_event_images`
				INNER JOIN `orders_invoice_photo`
					ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id`
				INNER JOIN `orders_invoice`
					ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
				INNER JOIN `cust_customers` 
					ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
				WHERE `photo_event_images`.`cust_id` = '".$DATA."' 
					AND `cust_photo` = 'n'
				GROUP BY `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`
				
			UNION ALL 
			
				SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_city`, `cust_state`, `cust_zip`, `cust_phone`, `cust_email`, '1' AS `type`
				FROM `cust_customers` 
				WHERE `photo_id` = '".$DATA."'
					AND `cust_photo` = 'n'
				GROUP BY `cust_fname`, `cust_lname`, `cust_email`
				
			UNION ALL 
				
				SELECT CONCAT('SpcMrch,',`invoice_id`) AS `cust_id`, `fname` AS `cust_fname`, `lname` AS `cust_lname`, `city` AS `cust_city`, `state` AS `cust_state`, `zip` AS `cust_zip`, `phone` AS `cust_phone`, `email` AS `cust_email`, '2' AS `type`
				FROM `photo_invoices` 
				WHERE `cust_id` = '".$DATA."'
				GROUP BY `cust_fname`, `cust_lname`, `cust_email`
			) AS `DT1`
		GROUP BY `cust_fname`, `cust_lname`, `cust_email`
		ORDER BY `cust_lname` ASC, `cust_fname` ASC;");

	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header('Content-type: text/xml');
	
	echo '<?xml version="1.0" encoding="utf-8"?>';
	echo '<data>';
	foreach($getInfo->Rows() as $r){
		echo '<info id="'.$r['cust_id'].'" type="'.$r['type'].'" name="'.$r['cust_lname'].', '.$r['cust_fname'].'" email="'.$r['cust_email'].'" city="'.$r['cust_city'].'" state="'.$r['cust_state'].'" zip="'.$r['cust_zip'].'"></info>';
	}
	echo '</data>';
}
?>
