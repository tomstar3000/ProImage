<?php
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_date_format_for_db.php';

$IId = (isset($_GET['id'])) ? $_GET['id'] : $_POST['Inv_Id'];
$Cust_id = (isset($_POST['Company'])) ? $_POST['Company'] : false;
$Cust_id  = (isset($_GET['id'])) ? $_POST['Sel_Company'] : $Cust_id;
$Avail_items = array();
$Used_items = array();
$Desc_items = array();

if(isset($_POST['Inv_Num'])){
	$INum = $_POST['Inv_Num'];
	$IRev = $_POST['Rev_Num'];
} else {
	$query_get_last = "SELECT `invoice_num_2` FROM `orders_invoice` WHERE `invoice_online` = 'n' ORDER BY `invoice_num_2` DESC LIMIT 0,1";
	$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
	$row_get_last = mysql_fetch_assoc($get_last);
	$totalRows_get_last = mysql_num_rows($get_last);
	
	if($totalRows_get_last == 0){
		$INum = 100;
	} else {
		$INum = $row_get_last['invoice_num_2']+1;
	}
	mysql_free_result($get_last);
	
	if(isset($_GET['id'])){
		$query_get_last = "SELECT `invoice_rev` FROM `orders_invoice` WHERE `invoice_id` = '$IId'";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		$totalRows_get_last = mysql_num_rows($get_last);
		
		$IRev = ($row_get_last['invoice_rev'] == "") ? 1 : $row_get_last['invoice_rev']+1;
		
		mysql_free_result($get_last);
	}
	
}
if(isset($_POST['controller']) && isset($_POST['Company'])){
	if(isset($_SESSION['Invoice_Items'])){
		$Desc_items = $_SESSION['Invoice_Items'];
	}
	$Used_items = (isset($_POST['Proj_Ids'])) ? $_POST['Proj_Ids'] : array();
	$Avail_items = (isset($_POST['Available'])) ? $_POST['Available'] : array();

	if(isset($_POST['controller']) && $_POST['controller'] == "Add"){
		$Proj_id = $_POST['Project'];
		$Used_items[count($Used_items)] = $Proj_id;
		if($Proj_id <= 0){
			$Avail_items[0] = $Proj_id-1;
			$Desc_items[$Proj_id]["Name"] = "Empty Line Item";
			$Desc_items[$Proj_id]["Orig_Name"] = "Empty Line Item";
		} else {
			$key = array_search($Proj_id, $Avail_items);
			unset($Avail_items[$key]);
		}
		
	} else if (isset($_POST['controller']) && $_POST['controller'] == "Remove"){
		$Remove = $_POST['Remove_Ids'];
		foreach($Remove as $k => $v){
			if($v>0){
				$Avail_items[count($Avail_items)] = $v;
			}
			$key = array_search($v, $Used_items);
			unset($Used_items[$key]);
		}
	}
	if(isset($_POST['Proj_Ids'])){
		$Ids = array();
		$Names = array();
		$Descs = array();
		$Hours = array();
		$Amounts = array();
		$Ids = $_POST['Proj_Ids'];
		$Names = $_POST['Proj_Name'];
		$Descs = $_POST['Proj_Desc'];
		$Hours = $_POST['Proj_Hours'];
		$Amounts = $_POST['Proj_Amount'];
		foreach($Ids as $k => $v){
			$Desc_items[$v]["Name"] = $Names[$k];
			$Desc_items[$v]["Desc"] = $Descs[$k];
			$Desc_items[$v]["Hours"] = $Hours[$k];
			$Desc_items[$v]["Amount"] = $Amounts[$k];
		}
	}
	$_SESSION['Invoice_Items'] = $Desc_items;
}
if(isset($_POST['controller']) && $_POST['controller'] == "true"){
	if($_GET['cont'] == "add"){
		$Proj_Total = $_POST['Total'];
		$Due_Date = date("Y-m-d H:i:s", mktime(0,0,0, date("m")+1, date("d"), date("Y")));
		
		$add = "INSERT INTO `orders_invoice` (`cust_id`,`invoice_num_2`,`invoice_rev`,`invoice_total`,`invoice_date`, `invoice_due`, `invoice_paid`,`invoice_comp`,`invoice_online`) VALUES ('$Cust_id','$INum','0','$Proj_Total',NOW(),'$Due_Date','n','n','n');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$query_get_last = "SELECT `invoice_id` FROM `orders_invoice` WHERE `invoice_num_2` = '$INum'";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		$totalRows_get_last = mysql_num_rows($get_last);
		
		$IId = $row_get_last['invoice_id'];
		
		mysql_free_result($get_last);
		
		foreach ($Used_items as $k => $v){	
			$Name = $Desc_items[$v]["Name"];
			$Desc = $Desc_items[$v]["Desc"];
			$Hours = $Desc_items[$v]["Hours"];
			$Amount = $Desc_items[$v]["Amount"];
			
			$add = "INSERT INTO `orders_invoice_proj` (`invoice_id`,`proj_id`,`invoice_item_title`,`invoice_item_desc`,`invoice_item_hours`,`invoice_item_amt`) VALUES ('$IId','$v','$Name','$Desc','$Hours','$Amount');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		}		
		
		include $r_path.'scripts/re_direct.php';
	} else {
		$del= "DELETE FROM `orders_invoice_proj` WHERE `invoice_id` = '$IId'";
		$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
		
		$optimize = "OPTIMIZE TABLE `orders_invoice_proj`";
		$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
		
		foreach ($Used_items as $k => $v){	
			$Name = $Desc_items[$v]["Name"];
			$Desc = $Desc_items[$v]["Desc"];
			$Hours = $Desc_items[$v]["Hours"];
			$Amount = $Desc_items[$v]["Amount"];
			
			$add = "INSERT INTO `orders_invoice_proj` (`invoice_id`,`proj_id`,`invoice_item_title`,`invoice_item_desc`,`invoice_item_hours`,`invoice_item_amt`) VALUES ('$IId','$v','$Name','$Desc','$Hours','$Amount');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		}
		$upd = "UPDATE `orders_invoice` SET `invoice_rev` = '$IRev' WHERE `invoice_id` = '$IId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());	
		
		include $r_path.'scripts/re_direct.php'; 
	}
} else {
	if(isset($_GET['id']) && count($Used_items) == 0 && !isset($_POST['controller'])){
		$query_get_invoice_information = "SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_cname` FROM `orders_invoice` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `invoice_id` = '$IId'";
		$get_invoice_information = mysql_query($query_get_invoice_information, $cp_connection) or die(mysql_error());
		$row_get_invoice_information = mysql_fetch_assoc($get_invoice_information);
		
		$INum = $row_get_invoice_information['invoice_num_2'];
		$Cust_id = $row_get_invoice_information['cust_id'];
		$CName = ($row_get_invoice_information['cust_fname']=="") ? $row_get_invoice_information['cust_cname'] : $row_get_invoice_information['cust_lname'].", ".$row_get_invoice_information['cust_fname'];
		$Rev = $row_get_invoice_information['invoice_rev'];
		
		$query_get_inv_items_information = "SELECT * FROM `orders_invoice_proj` WHERE `invoice_id` = '$IId'";
		$get_inv_items_information = mysql_query($query_get_inv_items_information, $cp_connection) or die(mysql_error());
		$row_get_inv_items_information = mysql_fetch_assoc($get_inv_items_information);
		$totalRows_get_inv_items_information = mysql_num_rows($get_inv_items_information);	
		
		do{			
			$count = count($Used_items);
			$Used_items[$count] = $row_get_inv_items_information['proj_id'];
			$Desc_items[$Used_items[$count]]["Name"] = ($row_get_inv_items_information['invoice_item_title']) == "" ? "&nbsp;" : $row_get_inv_items_information['invoice_item_title'];
			$Desc_items[$Used_items[$count]]["Orig_Name"] = ($row_get_inv_items_information['invoice_item_title']) == "" ? "&nbsp;" : $row_get_inv_items_information['invoice_item_title'];
			$Desc_items[$Used_items[$count]]["Hours"] = ($row_get_inv_items_information['invoice_item_hours']) == "" ? "&nbsp;" : $row_get_inv_items_information['invoice_item_hours'];
			$Desc_items[$Used_items[$count]]["Amount"] = ($row_get_inv_items_information['invoice_item_amt']) == "" ? "&nbsp;" : $row_get_inv_items_information['invoice_item_amt'];
			$Desc_items[$Used_items[$count]]["Orig_Amount"] = ($row_get_inv_items_information['invoice_item_amt']) == "" ? "&nbsp;" : $row_get_inv_items_information['invoice_item_amt'];
			$Desc_items[$Used_items[$count]]["Desc"] = $row_get_inv_items_information['invoice_item_desc'];
			if($row_get_inv_items_information['proj_id']<=0){
				$temp_id = ($temp_id) ? $temp_id-1 : -1;
			}
		} while($row_get_inv_items_information = mysql_fetch_assoc($get_inv_items_information));
		mysql_free_result($get_inv_items_information);
		
		mysql_free_result($get_invoice_information);
	}
	if($Cust_id != $_POST['Sel_Company'] && (!isset($_POST['controller']) || $_POST['controller'] == "false")){
		if(isset($_SESSION['Invoice_Items'])){
			unset($_SESSION['Invoice_Items']);
			session_unregister('Invoice_Items');
			$_SESSION['Invoice_Items'] = "";
			$Avail_items = array();
			if(!isset($_GET['id'])){
				$Used_items = array();
				$Desc_items = array();
			}
		}
		session_register("Invoice_Items");
		$query_get_projects = "SELECT `proj_projects`.`proj_id` , `proj_projects`.`proj_name` , `proj_projects`.`proj_number` , `proj_projects`.`proj_price` , SUM( `proj_timesheet`.`time_amt` ) AS `timesheet` , SUM( `orders_invoice_proj`.`invoice_item_amt` ) AS `total` FROM `proj_projects` LEFT OUTER JOIN `proj_timesheet` ON `proj_timesheet`.`proj_id` = `proj_projects`.`proj_id` LEFT OUTER JOIN `orders_invoice_proj` ON `orders_invoice_proj`.`proj_id` = `proj_projects`.`proj_id` WHERE `proj_projects`.`cust_id` = '$Cust_id' GROUP BY `proj_projects`.`proj_id`";
		$get_projects = mysql_query($query_get_projects, $cp_connection) or die(mysql_error());
		$row_get_projects = mysql_fetch_assoc($get_projects);
		$totalRows_get_projects = mysql_num_rows($get_projects);
		
		$Avail_items[0] = ($temp_id) ? $temp_id : 0;
		$Desc_items[0]["Name"] = "Empty Line Item";
		$Desc_items[0]["Orig_Name"] = "Empty Line Item";
		$Desc_items[0]["Orig_Amount"] = 0;
		do{			
			if($row_get_projects['timesheet'] == ""){
				if($row_get_projects['proj_price']>$row_get_projects['total']){
					$price = $row_get_projects['proj_price']-$row_get_projects['total'];
				} else {
					$price = false;
				}
			} else {
				if($row_get_projects['timesheet']>$row_get_projects['total']){
					$price = $row_get_projects['timesheet']-$row_get_projects['total'];
				} else {
					$price = false;
				}
			}
			if($price){
				if (in_array($row_get_projects['proj_id'], $Used_items)) {
					$Proj_Num = ($row_get_projects['proj_number'] == "") ? "" : " - ".$row_get_projects['proj_number']." ";
					$Desc_items[$row_get_projects['proj_id']]["Orig_Name"] = $row_get_projects['proj_name'].$Proj_Num;
					if($Desc_items[$row_get_projects['proj_id']]["Orig_Amount"] < $price){
						$Desc_items[$row_get_projects['proj_id']]["Orig_Amount"] += $price;
					}
				} else {
					$count = count($Avail_items);
					$Avail_items[$count] = $row_get_projects['proj_id'];
					$Proj_Num = ($row_get_projects['proj_number'] == "") ? "" : " - ".$row_get_projects['proj_number']." ";
					$Desc_items[$row_get_projects['proj_id']]["Orig_Name"] = $row_get_projects['proj_name'].$Proj_Num;
					$Desc_items[$row_get_projects['proj_id']]["Orig_Amount"] = $price;
				}
			}
		} while($row_get_projects = mysql_fetch_assoc($get_projects));
		mysql_free_result($get_projects);
		
		$_SESSION['Invoice_Items'] = $Desc_items;
	}
}
?>