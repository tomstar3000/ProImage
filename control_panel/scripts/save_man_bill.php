<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$BId = $path[4];
$ManId = $path[2];
$BAdd = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$BAdd2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$BSuite = (isset($_POST['Suite/Apt'])) ? clean_variable($_POST['Suite/Apt'],true) : '';
$BCity = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$BState = (isset($_POST['State'])) ? $_POST['State'] : '';
$BZip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$BCount = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$BCC = (isset($_POST['Credit_Card_Number'])) ? clean_variable($_POST['Credit_Card_Number'],true) : '';
$B4Num = substr($BCC,-4,4);
$BCCV = (isset($_POST['CCV'])) ? clean_variable($_POST['CVV'],true) : '';
$BCCT = (isset($_POST['Credit_Card_Type'])) ? clean_variable($_POST['Credit_Card_Type'],true) : '';
$BCCM = (isset($_POST['Exp_Month'])) ? clean_variable($_POST['Exp_Month'],true) : date("m");
$BCCY = (isset($_POST['Exp_Year'])) ? clean_variable($_POST['Exp_Year'],true) : date("Y");
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `sellers_billing` (`sell_id`,`sell_bill_add`,`sell_bill_add_2`,`sell_bill_suite_apt`,`sell_bill_city`,`sell_bill_state`,`sell_bill_zip`,`sell_bill_country`,`sell_bill_ccnum`,`sell_bill_ccshort`,`sell_bill_ccv`,`sell_bill_exp_month`,`sell_bill_exp_year`,`sell_bill_cc_type_id`) VALUES ('$ManId','$BAdd','$BAdd2','$BSuite','$BCity','$BState','$BZip','$BCount','$BCC','$B4Num','$BCCV','$BCCM','$BCCY','$BCCT');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `sellers_billing` SET `sell_id` = '$ManId',`sell_bill_add` = '$BAdd',`sell_bill_add_2` = '$BAdd2',`sell_bill_suite_apt` = '$BSuite',`sell_bill_city` = '$BCity',`sell_bill_state` = '$BState',`sell_bill_zip` = '$BZip',`sell_bill_country` = '$BCount',`sell_bill_ccnum` = '$BCC',`sell_bill_ccshort` = '$B4Num',`sell_bill_ccv` = '$BCCV',`sell_bill_exp_month` = '$BCCM',`sell_bill_exp_year` = '$BCCY',`sell_bill_cc_type_id` = '$BCCT' WHERE `sell_bill_id` = '$BId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());	
	}
	$cont = "view";
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `sellers_billing` WHERE `sell_bill_id` = '$BId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$ManId = $row_get_info['sell_id'];
		$BAdd = $row_get_info['sell_bill_add'];
		$BAdd2 = $row_get_info['sell_bill_add_2'];
		$BSuite = $row_get_info['sell_bill_suite_apt'];
		$BCity = $row_get_info['sell_bill_city'];
		$BState = $row_get_info['sell_bill_state'];
		$BZip = $row_get_info['sell_bill_zip'];
		$BCount = $row_get_info['sell_bill_country'];
		$BCC = $row_get_info['sell_bill_ccnum'];
		$BCCV = $row_get_info['sell_bill_ccv'];
		$BCCT = $row_get_info['sell_bill_cc_type_id'];
		$BCCM = $row_get_info['sell_bill_exp_month'];
		$BCCY = $row_get_info['sell_bill_exp_year'];
		
		mysql_free_result($get_info);
	}
}
?>