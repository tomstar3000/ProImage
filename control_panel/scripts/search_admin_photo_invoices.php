<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_3_table.php');
require_once($r_path.'scripts/fnct_format_date.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
$CustId = $path[2];
$Inv = (isset($_POST['Invoice_Number'])) ? clean_variable($_POST['Invoice_Number'],true) : '';
$SDate = (isset($_POST['Start_Date'])) ? clean_variable($_POST['Start_Date'],true) : '';
if($SDate != "" && $SDate != "--"){
	$SDate = substr($SDate,6,4)."-".substr($SDate,0,2)."-".substr($SDate,3,2);
	$query_start = " AND `orders_invoice`.`invoice_date` >= '".$SDate."'";
} else {
	//$SDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	//$query_start = " AND `orders_invoice`.`invoice_date` >= '".$SDate."'";
}
$EDate = (isset($_POST['End_Date'])) ? clean_variable($_POST['End_Date'],true) : '';
if($EDate != ""){
	$EDate = date("Y-m-d", mktime(0,0,0,substr($EDate,0,2),substr($EDate,3,2)+1,substr($EDate,6,4)));
	$query_end = " AND `orders_invoice`.`invoice_date` <= '".$EDate."'";
} else {
	//$EDate = date("Y-m-d");
}
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Search Invoices</h2>
</div>
<script type="text/javascript" src="/Aurigma/v_1/iuembed_fd.js"></script>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>Invoice Number: </td>
    <td><input type="text" name="Invoice Number" id="Invoice_Number" value="<? echo $Inv; ?>" /></td>
  </tr>
  <tr>
    <td>Date Range </td>
    <td><input type="text" name="Start Date" id="Start_Date" value="<? if($SDate != "" && $SDate != "--") echo substr($SDate,5,2)."/".substr($SDate,8,2)."/".substr($SDate,0,4); ?>" />
      <img src="/control_panel/images/ico_cal.jpg" width="19" height="22" hspace="0" vspace="0" border="0" onclick="newwindow=window.open('scripts/calendar.php?future=both&time=false&field=Start_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer" /> -
      <input type="text" name="End Date" id="End_Date" value="<? if($EDate != "" && $EDate != "--") echo date("m/d/Y", mktime(0,0,0,substr($EDate,5,2),substr($EDate,8,2)-1,substr($EDate,0,4))); ?>" />
      <img src="/control_panel/images/ico_cal.jpg" width="19" height="22" hspace="0" vspace="0" border="0" onclick="newwindow=window.open('scripts/calendar.php?future=both&time=false&field=End_Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;" style="cursor:pointer" /> (mm/dd/yyyy)
      &nbsp;</td>
  </tr>
  <tr>
    <td>Customer Name:</td>
    <td><input type="text" name="First Name" id="First_Name" value="<? echo $FName; ?>" />
      <input type="text" name="Last Name" id="Last_Name" value="<? echo $LName; ?>" /></td>
  </tr>
  <tr>
    <td>Customer E-mail:</td>
    <td><input type="text" name="Email" id="Email" value="<? echo $Email; ?>" /></td>
  </tr>
</table>
<input type="submit" name="btn_Search" id="btn_Search" value="Search" />
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Search Results</h2>
</div>
<div>
 <?
$query_get_info = "SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE (`cust_customers`.`cust_fname` LIKE '%".$FName."%' AND `cust_customers`.`cust_lname` LIKE '%".$LName."%' AND `orders_invoice`.`invoice_num` LIKE '%".$Inv."%' AND `cust_customers`.`cust_email` LIKE '%".$Email."%'".$query_start.$query_end.") GROUP BY `orders_invoice`.`invoice_id` ORDER BY `invoice_num` ASC";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "Invoice";
$headers[1] = "Date";
$headers[2] = "Accepted";
$headers[3] = "Total";
$headers[4] = "Name";
$headers[5] = "# Images";
$headers[6] = "Quality";
$headers[7] = "Printer";
$headers[8] = "Shipper";
$headers[9] = "&nbsp;";
$headers[10] = "&nbsp;";
if($Inv != '' || $SDate != '' || $EDate != '' || $FName != '' || $LName != '' || $Email != ''){
	do{	
		$count = count($records);
		$records[$count][0] = false;
		$records[$count][1] = $row_get_info['invoice_id'];
		$records[$count][2] = $row_get_info['invoice_num'];
		$records[$count][3] = format_date($row_get_info['invoice_date'],"Short",false,true,false);
		$records[$count][4] = format_date($row_get_info['invoice_accepted_date'],"Short",false,true,false);
		$records[$count][5] = "$".number_format($row_get_info['invoice_total'],2,".",",");
		$records[$count][6] = $row_get_info['cust_fname']." ".$row_get_info['cust_lname'];
		$records[$count][7] = $row_get_info['count_image_ids'];
		$records[$count][8] = $row_get_info['invoice_pers_quality'];
		$records[$count][9] = $row_get_info['invoice_pers_print'];
		$records[$count][10] = $row_get_info['invoice_pers_ship'];
		$records[$count][11] = '<a href="/checkout/invoice.php?invoice='.$row_get_info['invoice_enc'].'" target="_blank">View</a>';
	
		$tempnum = $row_get_info['invoice_num'];
		while(strlen($tempnum) < 5) $tempnum = "0".$tempnum;
		//if(is_dir($r_path."../toPhatFoto/".substr($row_get_info['invoice_date'],0,10)."/".$tempnum)){
			$records[$count][11] = '<div style="width:110px; height20px;" align="center"><script type="text/javascript">
	function FileDownload_'.$count.'(Step){
		if (Step == 2){
			getFileDownloader("FileDownloader_'.$count.'").setFileList("GetFileList.php?id='.substr($row_get_info['invoice_accepted_date'],0,10)."/".$tempnum.'");
		}
	}
	var fd = new FileDownloaderWriter("FileDownloader_'.$count.'", 101, 19);
	fd.activeXControlCodeBase = "/Aurigma/v_1/FileDownloader.cab";
	fd.activeXControlVersion = "1,0,100,0";
	fd.addParam("ButtonDownloadText", "Download files");
	fd.addParam("ProcessSubfolders", "true");
	fd.addParam("ButtonDownloadImageFormat", "Width=101;Height=19;UrlNormal=/control_panel/images/btn_download.jpg;BackgroundColor='.$Rec_Style_1.'");
	fd.addEventListener("DownloadStep", "FileDownload_'.$count.'");
	fd.writeHtml();
				</script></div>';
			//} else {
			//	$records[$count][11] = "&nbsp;";
			//}
	} while ($row_get_info = mysql_fetch_assoc($get_info)); 
}

mysql_free_result($get_info);
build_record_3_table('Invoices','Invoices',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,false,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,false,false,false,false,false);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
