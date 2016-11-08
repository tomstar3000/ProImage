<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
require_once $r_path.'scripts/fnct_clean_entry.php';
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
	$Sort = "Invoice Number";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Invoice Number"){
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
} else if ($Sort == "Customer Name"){
	$Sort_val = " ORDER BY `cust_fname` ".$Order.", `cust_lname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}  else if ($Sort == "Number of Images"){
	$Sort_val = " ORDER BY `count_image_ids` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} 

$Inv = (isset($_POST['Invoice_Number'])) ? clean_variable($_POST['Invoice_Number'],true) : '';
$SDate = (isset($_POST['Start_Date'])) ? clean_variable($_POST['Start_Date'],true) : '';
if($SDate != "" && $SDate != "--"){
	$SDate = date("Y-m-d H:i:s", mktime(0,0,0,substr($SDate,0,2),substr($SDate,3,2),substr($SDate,6,4)));
	$query_start = " AND `orders_invoice`.`invoice_date` >= '".$SDate."'";
} else {
	//$SDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
	//$query_start = " AND `orders_invoice`.`invoice_date` >= '".$SDate."'";
}
$EDate = (isset($_POST['End_Date'])) ? clean_variable($_POST['End_Date'],true) : '';
if($EDate != ""){
	$EDate = date("Y-m-d H:i:s", mktime(0,0,-1,substr($EDate,0,2),(substr($EDate,3,2)+1),substr($EDate,6,4)));
	$query_end = " AND `orders_invoice`.`invoice_date` <= '".$EDate."'";
} else {
	//$EDate = date("Y-m-d");
}
$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';

define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'false\'; document.getElementById(\'form_action_form\').submit();'; ?>

<h1 id="HdrType2" class="ClntInvClnt">
  <div>Search Invoice List</div>
</h1>
<div id="HdrLinks"><a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Search'; return true;" onmouseout="window.status=''; return true;" title="Search" class="BtnSearch">Search</a></div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn2"> <span>
    <label for="Invoice_Number" class="CstmFrmElmntLabel">Invoice Number</label>
    <input type="text" name="Invoice Number" id="Invoice_Number" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Invoice Number'; return true;" onmouseout="window.status=''; return true;" title="Invoice Number" class="CstmFrmElmntInput" value="<? echo $Inv; ?>" />
    </span><span>
    <label for="Email" class="CstmFrmElmntLabel">Customer E-mail:</label>
    <input type="text" name="Email" id="Email" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Email'; return true;" onmouseout="window.status=''; return true;" title="Email" class="CstmFrmElmntInput" value="<? echo $Email; ?>" />
    </span><span>
    <label for="Start_Date" class="CstmFrmElmntLabel">From Date</label>
    <input type="text" name="Start Date" id="Start_Date" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Start Date'; return true;" onmouseout="window.status=''; return true;" title="Start Date" class="CstmFrmElmntInput" value="<? if($SDate != "" && $SDate != "--") echo substr($SDate,5,2)."/".substr($SDate,8,2)."/".substr($SDate,0,4); ?>" />
    (mm/dd/yyyy)
    <label for="End_Date" class="CstmFrmElmntLabel">To Date</label>
    <input type="text" name="End Date" id="End_Date" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='End Date'; return true;" onmouseout="window.status=''; return true;" title="End Date" class="CstmFrmElmntInput" value="<? if($EDate != "" && $EDate != "--") echo date("m/d/Y", mktime(0,0,0,substr($EDate,5,2),substr($EDate,8,2)-1,substr($EDate,0,4))); ?>" />
    (mm/dd/yyyy) </span><span>
    <label for="First_Name" class="CstmFrmElmntLabel">Customer's First Name</label>
    <input type="text" name="First Name" id="First_Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" value="<? echo $FName; ?>" />
    <label for="Last_Name" class="CstmFrmElmntLabel">Customer's Last Name</label>
    <input type="text" name="Last Name" id="Last_Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" value="<? echo $LName; ?>" />
    </span><br clear="all" />
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? 
if($Inv != '' || $SDate != '' || $EDate != '' || $FName != '' || $LName != '' || $Email != ''){
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `photo_event_images`.`cust_id` = '$CustId' AND (`cust_customers`.`cust_fname` LIKE '%".$FName."%' AND `cust_customers`.`cust_lname` LIKE '%".$LName."%' AND `orders_invoice`.`invoice_num` LIKE '%".$Inv."%' AND `cust_customers`.`cust_email` LIKE '%".$Email."%'".$query_start.$query_end.") GROUP BY `orders_invoice`.`invoice_id` ORDER BY `invoice_num` ASC;"); ?>
<div id="RecordTable" class="Yellow">
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Invoice Number,<? echo ($Sort == "Invoice Number" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Invoice Number'; return true;" onmouseout="window.status=''; return true;" title="Sort By Invoice Number">Invoice Number</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Date,<? echo ($Sort == "Date" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Dates'; return true;" onmouseout="window.status=''; return true;" title="Sort By Date">Date</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Total,<? echo ($Sort == "Total" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Total'; return true;" onmouseout="window.status=''; return true;" title="Sort By Total">Total</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Customer Name,<? echo ($Sort == "Customer Name" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Customer Name</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Number of Images,<? echo ($Sort == "Number of Images" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Number of Images'; return true;" onmouseout="window.status=''; return true;" title="Sort By Number of Images">Number of Images</a></th>
        <th>&nbsp;</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",$path).",".$r['invoice_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice <? echo $r['invoice_num']; ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice <? echo $r['invoice_num']; ?>"><? echo $r['invoice_num']; ?></a></td>
        <td><? echo format_date($r['invoice_date'],"Short",false,true,false); ?></td>
        <td><? echo "$".number_format($r['invoice_total'],2,".",","); ?></td>
        <td><? echo $r['cust_fname']." ".$r['cust_lname']; ?></td>
        <td><? echo $r['count_image_ids']; ?></td>
        <td><? echo '<a href="/checkout/invoice.php?invoice='.$r['invoice_enc'].'" target="_blank">View</a>'; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice <? echo $r['invoice_num']; ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice <? echo $r['invoice_num']; ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
<? } ?>
