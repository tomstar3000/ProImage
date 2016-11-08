<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_record_5_table.php');
require_once($r_path.'scripts/fnct_format_date.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Send to Lab")require_once($r_path.'scripts/del_photo_invoices.php');
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Invoice";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Invoice"){
	$Sort_val = " ORDER BY `invoice_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Date"){
	$Sort_val = " ORDER BY `invoice_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Total"){
	$Sort_val = " ORDER BY `invoice_total` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Name"){
	$Sort_val = " ORDER BY `cust_fname`, `cust_lname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Event"){
	$Sort_val = " ORDER BY `event_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Number"){
	$Sort_val = " ORDER BY `event_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Img"){
	$Sort_val = " ORDER BY `count_image_ids` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
if ($path[1] != "Open"){
	$Date = date("Y-m-d H:i:s");
	$StartDate = date("m/d/Y", mktime(0,0,0,date("m"),1,date("Y")));
	$EndDate = date("m/d/Y", mktime(0,0,0,(date("m")+1),0,date("Y")));
	$Start = get_variable($_POST['From'], get_variable($_GET['From'], $StartDate, true), true);
	$End = get_variable($_POST['To'], get_variable($_GET['To'], $EndDate, true), true);
	$Start = date("Y-m-d H:i:s", mktime(0,0,0,substr($Start,0,2),substr($Start,3,2),substr($Start,6,4)));
	$End = date("Y-m-d H:i:s", mktime(0,0,0,substr($End,0,2),(substr($End,3,2)+1),substr($End,6,4)));
	
	$query_get_oldest= "(
		SELECT `invoice_paid_date` 
		FROM `orders_invoice`
		INNER JOIN `orders_invoice_photo`
			ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		WHERE `photo_event_images`.`cust_id` = '$CustId'
		ORDER BY `invoice_paid_date` ASC
			) UNION (
		SELECT `invoice_paid_date` 
		FROM `orders_invoice`
		INNER JOIN `orders_invoice_border`
			ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id`
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		WHERE `photo_event_images`.`cust_id` = '$CustId'
		ORDER BY `invoice_paid_date` ASC	
	)
	LIMIT 0,1";
	$get_oldest = mysql_query($query_get_oldest, $cp_connection) or die(mysql_error());
	$row_get_oldest = mysql_fetch_assoc($get_oldest);
	
	$year = substr($row_get_oldest['invoice_paid_date'],0,4);
	if($year == 0) $year = date("Y");
	$month = substr($row_get_oldest['invoice_paid_date'],5,2);
}
if ($path[1] != "Open"){
	$andthis = " AND `orders_invoice`.`invoice_paid_date` >= '$Start' AND `orders_invoice`.`invoice_paid_date` <= '$End'";
} else {
	$andthis = "";
}
?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Invoices</h2>
  <p id="Search"><a href="#" onclick="javascript:set_form('','<? echo implode(",",$path); ?>','search','<? echo $sort; ?>','<? echo $rcrd; ?>');">Invoice Search</a></p>
</div>
<? if ($path[1] != "Open"){ ?>
<script type="text/javascript">
function set_time(date_val){
	date_val = date_val.split("[-]");
	// yyyy-mm-dd
	// 0123456789
	date_val[0] = date_val[0].substr(5,2)+"/"+date_val[0].substr(8,2)+"/"+date_val[0].substr(0,4);
	date_val[1] = date_val[1].substr(5,2)+"/"+date_val[1].substr(8,2)+"/"+date_val[1].substr(0,4);
	document.getElementById('From').value = date_val[0];
	document.getElementById('To').value = date_val[1];
	document.getElementById('form_action_form').submit();
}
</script>
<div id="Report_Date">
 <p>Yearly Report
  <select name="Report_Year" id="Report_Year" onchange="set_time(this.value);">
   <option value="0">-- Select Year --</option>
   <? for($n = intval($year); $n<=date("Y"); $n++){ ?>
   <option value="<? echo date("Y-m-d", mktime(0,0,0,1,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,1,0,($n+1))); ?>"><? echo $n; ?></option>
   <? } ?>
  </select>
  Monthly Report
  <select name="Report_Month" id="Report_Month" onchange="set_time(this.value);">
   <option value="0">-- Select Month --</option>
   <? for($n = intval($year); $n<=date("Y"); $n++){
				if($n == $year) $z = $month; else $z = 1;
				if($year == date("Y"))$end = date("m"); else $end = 12;
				for($z = intval($z); $z<=$end; $z++){?>
   <option value="<? echo date("Y-m-d", mktime(0,0,0,$z,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,($z+1),0,$n)); ?>"><? echo date("M",mktime(0,0,0,$z,1,date("Y")))." (".$n.")"; ?></option>
   <? } }?>
  </select>
  <br />
  Select Report From:
  <input type="text" name="From" id="From" value="<? echo format_date($Start,"Dash",false,true,false); ?>" />
  To:
  <input type="text" name="To" id="To" value="<? echo format_date($End,"Dash",false,true,false); ?>" />
  <input type="submit" name="Submit" id="Submit" value="Select Invoices" />
 </p>
</div>
<? } ?>
<div>
  <?
	if($path[1]=="Open"){
$query_get_info = "SELECT *, SUM(`count_image_ids`) AS `count_image_ids`
	FROM (
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` 
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_photo` 
				ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE `photo_event_images`.`cust_id` = '$CustId' 
				AND `invoice_accepted` = 'n' 
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable1`
	UNION 
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_border`.`image_id` ) AS `count_image_ids` 
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_border` 
				ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE `photo_event_images`.`cust_id` = '$CustId' 
				AND `invoice_accepted` = 'n' 
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable2`
) AS `MainTable`
GROUP BY `invoice_id`".$Sort_val; } else { 
$query_get_info = "SELECT *, SUM(`count_image_ids`) AS `count_image_ids`
	FROM (
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` 
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_photo` 
				ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE `photo_event_images`.`cust_id` = '$CustId'".$andthis."
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable1`
	UNION 
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_border`.`image_id` ) AS `count_image_ids` 
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_border` 
				ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE `photo_event_images`.`cust_id` = '$CustId'".$andthis."
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable2`
) AS `MainTable`
GROUP BY `invoice_id`".$Sort_val; }
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

$records = array();
$headers = array();
$sortheaders = array();
$sortheaders[0] = $Sort;
$sortheaders[1] = $Order;
$div_data = false;
$drop_downs = false;
$headers[0] = "Invoice";
$headers[1] = "Date";
$headers[2] = "Total";
$headers[3] = "Name";
$headers[4] = "Event";
$headers[5] = "Number";
$headers[6] = "Img";
$headers[7] = "&nbsp;";

do{	
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_info['invoice_id'];
	$records[$count][2] = $row_get_info['invoice_num'];
	$records[$count][3] = format_date($row_get_info['invoice_date'],"Dash",false,true,false);
	$records[$count][4] = "$".number_format($row_get_info['invoice_total'],2,".",",");
	$records[$count][5] = $row_get_info['cust_fname']." ".$row_get_info['cust_lname'];
	$records[$count][6] = $row_get_info['event_name'];
	$records[$count][7] = $row_get_info['event_num'];
	$records[$count][8] = $row_get_info['count_image_ids'];
	$records[$count][9] = '<a href="/checkout/invoice.php?invoice='.$row_get_info['invoice_enc'].'" target="_blank">View</a>';
	
} while ($row_get_info = mysql_fetch_assoc($get_info)); 
mysql_free_result($get_info);
$oldcont = $cont;
$cont .= "&From=".format_date($Start,"Dash",false,true,false)."&To=".format_date(date("Y-m-d H:i:s", mktime(0,0,0,substr($End,5,2),(substr($End,8,2)-1),substr($End,0,4))),"Dash",false,true,false);
if($path[1]=="Open"){
build_record_5_table('Invoices','Invoices',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Send Invoice(s) to the Lab','Send to Lab','Send to Lab','btn_lab_2.jpg')),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5, true, 'btn_select_all.jpg', 'btn_deselect.jpg');
} else {
build_record_5_table('Invoices','Invoices',$headers,$sortheaders,$records,$div_data,$drop_downs,false,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5,false,false);
}
$cont = $oldcont;
if(is_array($rcrd))$rcrd = implode(",",$rcrd);

?>
</div>
