<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
?>

<h1 id="HdrType2-5" class="ClntSpclDate">
  <div>Special Dates</div>
</h1>
<div id="HdrLinks">
  <a href="javascript:document.getElementById('Controller').value = 'EvntDelete'; document.getElementById('form_action_form').submit();" onclick="javascript:if(confirm('Are you sure you want to remove these Special Events')) return true; else return false;" onmouseover="window.status='Remove Special Events'; return true;" onmouseout="window.status=''; return true;" title="Remove Special Events" class="BtnDel">Remove Special Events</a>
  <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('','<? echo implode(",",$path); ?>,SpclEvnts','add','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false;" onmouseover="window.status='Add New Special Event'; return true;" onmouseout="window.status=''; return true;" title="Add New Special Event" class="BtnAdd">Add New Special Event</a>
</div>
<div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('CstmrEvnt',this); return false;" onmouseover="window.status='Expand Client Processed Credit Cards'; return true;" onmouseout="window.status=''; return true;" title="Expand Client Processed Credit Cards">+</a></div>
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
if ($Sort !== "Special Event Name" && $Sort !== "Special Event Date" ){
	$Sort = "Special Event Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Special Event Name"){
	$Sort_val = " ORDER BY `cust_date_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Special Event Date"){
	$Sort_val = " ORDER BY `cust_date_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `cust_customers_special_date`.*
	FROM `cust_customers_special_date`
	LEFT JOIN `cust_customers`
		ON `cust_customers`.`cust_id` = `cust_customers_special_date`.`cust_id`
	WHERE `cust_fname` = '$temp_fname' 
		AND `cust_lname` = '$temp_lname' 
		AND `cust_email` = '$temp_email' 
	GROUP BY `cust_date_id`
	".$Sort_val.";"); ?>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>"> <a id="CstmrEvnt"></a>
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Special Event Name,<? echo ($Sort == "Special Event Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Name">Event Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Special ,<? echo ($Sort == "Special Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Date'; return true;" onmouseout="window.status=''; return true;" title="Sort By Date">Date</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",$path).",SpclEvnts,".$r['cust_date_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Special Date <? echo str_replace("'","\'",$r['cust_date_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Special Date <? echo str_replace("'","\'",$r['cust_date_name']); ?>"><? echo $r['cust_date_name']; ?></a></td>
        <td><? echo format_date($r['cust_date_date'],"Short",false,true,false); ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice <? echo str_replace("'","\'",$r['cust_date_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice <? echo str_replace("'","\'",$r['cust_date_name']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
