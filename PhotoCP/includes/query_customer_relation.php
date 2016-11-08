<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
?>

<h1 id="HdrType2-5" class="ClntRelation">
  <div>Client Relationships</div>
</h1>
<div id="HdrLinks">
  <a href="javascript:document.getElementById('Controller').value = 'RelDelete'; document.getElementById('form_action_form').submit();" onclick="javascript:if(confirm('Are you sure you want to remove these Relationships')) return true; else return false;" onmouseover="window.status='Remove Relationship'; return true;" onmouseout="window.status=''; return true;" title="Remove Relationship" class="BtnDel">Remove Relationship</a>
  <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('','<? echo implode(",",$path); ?>,Relat','add','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false;" onmouseover="window.status='Add New Relationship'; return true;" onmouseout="window.status=''; return true;" title="Add New Relationship" class="BtnAdd">Add New Relationship</a>
</div>
<div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('CstmrRel',this); return false;" onmouseover="window.status='Expand Relationships'; return true;" onmouseout="window.status=''; return true;" title="Expand Relationships">+</a></div>
<?
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
if ($Sort !== "Name" && $Sort !== "Relationship" ){
	$Sort = "Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Name"){
	$Sort_val = " ORDER BY `cust_lname` ".$Order." , `cust_fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Relationship"){
	$Sort_val = " ORDER BY `cust_rel_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `cust_customers_relationship`.`cust_rel_id`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_relationships`.`cust_rel_name`
	FROM `cust_customers_relationship`
	INNER JOIN `cust_customers`
		ON (
					(`cust_customers`.`cust_id` = `cust_customers_relationship`.`cust_id_2` AND `cust_customers_relationship`.`cust_id_2` != '$CId')
				OR 
					(`cust_customers`.`cust_id` = `cust_customers_relationship`.`cust_id` AND `cust_customers_relationship`.`cust_id` != '$CId')
				)
	INNER JOIN `cust_relationships`
		ON (
					(`cust_relationships`.`cust_rel_id` = `cust_customers_relationship`.`cust_rel_2` AND `cust_customers_relationship`.`cust_id` != '$CId')
				OR
					(`cust_relationships`.`cust_rel_id` = `cust_customers_relationship`.`cust_rel` AND `cust_customers_relationship`.`cust_id_2` != '$CId' )				
				)
	WHERE `cust_customers_relationship`.`cust_id` = '$CId' 
		OR `cust_customers_relationship`.`cust_id_2` = '$CId'
	GROUP BY `cust_customers_relationship`.`cust_rel_id`
	".$Sort_val.";"); ?>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>"> <a id="CstmrRel"></a>
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Name,<? echo ($Sort == "Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Name">Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Relationship,<? echo ($Sort == "Relationship" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Relationship'; return true;" onmouseout="window.status=''; return true;" title="Sort By Relationship">Relationship</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",$path).",Relat,".$r['cust_rel_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Relationship'; return true;" onmouseout="window.status=''; return true;" title="View Relationship"><? echo $r['cust_lname'].", ".$r['cust_fname']; ?></a></td>
        <td><? echo $r['cust_rel_name']; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Relationship'; return true;" onmouseout="window.status=''; return true;" title="View Relationship">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
