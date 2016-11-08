<? if(!isset($r_path)){$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++ )$r_path .= "../";}
$EId = $path[2];
include $r_path.'security.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	$items = array();
	$items = $_POST['Message_Board_items'];
	foreach ($items as $key => $value){
		$FavId = explode(".",$value);
		if(is_array($FavId)){
			$MsgId = $FavId[1];
			$FavId = $FavId[0];
		} else {
			$MsgId = "";
			$FavId = $FavId;
		}
		$rmvInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$rmvInfo->mysql("DELETE FROM `photo_cust_favories_message` WHERE `fav_message_id` = '$MsgId';");
		$rmvInfo->mysql("OPTIMIZE TABLE `photo_cust_favories_message`;");
		$rmvInfo->mysql("SELECT `fav_id` FROM `photo_cust_favories_message` WHERE `fav_id` = '$FavId';");
		if($rmvInfo->TotalRows() == 0 && $FavId != 0){
			$rmvInfo->mysql("DELETE FROM `photo_cust_favories` WHERE `fav_id` = '$FavId';");
			$rmvInfo->mysql("OPTIMIZE TABLE `photo_cust_favories`;");
		}
	}
}
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "E-mail";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "E-mail"){
	$Sort_val = " ORDER BY `fav_email` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Date"){
	$Sort_val = " ORDER BY `fav_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} ?>

<h1 id="HdrType2" class="<? switch($path[0]){
	case "Evnt": echo "MsgBrdEvnt"; break;
	case "Clnt": echo "MsgBrdClnt"; break;
} ?>">
  <div>Message Board</div>
</h1>
<div id="HdrLinks"><a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_action_form').submit();" onclick="javascript:if(confirm('Are you sure you want to remove these entries')) return true; else return false;" onmouseover="window.status='Remove Message Board Entry'; return true;" onmouseout="window.status=''; return true;" title="Remove Message Board Entry" class="BtnDel">Remove Message Board Entry</a><a href="#" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('','<? echo implode(",",$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false;" onmouseover="window.status='Add New Message Board Entry'; return true;" onmouseout="window.status=''; return true;" title="Add New Message Board Entry" class="BtnAdd">Add New Message Board Entry</a></div>
<?
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `event_num` FROM `photo_event` WHERE `event_id` = '$EId' LIMIT 0,1;");
$getInfo = $getInfo->Rows();
$FavCode = $getInfo[0]['event_num'].$CHandle;

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `photo_cust_favories`.*, `photo_cust_favories_message`.`fav_message`, `photo_cust_favories_message`.`fav_message_id`
	FROM `photo_cust_favories`
	LEFT JOIN `photo_cust_favories_message`
		ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
			OR `photo_cust_favories_message`.`fav_id` IS NULL)
	WHERE `fav_code` = '$FavCode'".$Sort_val.";");
?>
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
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','E-mail,<? echo ($Sort == "E-mail" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By E-mail'; return true;" onmouseout="window.status=''; return true;" title="Sort By E-mail">E-mail</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Date'; return true;" onmouseout="window.status=''; return true;" title="Sort By Date">Date</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",$path).",".$r['fav_id'].".".$r['fav_message_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><input type="checkbox" name="Event_Notifications_items[]" id="Event_Notifications_items_<? echo ($k+1); ?>" value="<? echo $r['fav_id'].".".$r['fav_message_id']; ?>" /></td>
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit <? echo str_replace("'","\'",$r['fav_email']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit <? echo str_replace("'","\'",$r['fav_email']); ?>"><? echo ((strlen($r['fav_email']) > 30) ?  substr($r['fav_email'],0,30)."..." : $r['fav_email']); ?></a></td>
        <td><? echo format_date($r['fav_date'],"Standard",false,true,false); ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['fav_email']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['fav_email']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
