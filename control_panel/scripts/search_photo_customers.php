<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_3_table.php';
require_once $r_path.'scripts/fnct_format_phone.php'; 
require_once $r_path.'scripts/fnct_clean_entry.php';

$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable($_POST['Phone_Number'],true) : '';
$replace = array("(",")","-");
$with = array("","","");
$Phone = str_replace($replace, $with, $Phone);

?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Customer List </h2>
  
  <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','query','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>Customer Name:</td>
	<td><input type="text" name="First Name" id="First_Name" value="<? echo $FName; ?>" /> <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName; ?>" /></td>
  </tr>
  <tr>
    <td>Customer E-mail:</td>
	<td><input type="text" name="Email" id="Email" value="<? echo $Email; ?>" /></td>
  </tr>
  <tr>
    <td>Phone Number:</td>
	<td><input type="text" name="Phone Number" id="Phone_Number" value="<? if(is_numeric($Phone))echo phone_number($Phone); ?>" /></td>
  </tr>
</table>
<p>
  <input type="submit" name="btn_Search" id="btn_Search" value="Search" />
</p>
<p>
  <?
$query_get_records = "SELECT `cust_customers`.`cust_id`,`cust_customers`.`cust_fname`,`cust_customers`.`cust_lname`,`cust_customers`.`cust_city`,`cust_customers`.`cust_state`,`cust_customers`.`cust_zip`,`cust_customers`.`cust_phone`,`cust_customers`.`cust_email` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `photo_event_images`.`cust_id` = '$CustId' AND (`cust_customers`.`cust_fname` LIKE '%".$FName."%' AND `cust_customers`.`cust_lname` LIKE '%".$LName."%' AND `cust_customers`.`cust_email` LIKE '%".$Email."%' AND `cust_customers`.`cust_phone` LIKE '%".$Phone."%') GROUP BY `cust_customers`.`cust_id` ORDER BY `cust_customers`.`cust_fname` , `cust_customers`.`cust_lname` ASC";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Customer Name";
$headers[1] = "Address";
$headers[2] = "Phone Number";
$headers[3] = "E-mail";
if($FName != '' || $LName != '' || $Email != '' || $Phone != ''){
	do{
		$count = count($records);
		$records[$count][0] = false;
		$records[$count][1] = $row_get_records['cust_id'];
		$records[$count][2] = $row_get_records['cust_lname'].", ".$row_get_records['cust_fname'];
		$records[$count][3] = $row_get_records['cust_city']." ".$row_get_records['cust_state']." ".$row_get_records['cust_zip'];
		$records[$count][4] = phone_number($row_get_records['cust_phone']);
		$records[$count][5] = $row_get_records['cust_email'];
		
	} while ($row_get_records = mysql_fetch_assoc($get_records)); 
}

mysql_free_result($get_records);

build_record_3_table('Customers','Customers',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,false,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,false,false,false,false,false);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>
</p>
