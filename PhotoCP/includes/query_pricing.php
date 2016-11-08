<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	$items = array();
	$items = $_POST['Pricing_items'];

	foreach ($items as $key => $value){
		$delInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
    $delInfo->mysql("SELECT cust_id FROM `photo_event_price` WHERE `photo_event_price_id` = '$value';");
    $getInfo = $delInfo->Rows();
    $IS_AVENET_DEFAULT = ($getInfo[0]['cust_id'] == 0) ? true : false;

    if ($IS_AVENET_DEFAULT) {
        $delInfo->mysql("INSERT IGNORE INTO `photo_event_price_cust_del` (`photo_event_price_id`, `cust_id`) VALUES ('$value', '$CustId');");
    } else {
        $delInfo->mysql("UPDATE `photo_event_price` SET `photo_price_use` = 'n' WHERE `photo_event_price_id` = '$value';");
        $delInfo->mysql("OPTIMIZE TABLE `photo_event_price`;");
    }
	}
}
else if(isset($_POST['Controller']) && $_POST['Controller'] == "Clone"){
  $items = array();
  $items = $_POST['Pricing_items'];
  
  if (count($items) > 0) {
    foreach ($items as $key => $value) {
      $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
      $getInfo->mysql("SELECT `photo_event_price_id`, `cust_id`, `price_name`, `photo_price`, `photo_color`, `photo_blk_wht`, `photo_sepia`, `photo_price_use`
                FROM `photo_event_price` 
                WHERE `photo_event_price_id` = '$value';");

      $getInfo = $getInfo->Rows();
      $getInfo = $getInfo[0];

      $cloneInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
      $cloneStr = "INSERT INTO `photo_event_price` (`cust_id`, `price_name`, `photo_price`, `photo_color`, `photo_blk_wht`, `photo_sepia`, `photo_price_use`) 
                                            VALUES ('$CustId','" . preg_replace('/ \(Clone\)/', '', $getInfo['price_name']) . " (Clone)','" . $getInfo['photo_price'] . "','" . $getInfo['photo_color'] . "','" . $getInfo['photo_blk_wht'] . "','" . $getInfo['photo_sepia'] . "','" . $getInfo['photo_price_use'] . "');";
      $cloneInfo->mysql( $cloneStr );
    }
  }
}
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Pricing Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Pricing Name"){
	$Sort_val = " ORDER BY `price_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$HotMenu = "Busn,Prcn:query"; $Key = array_search($HotMenu, $StrArray);
?>

<h1 id="HdrType2" class="BsnPrcPrds">
  <div>Pricing Group</div>
</h1>
<div id="HdrLinks">
  <a href="javascript:document.getElementById('Controller').value = 'Clone'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Clone Pricing Group'; return true;" onmouseout="window.status=''; return true;" title="Clone Pricing Group" class="BtnClone">Clone Pricing Group</a>
  <a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_action_form').submit();" onclick="javascript:if(confirm('Are you sure you want to remove these Pricing Group')) return true; else return false;" onmouseover="window.status='Remove Pricing Group'; return true;" onmouseout="window.status=''; return true;" title="Remove Pricing Group" class="BtnDel">Remove Pricing Group</a>
  <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false'; set_form('','<? echo implode(",",$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false;" onmouseover="window.status='Add New Pricing Group'; return true;" onmouseout="window.status=''; return true;" title="Add New Pricing Group" class="BtnAdd">Add New Pricing Group</a> 
  <a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<?
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `photo_event_price`.* 
                  FROM `photo_event_price` 
                  LEFT OUTER JOIN `photo_event_price_cust_del` 
                    ON (`photo_event_price_cust_del`.`photo_event_price_id` = `photo_event_price`.`photo_event_price_id`
                       AND `photo_event_price_cust_del`.`cust_id` = '$CustId') 
                  WHERE (`photo_event_price`.`cust_id` = '$CustId' 
                    OR `photo_event_price`.`cust_id` = '0') 
                    AND `photo_event_price`.`photo_price_use` = 'y' 
                    AND `photo_event_price_cust_del`.`photo_event_price_id` IS NULL".$Sort_val.";");
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
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Pricing Name,<? echo ($Sort == "Pricing Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Pricing Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Pricing Name">Pricing Name</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",$path).",".$r['photo_event_price_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><input type="checkbox" name="Pricing_items[]" id="Pricing_items_<? echo ($k+1); ?>" value="<? echo $r['photo_event_price_id']; ?>" /></td>
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit <? echo str_replace("'","\'",$r['price_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit <? echo str_replace("'","\'",$r['price_name']); ?>"><? echo ((strlen($r['price_name']) > 30) ?  substr($r['price_name'],0,30)."..." : $r['price_name']); ?></a></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['price_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['price_name']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
