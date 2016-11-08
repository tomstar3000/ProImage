<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_phone.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	$items = array();
	$items = $_POST['Photographers_items'];
	foreach ($items as $key => $value){
		$delInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$delInfo->mysql("UPDATE `photo_photographers` SET `photo_use` = 'n' WHERE `photo_id` = '$value';");
		$delInfo->mysql("OPTIMIZE TABLE `photo_photographers`;");
	}
}

if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Customer Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Photographer Name"){
	$Sort_val = " ORDER BY `photo_fname` ".$Order.", `photo_lname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Address"){
	$Sort_val = " ORDER BY `photo_city` ".$Order.",`photo_state` ".$Order.",`photo_zip` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Phone Number"){
	$Sort_val = " ORDER BY `photo_phone` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "E-mail"){
	$Sort_val = " ORDER BY `photo_email` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$HotMenu = "Photo,Photo:query"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="BsnPhotogList">
  <div>Photographer List</div>
</h1>
<div id="HdrLinks"><a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_action_form').submit();" onclick="javascript:if(confirm('Are you sure you want to remove these Photographers')) return true; else return false;" onmouseover="window.status='Remove Photographers'; return true;" onmouseout="window.status=''; return true;" title="Remove Photographers" class="BtnDel">Remove Photographers</a><a href="#" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('','<? echo implode(",",$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false;" onmouseover="window.status='Add New Photographer'; return true;" onmouseout="window.status=''; return true;" title="Add New Photographer" class="BtnAdd">Add New Photographer</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<? 
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT * FROM `photo_photographers` WHERE `photo_use` = 'y' AND `cust_id` = '$CustId' ".$Sort_val.";"); ?>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': case 'Photo': echo 'Green'; break;
	default: echo 'Red'; break; } ?>">
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll','TableRecords1');" /></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Photographer Name,<? echo ($Sort == "Photographer Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Photographer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Photographer Name">Photographer Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Address,<? echo ($Sort == "Address" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Address'; return true;" onmouseout="window.status=''; return true;" title="Sort By Address">Address</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Phone Number,<? echo ($Sort == "Phone Number" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Phone Number'; return true;" onmouseout="window.status=''; return true;" title="Sort By Phone Number">Phone Number</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','E-mail,<? echo ($Sort == "E-mail" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By E-mail'; return true;" onmouseout="window.status=''; return true;" title="Sort By E-mail">E-mail</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,2)).",".$r['photo_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><input type="checkbox" name="Photographers_items[]" id="Photographers_items_<? echo ($k+1); ?>" value="<? echo $r['photo_id']; ?>" /></td>
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['photo_lname'].", ".$r['photo_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['photo_lname'].", ".$r['photo_fname']); ?>"><? echo $r['photo_lname'].", ".$r['photo_fname']; ?></a></td>
        <td><? echo $r['photo_city']." ".$r['photo_state']." ".$r['photo_zip']; ?></td>
        <td><? echo phone_number($r['photo_phone']); ?></td>
        <td><? echo $r['photo_email']; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['photo_lname'].", ".$r['photo_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['photo_lname'].", ".$r['photo_fname']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
