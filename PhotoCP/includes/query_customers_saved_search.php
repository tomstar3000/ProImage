<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_phone.php';

if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
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
	$Sort_val = " ORDER BY `cust_search_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Date"){
	$Sort_val = " ORDER BY `cust_search_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$HotMenu = "Clnt,SrchSvd:query"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="ClntSrchClnt">
  <div>Customer List</div>
</h1>
<div id="HdrLinks"> <a href="#" onclick="javascript:set_form('','<? echo implode(",",array_slice($path,0,2)); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Add Customer'; return true;" onmouseout="window.status=''; return true;" title="Add Customer" class="BtnAdd">Add Customer</a>

<a href="#" onclick="javascript:set_form('','<? echo implode(",",array_slice($path,0,1)); ?>,Search','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Customer Search'; return true;" onmouseout="window.status=''; return true;" title="Customer Search" class="BtnSearch">Customer Search</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Clnt"; ?>','query','','');" id="BtnClntAll"<? if($path[1]=="Clnt" && $cont!="add") echo ' class="NavSel"'; ?> title="View All Clients">View All Clients</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Clnt"; ?>','add','','');" id="BtnClntNew"<? if($path[1]=="Clnt" && $cont=="add") echo ' class="NavSel"'; ?> title="Create New Client">Create New Client</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Search"; ?>','query','','');" id="BtnClntSrch"<? if($path[1]=="Search") echo ' class="NavSel"'; ?> title="Search Clients">Search Clients</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",SrchSvd"; ?>','query','','');" id="BtnClntSrchSvd"<? if($path[1]=="SrchSvd") echo ' class="NavSel"'; ?> title="Saved Searches">Saved Searches</a><br clear="all" />
</div>
<? 
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT * FROM `photo_cust_svd_search` WHERE `cust_id` = '$CustId'
								".$Sort_val." ;"); ?>
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
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Name,<? echo ($Sort == "Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Date'; return true;" onmouseout="window.status=''; return true;" title="Sort By Date">Date</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Clnt,Search,".$r['cust_search_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_search_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_search_name']); ?>"><? echo $r['cust_search_name']; ?></a></td>
        <td><? echo format_date($r['cust_search_date'],"Short",false,true,false); ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_search_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_search_name']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>