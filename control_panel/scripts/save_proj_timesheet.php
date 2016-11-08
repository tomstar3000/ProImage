<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
if($path[3] == "Proj"){
	$TId = $path[6];
	$Proj_Id = $path[4];
} else {
	$TId = $path[4];
	$Proj_Id = $path[2];
}
$Emp_id = (isset($_POST['Employee'])) ? clean_variable($_POST['Employee'],true) : '';
$Work_id = (isset($_POST['Work_Type'])) ? clean_variable($_POST['Work_Type'],true) : '';
$TDate = (isset($_POST['Date'])) ? clean_variable($_POST['Date'],true) : '';
$TRate = (isset($_POST['Bill_Rate'])) ? clean_variable($_POST['Bill_Rate'],true) : '';
$THours = (isset($_POST['Hours'])) ? clean_variable($_POST['Hours'],true) : '';
$TDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$TAmt = (isset($_POST['Amount'])) ? clean_variable($_POST['Amount'],true) : '0';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($TDesc,'Store');
	if($cont == "add"){
		$add = "INSERT INTO `proj_timesheet` (`proj_id`,`time_date`,`time_bill`,`time_hours`,`time_amt`,`time_desc`,`emp_id`,`cat_id`) VALUES ('$Proj_Id','$TDate','$TRate','$THours','$TAmt','$text','$Emp_id','$Work_id');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `proj_timesheet` SET `proj_id` = '$Proj_Id',`time_date` = '$TDate',`time_bill` = '$TRate',`time_hours` = '$THours',`time_amt` = '$TAmt',`time_desc` = '$text',`emp_id` = '$Emp_id',`cat_id` = '$Work_id' WHERE `time_id` = '$TId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `proj_timesheet` WHERE `time_id` = '$TId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$Emp_id = $row_get_info['emp_id'];
		$Work_id = $row_get_info['cat_id'];
		$TDate = $row_get_info['time_date'];
		$TRate = $row_get_info['time_bill'];
		$THours = $row_get_info['time_hours'];
		$TDesc = $row_get_info['time_desc'];
		$TAmt = $row_get_info['time_amt'];
		
		mysql_free_result($get_info);
	}
}
?>