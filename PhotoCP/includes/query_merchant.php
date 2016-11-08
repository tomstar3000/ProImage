<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_phone.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") {
	$items = $_POST['Merch_items'];
	if(is_array($items) && count($items)>0){
		foreach($items as $r){
			$rmvInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$rmvInfo->mysql("UPDATE `photo_invoices` SET `inv_use` = 'n' WHERE `invoice_id` = '".clean_variable($r,true)."';");
			$rmvInfo->mysql("OPTIMIZE TABLE `photo_invoices`;");
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
	$Sort_val = " ORDER BY `inv_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Customer Name"){
	$Sort_val = " ORDER BY `lname` ".$Order.", `fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Charge Amount"){
	$Sort_val = " ORDER BY `amount` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Card Type"){
	$Sort_val = " ORDER BY `cc_type_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Process Date"){
	$Sort_val = " ORDER BY `cust_phone` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Errors"){
	$Sort_val = " ORDER BY `cust_email` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$HotMenu = "Busn,Mrch:query"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="BsnMerchant">
  <div>Pending Transactions</div>
</h1>
<div id="HdrLinks"><a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_action_form').submit();" onclick="javascript:if(confirm('Are you sure you want to remove invoice(s)')) return true; else return false;" onmouseover="window.status='Remove Invoice'; return true;" onmouseout="window.status=''; return true;" title="Remove Invoice" class="BtnDel">Remove Invoice</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a> </div>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Mrch"; ?>','add','','');" id="BtnTransNew">New Transaction</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Mrch"; ?>','query','','');" id="BtnTransPend" class="NavSel">Pending Transactions</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Past"; ?>','query','','');" id="BtnTransPast">Past Transactions</a><br clear="all" />
</div>
<? 
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `photo_invoices`.*, `billship_cc_types`.`cc_type_name` FROM `photo_invoices`
								LEFT JOIN `billship_cc_types`
									ON (`billship_cc_types`.`cc_type_id` = `photo_invoices`.`cc_type`
										OR `billship_cc_types`.`cc_type_id` IS NULL)
								WHERE `cust_id` = '$CustId' AND `inv_use` = 'y' AND (`process` = 'n' OR (`process` = 'y' AND `fail` = 'y')) ".$Sort_val.";"); ?>
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
        <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll','TableRecords1');" /></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Customer Name,<? echo ($Sort == "Invoice" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Invoice'; return true;" onmouseout="window.status=''; return true;" title="Sort By Invoice">Invoice</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Customer Name,<? echo ($Sort == "Customer Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Customer Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Charge Amount,<? echo ($Sort == "Charge Amount" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Charge Amount'; return true;" onmouseout="window.status=''; return true;" title="Sort By Charge Amount">Charge Amount</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Card Type,<? echo ($Sort == "Card Type" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Card Type'; return true;" onmouseout="window.status=''; return true;" title="Sort By Card Type">Card Type</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Process Date,<? echo ($Sort == "Process Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort Process Date'; return true;" onmouseout="window.status=''; return true;" title="Sort By Process Date">Process Date</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Errors,<? echo ($Sort == "Errors" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort Errors'; return true;" onmouseout="window.status=''; return true;" title="Sort By Errors">Errors</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,2)).",".$r['invoice_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><input type="checkbox" name="Merch_items[]" id="Merch_items_<? echo ($k+1); ?>" value="<? echo $r['invoice_id']; ?>" /></td>
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice <? echo str_replace("'","\'",$r['inv_num']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice <? echo str_replace("'","\'",$r['inv_num']); ?>"><? echo $r['inv_num']; ?></a></td>
        <td><? echo $r['lname'].", ".$r['fname']; ?></td>
        <td><? echo "$".number_format($r['amount'],2,".",","); ?></td>
        <td><? echo $r['cc_type_name']; ?></td>
        <td><? echo format_date($r['chrg_date'],"Short",false,true,false); ?></td>
        <td><? echo ($r['fail']=='y')? "1":"0"; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice <? echo str_replace("'","\'",$r['inv_num']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice <? echo str_replace("'","\'",$r['inv_num']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
