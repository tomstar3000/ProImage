<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
?>

<h1 id="HdrType2-5" class="ClntInvClnt">
  <div>Credit Card Processing</div>
</h1>

<div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('CstmrMrch',this); return false;" onmouseover="window.status='Expand Client Processed Credit Cards'; return true;" onmouseout="window.status=''; return true;" title="Expand Client Processed Credit Cards">+</a></div>
<?
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
if($path[2] == "SpcMrch"){
	$getInfo->mysql("SELECT `fname` AS `cust_fname` , `lname` AS `cust_lname` ,`email` AS `cust_email` FROM `photo_invoices` WHERE `invoice_id` = '$CId';");
} else {
	$getInfo->mysql("SELECT `cust_fname` ,`cust_lname` ,`cust_email` FROM `cust_customers` WHERE `cust_id` = '$CId';");
}
$getInfo = $getInfo->Rows();

$temp_fname = $getInfo[0]['cust_fname'];
$temp_lname = $getInfo[0]['cust_lname'];
$temp_email = $getInfo[0]['cust_email'];

if(isset($_POST['form_path']) || isset($_POST['path'])){
	$sort = (isset($_POST['form_sort'])) ? clean_variable($_POST['form_sort'],true) : ((isset($_POST['sort'])) ? clean_variable($_POST['sort'],true) : "");
} else {
	$sort = (isset($_GET['form_sort'])) ? clean_variable($_GET['form_sort'],true) : ((isset($_GET['sort'])) ? clean_variable($_GET['sort'],true) : "");
}

if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
}
if($Sort != "Processed Invoice" && $Sort != "Processed Customer Name" && $Sort != "Processed Charge Amount" && $Sort != "Processed Card Type" && $Sort != "Processed Process") {
	$Sort = "Processed Invoice";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Processed Invoice"){
	$Sort_val = " ORDER BY `inv_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Processed Customer Name"){
	$Sort_val = " ORDER BY `lname` ".$Order.", `fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Processed Charge Amount"){
	$Sort_val = " ORDER BY `amount` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Processed Card Type"){
	$Sort_val = " ORDER BY `cc_type_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Processed Process"){
	$Sort_val = " ORDER BY `process` ".$Order.", `refund` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} 

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `photo_invoices`.*, `billship_cc_types`.`cc_type_name`
	FROM `photo_invoices`
	LEFT JOIN `billship_cc_types`
		ON (`billship_cc_types`.`cc_type_id` = `photo_invoices`.`cc_type`
			OR `billship_cc_types`.`cc_type_id` IS NULL)
	WHERE `fname` = '$temp_fname' 
		AND `lname` = '$temp_lname' 
		AND `email` = '$temp_email' 
	GROUP BY `invoice_id`
	".$Sort_val.";"); ?>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>"> <a id="CstmrMrch"></a>
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Processed Invoice,<? echo ($Sort == "Processed Invoice" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Invoice'; return true;" onmouseout="window.status=''; return true;" title="Sort By Invoice">Invoice</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Processed Customer Name,<? echo ($Sort == "Processed Customer Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Customer Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Processed Charge Amount,<? echo ($Sort == "Processed Charge Amount" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Charge Amount'; return true;" onmouseout="window.status=''; return true;" title="Sort By Charge Amount">Charge Amount</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Processed Card Type,<? echo ($Sort == "Processed Card Type" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Card Type'; return true;" onmouseout="window.status=''; return true;" title="Sort By Card Type">Card Type</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Processed Process,<? echo ($Sort == "Processed Process" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort Processed'; return true;" onmouseout="window.status=''; return true;" title="Sort By Processed">Processed</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Busn,Past,".$r['invoice_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice <? echo str_replace("'","\'",$r['inv_num']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice <? echo str_replace("'","\'",$r['inv_num']); ?>"><? echo $r['inv_num']; ?></a></td>
        <td><? echo $r['lname'].", ".$r['fname']; ?></td>
        <td><? echo "$".number_format($r['amount'],2,".",","); ?></td>
        <td><? echo $r['cc_type_name']; ?></td>
        <td><? echo ($r['process']=='y')? (($r['refund']=='y')?"Refunded":'Yes'):"No"; ?></td>
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
