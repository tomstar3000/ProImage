<?php
require_once($r_path."scripts/fnct_clean_entry.php");
$Crumb_path = array();
$Crumb_link = array();
$old_path = (isset($_GET['op'])) ? explode(",", clean_variable($_GET['op'], true)) : false;
$old_ids = (isset($_GET['oid'])) ? explode(",", clean_variable($_GET['oid'], true)) : false;
if($old_path !== false){
	// 0
	array_push($Crumb_path, $pathing[0]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$old_path[0]);
	//1
	foreach($old_path as $k => $v){
		array_push($Crumb_path, $v);
		array_push($Crumb_link, 'Path='.$pathing[0].','.explode(",",array_slice($old_path,0,($k+1))));
	}
	//2
	if($pathing[0] == "Rept"){
		array_push($Crumb_path, $old_ids[0]);
	} else {
		array_push($Crumb_path, crumb_finder($Crumb_path['0'], $old_ids[0]));
	}
	array_push($Crumb_link, 'Path='.$pathing[0].','.$old_path[0].',&id='.$old_ids[0]);
	//3
	array_push($Crumb_path, $pathing[1]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$old_path[0].','.$pathing[1].'&id='.$old_ids[0]);
	//4
	if(count($old_ids) > 1){
		array_push($Crumb_path, $old_ids[1]);
		array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1].'&op='.$old_path[0].','.$old_path[1].'&oid='.$old_ids[0].','.$old_ids[1].'&id='.clean_variable($_GET['id'],true));
	}
	//5
	if($pathing[1] == "Rept"){
		array_push($Crumb_path, clean_variable($_GET['id'],true));
	} else {
		array_push($Crumb_path, crumb_finder($pathing[1], clean_variable($_GET['id'], true)));
	}
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1].'&op='.$old_path[0].','.$old_path[1].'&oid='.$old_ids[0].'&id='.clean_variable($_GET['id'],true));
	//6
	array_push($Crumb_path, $pathing[2]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1].','.$pathing[2].'&op='.$old_path[0].','.$old_path[1].'&oid='.$old_ids[0].'&id='.clean_variable($_GET['id'],true));
	$header = crumb_switch($pathing[0]);
	
} else if(isset($_GET['id'])) {
	//0
	array_push($Crumb_path, $pathing[0]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1]);
	//1
	array_push($Crumb_path, $pathing[1]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1]);
	//2
	if($pathing[0] == "Rept"){
		array_push($Crumb_path, clean_variable($_GET['id'], true));
	} else {
		array_push($Crumb_path, crumb_finder($pathing[0], clean_variable($_GET['id'], true)));
	}
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1].'&id='.clean_variable($_GET['id'], true));
	//3
	array_push($Crumb_path, $pathing[2]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1].','.$pathing[2].'&id='.clean_variable($_GET['id'], true));
	$header = crumb_switch($pathing[0]);
} else {
	array_push($Crumb_path, $pathing[0]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1]);
	array_push($Crumb_path, $pathing[1]);
	array_push($Crumb_link, 'Path='.$pathing[0].','.$pathing[1]);
	$header = crumb_switch($pathing[0]);
}
$Crumb = "";
foreach ($Crumb_path as $k => $v){
	if($k == (count($Crumb_path)-1) || $Crumb_path [$k+1] == ""){
		$Crumb .=  '<a href="'.$_SERVER['PHP_SELF'].'?'.$Crumb_link[$k].'&Sort='.$_GET['Sort'].'">'.crumb_switch ($v).'</a>';
	} else {
		$Crumb .=  '<a href="'.$_SERVER['PHP_SELF'].'?'.$Crumb_link[$k].'&Sort='.$_GET['Sort'].'">'.crumb_switch ($v)."</a> &gt; ";
	}
}
$Crumb = "Home > ".$Crumb;
function crumb_switch ($v){
	switch ($v) {
		case "Home":
		   $v = "Home";
		   break;
		case "Prod":
		   $v = "Products";
		   break;
		case "Cat":
		  $v = "Categories";
		   break;
		case "Feat":
		   $v = "Features";
		   break;
		case "Spec":
		   $v = "Specs";
		   break;
		case "Attrib":
		   $v = "Attributes";
		   break;
		case "Group":
		   $v = "Groups";
		   break;
		case "Specl":
		   $v = "Special Delivery";
		   break;
		case "Man":
		   $v = "Manufactures";
		   break;
		case "Rept":
		   $v = "Reports";
		   break;
		case "BillShip":
		   $v = "Billing and Shipping";
		   break;
		case "Credit":
		   $v = "Credit Cards";
		   break;
		case "Ship":
		   $v = "Shipping Companies";
		   break;
		case "State":
		   $v = "State Taxes";
		   break;
		case "Count":
		   $v = "County Taxes";
		   break;
		case "Cust":
		   $v = "Customers";
		   break;
		case "All":
		   $v = "All";
		   break;
		case "Online":
		   $v = "Online";
		   break;
		case "Inv":
		   $v = "Invoices";
		   break;
		case "Open":
		   $v = "Open";
		   break;
		case "Daily":
		   $v = "Daily";
		   break;
		case "Monthly":
		   $v = "Monthly";
		   break;
		case "High":
		   $v = "Highbred";
		   break;
		case "Cont":
		   $v = "Contracts";
		   break;
		case "Order":
		   $v = "Orders";
		   break;
		case "Gen":
		   $v = "General";
		   break;
		case "Proj":
		   $v = "Projects";
		   break;
		case "Web":
		   $v = "Website";
		   break;
		case "Nav":
		   $v = "Navigation";
		   break;
		case "Page":
		   $v = "Pages";
		   break;
		case "Review":
		   $v = "Review";
		   break;
		case "Save":
		   $v = "Save";
		   break;
		case "Cont_info":
		   $v = "Contact Information";
		   break;
		case "Test":
		   $v = "Testimonials";
		   break;
		case "Evnt":
		   $v = "Events";
		   break;
		case "Evnt_Cont":
		   $v = "Event Contact";
		   break;
		case "Evnt_Lov":
		   $v = "Event Location";
		   break;
		case "Quest":
		   $v = "Guestbook";
		   break;
		case "Link":
		   $v = "Links";
		   break;
		case "News":
		   $v = "News";
		   break;
		case "Press":
		   $v = "Press Releases";
		   break;
		case "CustForm":
		   $v = "Custom Forms";
		   break;
		case "Form":
		   $v = "Forms";
		   break;
		case "Table":
		   $v = "Tables";
		   break;
		case "Admin":
		   $v = "Administrator";
		   break;
		case "User":
		   $v = "User Settings";
		   break;
		case "Emp":
		   $v = "Employees";
		   break;
		case "Time":
		   $v = "Timesheet";
		   break;
	}
	return $v;
}

