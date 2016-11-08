<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Send to Lab"){
	$items = array();
	$items = $_POST['Invoices_items'];
	if(count($items)>0){
		foreach ($items as $k => $v){
			$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$updInfo->mysql("UPDATE `orders_invoice` SET `invoice_accepted` = 'p' WHERE `invoice_id` = '$v';");
		}
	}
}

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
	$Sort_val = " ORDER BY `cust_fname` ".$Order.", `cust_lname` ".$Order;
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
} else if ($Sort == "Imgs"){
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
	$End = date("Y-m-d H:i:s", mktime(0,0,-1,substr($End,0,2),(substr($End,3,2)+1),substr($End,6,4)));
	
	$getOld = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getOld->mysql("(
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
	LIMIT 0,1;");
	
	$getOld = $getOld->Rows();
	
	$year = substr($getOld[0]['invoice_paid_date'],0,4);
	if($year == 0) $year = date("Y");
	$month = substr($getOld[0]['invoice_paid_date'],5,2);
}
if ($path[1] != "Open"){
	$andthis = " AND `orders_invoice`.`invoice_paid_date` >= '$Start' AND `orders_invoice`.`invoice_paid_date` <= '$End'";
} else {
	$andthis = "";
}
?>

<h1 id="HdrType2" class="<? switch($path[0]){
	case 'Clnt': echo 'ClntInvClnt'; break;
	case 'Busn': switch($path[1]){
			case 'Open': echo 'BsnInvsOpen'; break;
			case 'All': echo 'BsnInvsAll'; break;
		} break;
	default: echo 'ClntInvClnt'; break; } ?>">
  <div>Invoices</div>
</h1>
<div id="HdrLinks">
  <? if($path[1]=="Open"){ ?>
  <a href="#" onclick="javascript:if(confirm('Are You Sure You Want to send invoices to the lab')) { document.getElementById('Controller').value = 'Send to Lab'; document.getElementById('form_action_form').submit(); } return false;" onmouseover="window.status='Send Orders to Lab'; return true;" onmouseout="window.status=''; return true;" title="Send Orders to Lab" class="BtnSndLab">Send to Lab</a>
  <? } ?>
  <a href="#" onclick="javascript:set_form('','<? echo implode(",",$path); ?>','search','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Search Orders'; return true;" onmouseout="window.status=''; return true;" title="Search Orders" class="BtnSearch">Search Orders</a> </div>
