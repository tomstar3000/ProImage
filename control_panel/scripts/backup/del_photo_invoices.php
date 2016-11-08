<? 
include $r_path.'security.php';
$items = array();
$items = $_POST['Invoices_items'];
function delete_vals($value){
	global $cp_connection;
	//$upd= "UPDATE `photo_event` SET `event_use` = 'n' WHERE `event_id` = '$value'";
	//$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
	//$del= "DELETE FROM `photo_event` WHERE `sell_whare_id` = '$value'";
	//$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
	
	//$optimize = "OPTIMIZE TABLE `photo_event`";
	//$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
}
if(count($items)>0){
	foreach ($items as $key => $value){
		array_push($path,$value);
		include($r_path.'scripts/get_photo_invoices.php');
		ob_start();
		$old_path = $r_path;
		$r_path .= "../";
		include($r_path.'checkout/invoice.php');
		$r_path = $old_path;
		$page = ob_get_contents();
		ob_end_clean();
		$reciever = $row_get_photo['cust_email'];
		//$reciever = "development@proimagesoftware.com";
		$reciever = "info@photoexpresspro.com";
		$Email = $row_get_photo['cust_email'];
		$page = str_replace($find, $replace, $page);
		$page = str_replace('="','=3D"',$page);
		require_once($r_path.'scripts/fnct_send_email.php');
		send_email($reciever, $Email, 'Invoice', $page, true, false, false);
		include($r_path.'scripts/save_photo_invoices.php');
		
		$path = array_slice($path,0,2);
		//delete_vals($value);
	}
}
?>