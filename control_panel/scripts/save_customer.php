<?php
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$CId = (isset($_POST['Customer_Id'])) ? clean_variable($_POST['Customer_Id'],true) : '';
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$MInt = (isset($_POST['Middle_Initial'])) ? clean_variable($_POST['Middle_Initial'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$CName = (isset($_POST['Company_Name'])) ? clean_variable($_POST['Company_Name'],true) : '';
$CDesc = (isset($_POST['Client_Description'])) ? clean_variable($_POST['Client_Description']) : '';
$Add = (isset($_POST['Address'])) ? clean_variable($_POST['Address'],true) : '';
$Add2 = (isset($_POST['Address_2'])) ? clean_variable($_POST['Address_2'],true) : '';
$SApt = (isset($_POST['Suite_Apt'])) ? clean_variable($_POST['Suite_Apt'],true) : '';
$City = (isset($_POST['City'])) ? clean_variable($_POST['City'],true) : '';
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : '';
$Zip = (isset($_POST['Zip'])) ? clean_variable($_POST['Zip'],true) : '';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$P1 = (isset($_POST['P1'])) ? clean_variable($_POST['P1'],true) : '';
$P2 = (isset($_POST['P2'])) ? clean_variable($_POST['P2'],true) : '';
$P3 = (isset($_POST['P3'])) ? clean_variable($_POST['P3'],true) : '';
$Cell = (isset($_POST['Cell_Number'])) ? clean_variable($_POST['Cell_Number'],true) : '';
$C1 = (isset($_POST['C1'])) ? clean_variable($_POST['C1'],true) : '';
$C2 = (isset($_POST['C2'])) ? clean_variable($_POST['C2'],true) : '';
$C3 = (isset($_POST['C3'])) ? clean_variable($_POST['C3'],true) : '';
$Fax = (isset($_POST['Fax_Number'])) ? clean_variable($_POST['Fax_Number'],true) : '';
$F1 = (isset($_POST['F1'])) ? clean_variable($_POST['F1'],true) : '';
$F2 = (isset($_POST['F2'])) ? clean_variable($_POST['F2'],true) : '';
$F3 = (isset($_POST['F3'])) ? clean_variable($_POST['F3'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Email2 = (isset($_POST['Email_2'])) ? clean_variable($_POST['Email_2'],true) : '';
$Website = (isset($_POST['Website'])) ? clean_variable($_POST['Website'],true) : '';
$Thumbv = (isset($_POST['Thumbnail_val'])) ? clean_variable($_POST['Thumbnail_val'],true) : '';
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$IP = (isset($_POST['IP_Address'])) ? clean_variable($_POST['IP_Address'],true) : '';
$SecLevel = (isset($_POST['Security_Level'])) ? $_POST['Security_Level'] : '';
$ACust = (isset($_POST['Approved_Customer'])) ? $_POST['Approved_Customer'] : 'y';
$OCust = (isset($_POST['Online_Customer'])) ? $_POST['Online_Customer'] : 'n';
$Port = (isset($_POST['Portfolio'])) ?  $_POST['Portfolio'] : 'n';
if(isset($_POST['controller']) && $_POST['controller'] == "true"){	
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Comp_Folder, $Comp_IWidth, $Comp_IHeight, $Comp_ICrop, $Comp_IResize);
	} else {
		$Image = array();
		if($Comp_IReq && $Imagev != ""){
			$Image[0] = false;
		} else {
			$Image[0] = true;
		}
		$Image[1] = $Imagev;
	}
	if (is_uploaded_file($_FILES['Thumbnail']['tmp_name'])){
		$Dname = date("mdy");
		$Fname = $_FILES['Thumbnail']['name'];
		$Iname = format_file_name($Fname,"t");
		$ISize = $_FILES['Thumbnail']['size'];
		$ITemp = $_FILES['Thumbnail']['tmp_name'];
		$IType = $_FILES['Thumbnail']['type'];
		
		$Thumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Comp_Folder, $Comp_TWidth, $Comp_THeight, $Comp_TCrop, $Comp_TResize);
	} else {
		$Thumb = array();
		if($Comp_TReq && $Thumbv != ""){
			$Thumb[0] = false;
		} else {
			$Thumb[0] = true;
		}
		$Thumb[1] = $Thumbv;
	}
	if($Image[0] && $Thumb[0]){
		$Image = $Image[1];
		$Thumb = $Thumb[1];
		if($_POST['Remove_Image'] == "true"){
			$Image = "";
		}
		if($_POST['Remove_Thumbnail'] == "true"){
			$Thumb = "";
		}
		$text = clean_variable($CDesc,'Store');
		if($_GET['cont'] == "add"){
			$add = "INSERT INTO `cust_customers` (`cust_fname`,`cust_mint`,`cust_lname`,`cust_cname`,`cust_desc`,`cust_add`,`cust_add_2`,`cust_suite_apt`,`cust_city`,`cust_state`,`cust_zip`,`cust_country`, `cust_phone`,`cust_cell`,`cust_fax`,`cust_email`,`cust_email_2`,`cust_website`,`cust_image`,`cust_thumb`,`cust_ip`,`cust_level`,`cust_online`,`cust_portfolio`,`cust_active`) VALUES ('$FName','$MInt','$LName','$CName','$text','$Add','$Add2','$SApt','$City','$State','$Zip','$Country','$Phone','$Cell','$Fax','$Email','$Email2','$Website','$Image','$Thumb','$IP','$SecLevel','$OCust','$Port','$ACust');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$url_string = preg_replace("/is_info=[\\d\\w]*/","",$_SERVER['QUERY_STRING']);
			$url_string = preg_replace("/&cont=[\\d\\w]*/","",$url_string);
			$url_string = preg_replace("/&info=[\\d\\w]*,[\\d\\w]* [\\d\\w]*/","",$url_string);
			$url_string = preg_replace("/&info=[\\d\\w]*,[\\d\\w]*/","",$url_string);
			$url_string = $_SERVER['PHP_SELF']."?".preg_replace("/&info=[\\d\\w]*/","",$url_string);
			echo "<script type=\"text/javascript\">document.location.href=\"".$url_string."\";</script>";
			die();
		} else if($_GET['cont'] == "edit"){
			$upd = "UPDATE `cust_customers` SET `cust_fname` = '$FName',`cust_mint` = '$MInt',`cust_lname` = '$LName',`cust_cname` = '$CName',`cust_desc` = '$text',`cust_add` = '$Add',`cust_add_2` = '$Add2',`cust_suite_apt` = '$SApt',`cust_city` = '$City',`cust_state` = '$State',`cust_zip` = '$Zip',`cust_country` = '$Country',`cust_phone` = '$Phone',`cust_cell` = '$Cell',`cust_fax` = '$Fax',`cust_email` = '$Email',`cust_email_2` = '$Email2',`cust_website` = '$Website',`cust_image` = '$Image',`cust_thumb` = '$Thumb',`cust_ip` = '$IP',`cust_level` = '$SecLevel',`cust_online` =  '$OCust', `cust_portfolio` = '$Port', `cust_active` = '$ACust' WHERE `cust_id` = '$CId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			
			$url_string = preg_replace("/is_info=[\\d\\w]*/","",$_SERVER['QUERY_STRING']);
			$url_string = preg_replace("/&cont=[\\d\\w]*/","",$url_string);
			$url_string = preg_replace("/&info=[\\d\\w]*,[\\d\\w]* [\\d\\w]*/","",$url_string);
			$url_string = preg_replace("/&info=[\\d\\w]*,[\\d\\w]*/","",$url_string);
			$url_string = $_SERVER['PHP_SELF']."?".preg_replace("/&info=[\\d\\w]*/","",$url_string);
			//header(sprintf("Location: %s", $url_string));			
			echo "<script type=\"text/javascript\">document.location.href=\"".$url_string."\";</script>";
			//echo "<body onLoad=\"javascript:opener.page_refresh(); window.close();\"></body>";
			die(); 
		}
	} else {
		$message = $Image[1]."\n".$Thumb[1];
		$Image = $Image[1];
		$Thumb = $Thumb[1];
	}
} else {
	if($_GET['cont'] != "add"){
		$CId = $_GET['id'];
	
		$query_get_customer_info = "SELECT * FROM `cust_customers` WHERE `cust_id` = '$CId'";
		$get_customer_info = mysql_query($query_get_customer_info, $cp_connection) or die(mysql_error());
		$row_get_customer_info = mysql_fetch_assoc($get_customer_info);
		$totalRows_get_customer_info = mysql_num_rows($get_customer_info);
		
		$FName = $row_get_customer_info['cust_fname'];
		$MInt = $row_get_customer_info['cust_mint'];
		$LName = $row_get_customer_info['cust_lname'];
		$CName = $row_get_customer_info['cust_cname'];
		$CDesc = $row_get_customer_info['cust_desc'];
		$Add = $row_get_customer_info['cust_add'];
		$Add2 = $row_get_customer_info['cust_add_2'];
		$SApt = $row_get_customer_info['cust_suite_apt'];
		$City = $row_get_customer_info['cust_city'];
		$State = $row_get_customer_info['cust_state'];
		$Zip = $row_get_customer_info['cust_zip'];
		$Country = $row_get_customer_info['cust_country'];
		$Phone = $row_get_customer_info['cust_phone'];
		$P1 = substr($row_get_customer_info['cust_phone'],0,3);
		$P2 = substr($row_get_customer_info['cust_phone'],3,3);
		$P3 = substr($row_get_customer_info['cust_phone'],6,4);
		$Cell = $row_get_customer_info['cust_cell'];
		$C1 = substr($row_get_customer_info['cust_cell'],0,3);
		$C2 = substr($row_get_customer_info['cust_cell'],3,3);
		$C3 = substr($row_get_customer_info['cust_cell'],6,4);
		$Fax = $row_get_customer_info['cust_fax'];
		$F1 = substr($row_get_customer_info['cust_fax'],0,3);
		$F2 = substr($row_get_customer_info['cust_fax'],3,3);
		$F3 = substr($row_get_customer_info['cust_fax'],6,4);
		$Email = $row_get_customer_info['cust_email'];
		$Email2 = $row_get_customer_info['cust_email_2'];
		$Website = $row_get_customer_info['cust_website'];
		$Thumb = $row_get_customer_info['cust_thumb'];
		$Image = $row_get_customer_info['cust_image'];
		$Thumbv = $Thumb;
		$Imagev = $Image;
		$IP = $row_get_customer_info['cust_ip'];
		$SecLevel = $row_get_customer_info['cust_level'];
		$OCust = $row_get_customer_info['cust_online'];
		$ACust = $row_get_customer_info['cust_active'];
		$Port = $row_get_customer_info['cust_portfolio'];
		
		mysql_free_result($get_customer_info);
	}
}
?>