<? if ($path[1] != "Open"){ ?>
<script type="text/javascript">
function set_time(){
	if(parseInt(document.getElementById('Report_Year').value) != 0)	date_val = document.getElementById('Report_Year').value;
	else if(parseInt(document.getElementById('Report_Month').value) != 0)	date_val = document.getElementById('Report_Month').value;
	else return;
	date_val = date_val.split("[-]");
	date_val[0] = date_val[0].substr(5,2)+"/"+date_val[0].substr(8,2)+"/"+date_val[0].substr(0,4);
	date_val[1] = date_val[1].substr(5,2)+"/"+date_val[1].substr(8,2)+"/"+date_val[1].substr(0,4);
	document.getElementById('From').value = date_val[0]; document.getElementById('To').value = date_val[1];
	document.getElementById('form_action_form').submit(); }
</script>
<script type="text/javascript">
function changeHiddenDate($Tag,$Fld){
	$Year = document.getElementById($Tag+'_Year');
	$Month = document.getElementById($Tag+'_Month');
	$Day = document.getElementById($Tag+'_Day');
	document.getElementById($Fld).value = $Month.value+"/"+$Day.value+"/"+$Year.value;
}
</script>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn2"> <span>
    <label for="Report_Year" class="CstmFrmElmntLabel">Yearly Report</label>
    <select name="Report_Year" id="Report_Year" class="CstmFrmElmnt" onchange="javascript: set_time();" onmouseover="window.status='Invoices by Month'; return true;" onmouseout="window.status=''; return true;" title="Invoices by Month">
      <option value="0">-- Select Year --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){ ?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,1,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,1,0,($n+1))); ?>"><? echo $n; ?></option>
      <? } ?>
    </select>
    </span> <span>
    <label for="Report_Month" class="CstmFrmElmntLabel">Monthly Report</label>
    <select name="Report_Month" id="Report_Month" class="CstmFrmElmnt" onchange="javascript: set_time();" onmouseover="window.status='Invoices by Month'; return true;" onmouseout="window.status=''; return true;" title="Invoices by Month">
      <option value="0">-- Select Month --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){
				if($n == $year) $z = $month; else $z = 1;
				if($year == date("Y"))$end = date("m"); else $end = 12;
				for($z = intval($z); $z<=$end; $z++){?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,$z,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,($z+1),0,$n)); ?>"><? echo date("M",mktime(0,0,0,$z,1,date("Y")))." (".$n.")"; ?></option>
      <? } }?>
    </select>
    </span> <span>
    <label for="From" class="CstmFrmElmntLabel">Select Invoices From:</label>
    <div style="float:left; clear:none;">
      <select name="Start Month" id="Start_Month" class="CstmFrmElmnt88" onchange="changeHiddenDate('Start','From')" onmouseover="window.status='Month of Invoices'; return true;" onmouseout="window.status=''; return true;" title="Month of Invoices">
        <? $TDate = date("m",mktime(0,0,1,substr($Start,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="Start Day" id="Start_Day" class="CstmFrmElmnt53" onchange="changeHiddenDate('Start','From')" onmouseover="window.status='Day of Invoices'; return true;" onmouseout="window.status=''; return true;" title="Day of Invoices">
        <? $TDate = date("d",mktime(0,0,1,1,substr($Start,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="Start Year" id="Start_Year" class="CstmFrmElmnt64" onchange="changeHiddenDate('Start','From')" onmouseover="window.status='Year of Invoices'; return true;" onmouseout="window.status=''; return true;" title="Year of Invoices">
        <? $year = substr($getOldest[0]['invoice_paid_date'],0,4);
					if($year == 0) $year = date("Y");
					$TDate = date("Y",mktime(0,0,1,1,1,substr($Start,0,4)));
				for($n = intval($year); $n<=date("Y"); $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,$n)); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('ReleaseCalendar','Start_Year','Start_Month','Start_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="ReleaseCalendar">Calendar</a></div>
    <input type="hidden" name="From" id="From" value="<? echo format_date($Start,"Dash",false,true,false); ?>" />
    </span> <span>
    <label for="To" class="CstmFrmElmntLabel">To:</label>
    <div style="float:left; clear:none;">
      <select name="End Month" id="End_Month" class="CstmFrmElmnt88" onchange="changeHiddenDate('End','To')" onmouseover="window.status='Month of Invoices'; return true;" onmouseout="window.status=''; return true;" title="Month of Invoices">
        <? $TDate = date("m",mktime(0,0,1,substr($End,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="End Day" id="End_Day" class="CstmFrmElmnt53" onchange="changeHiddenDate('End','To')" onmouseover="window.status='Day of Invoices'; return true;" onmouseout="window.status=''; return true;" title="Day of Invoices">
        <? $TDate = date("d",mktime(0,0,1,1,substr($End,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="End Year" id="End_Year" class="CstmFrmElmnt64" onchange="changeHiddenDate('End','To')" onmouseover="window.status='Year of Invoices'; return true;" onmouseout="window.status=''; return true;" title="Year of Invoices">
        <? $year = substr($getOldest[0]['invoice_paid_date'],0,4);
					if($year == 0) $year = date("Y");
					$TDate = date("Y",mktime(0,0,1,1,1,substr($End,0,4)));
				for($n = intval($year); $n<=date("Y"); $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,$n)); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('ReleaseCalendar','End_Year','End_Month','End_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="ReleaseCalendar">Calendar</a></div>
    <input type="hidden" name="To" id="To" value="<? echo format_date($End,"Dash",false,true,false); ?>" />
    </span> <span>
    <input type="submit" name="Submit" id="Submit" value="Select Invoices" />
    </span><br clear="all" />
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } ?>
<div>
<? $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	if($path[1]=="Open"){
		$getInfo->mysql("SELECT *, SUM(`count_image_ids`) AS `count_image_ids`
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
GROUP BY `invoice_id`".$Sort_val.";");
 } else { 
 		$getInfo->mysql("SELECT *, SUM(`count_image_ids`) AS `count_image_ids`
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
GROUP BY `invoice_id`".$Sort_val.";");
} ?>
<script type="text/javascript">
function goCheckAll(ID1, ID2){
	var CheckAll = false; if(document.getElementById(ID1).checked == true) CheckAll = true;
	var Boxes = document.getElementById(ID2).getElementsByTagName('input'); var n = 1;
	while(n<Boxes.length){ Boxes[n].checked = CheckAll; n++; } }
</script>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>">
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <? if($path[1]=="Open"){ ?>
        <th> <input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll','TableRecords1');" />
        </th>
        <? } ?>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Invoice,<? echo ($Sort == "Invoice" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Invoice'; return true;" onmouseout="window.status=''; return true;" title="Sort By Invoice">Invoice</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Date'; return true;" onmouseout="window.status=''; return true;" title="Sort By Date">Date</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Total,<? echo ($Sort == "Total" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Order Total'; return true;" onmouseout="window.status=''; return true;" title="Sort By Order Total">Total</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Name,<? echo ($Sort == "Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Event,<? echo ($Sort == "Event" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Name">Event</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Number,<? echo ($Sort == "Number" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Number'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Number">Number</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Imgs,<? echo ($Sort == "Imgs" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Number of Images'; return true;" onmouseout="window.status=''; return true;" title="Sort By Number of Images">Imgs</a></th>
        <th>&nbsp;</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,2)).",".$r['invoice_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <? if($path[1]=="Open"){ ?>
        <td><input type="checkbox" name="Invoices_items[]" id="Invoices_items_<? echo ($k+1); ?>" value="<? echo $r['invoice_id']; ?>" />
        </td>
        <? } ?>
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Open Invoice <? echo $r['invoice_num']; ?>'; return true;" onmouseout="window.status=''; return true;" title="Open Invoice <? echo $r['invoice_num']; ?>"><? echo $r['invoice_num']; ?></a></td>
        <td><? echo format_date($r['invoice_date'],"Dash",false,true,false); ?></td>
        <td><? echo "$".number_format($r['invoice_total'],2,".",","); ?></td>
        <td><? echo $r['cust_fname']." ".$r['cust_lname']; ?></td>
        <td><? echo $r['event_name']; ?></td>
        <td><? echo $r['event_num']; ?></td>
        <td><? echo $r['count_image_ids']; ?></td>
        <td><? echo '<a href="/checkout/invoice.php?invoice='.$r['invoice_enc'].'" target="_blank">View</a>'; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Open Invoice <? echo $r['invoice_num']; ?>'; return true;" onmouseout="window.status=''; return true;" title="Open Invoice <? echo $r['invoice_num']; ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
