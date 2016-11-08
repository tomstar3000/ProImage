<?
$upddate = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),(date("d")-1),date("Y")));

$query_change_list = "SELECT `cust_email` FROM `cust_customers` WHERE `cust_photo` = 'y' GROUP BY `cust_email`";
$change_list = mysql_query($query_change_list, $cp_connection) or die(mysql_error());
$updbc = array();
while($row_change_list = mysql_fetch_assoc($change_list)){
	if($row_change_list['cust_email'] != "" && $row_change_list['cust_email'] != " "){
		array_push($updbc,$row_change_list['cust_email']);
	}
}
$updbc = implode(",",$updbc);

if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
	$eol="\r\n"; 
} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
	$eol="\r"; 
} else { 
	$eol="\n"; 
} 
$updmessage = "An update from Pro Image Software".$eol;
$updmessage .= "These Products have been Added or Updated".$eol.$eol;

$query_change_list = "SELECT `prod_name` FROM `prod_products` WHERE `prod_updated` >= '$upddate' AND `prod_use` = 'y' ORDER BY `prod_name`";
$change_list = mysql_query($query_change_list, $cp_connection) or die(mysql_error());
$total_change_list = mysql_num_rows($change_list);

$updn = 1;
while($row_change_list = mysql_fetch_assoc($change_list)){
	$updmessage .= $updn.". ".$row_change_list['prod_name'].$eol;
	$updn++;
}

$query_change_list = "SELECT `prod_name` FROM `prod_products` WHERE `prod_updated` >= '$upddate' AND `prod_use` = 'n' ORDER BY `prod_name`";
$change_list = mysql_query($query_change_list, $cp_connection) or die(mysql_error());
$total_change_list = mysql_num_rows($change_list);

$updmessage .= "These Products have been Removed or Discontinued".$eol.$eol;

$updn = 1;
while($row_change_list = mysql_fetch_assoc($change_list)){
	$updmessage .= $updn.". ".$row_change_list['prod_name'].$eol;
	$updn++;
}

send_email("development@proimagesoftware.com", "development@proimagesoftware.com", "Pro Image Software Product Update", $updmessage, false, false, false, true);
?>