function crumb_finder($table_type, $id){
	global $cp_connection;
	switch($table_type){
		case "Prod":
			$query_get_name = "SELECT `prod_name` AS `Name` FROM `prod_products` WHERE `prod_id` = '$id'";
			break;
		case "Cust":
			$query_get_name = "SELECT `cust_lname` AS `LName`, `cust_fname` AS `FName`, `cust_cname` AS `CName` FROM `cust_customers` WHERE `cust_id` = '$id'";
			break;
		case "Inv":
			$query_get_name = "SELECT `cust_lname` AS `LName`, `cust_fname` AS `FName`, `cust_cname` AS `CName` FROM `cust_customers` WHERE `cust_id` = '$id'";
			break;
		case "Proj":
			$query_get_name = "SELECT `proj_name` AS `Name` FROM `proj_projects` WHERE `proj_id` = '$id'";
			break;
	}
	if(isset($query_get_name)){
		//mysql_select_db($database_cp_connection, $cp_connection);
		$get_name = mysql_query($query_get_name, $cp_connection) or die(mysql_error());
		$row_get_name = mysql_fetch_assoc($get_name);
		$totalRows_get_name = mysql_num_rows($get_name);
		
		if($table_type == "Cust" || $table_type == "Inv"){
			if($row_get_name['CName']){
				return $row_get_name['CName'];
			} else {
				return $row_get_name['FName']." ".$row_get_name['LName'];
			}
		} else {
			return $row_get_name['Name'];
		}
		
		mysql_free_result($get_name);
	}
}
?>