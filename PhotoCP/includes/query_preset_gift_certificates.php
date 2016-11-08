<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++ )$r_path .= "../";}
$EId = $path[2];
include $r_path.'security.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && isset($_POST['Discount_items'])){
	$items = array();
	$items = $_POST['Discount_items'];
	$getAction = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 'g';");
	$getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");
	foreach ($items as $key => $value){
		$getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$value','g');");
	}
}
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Code Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Gift Certificate Name"){
	$Sort_val = " ORDER BY `prod_discount_codes`.`disc_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "E-mail"){
	$Sort_val = " ORDER BY `prod_discount_codes`.`disc_email` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Amount"){
	$Sort_val = " ORDER BY `prod_discount_codes`.`disc_price` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();'; ?>
<h1 id="HdrType2" class="<? switch($path[0]){
	case 'Clnt': echo 'EvntMarket'; break;
	case 'Busn': echo 'BsnPreDiscCodes'; break;
	default: echo 'EvntMarket'; break; } ?>">
  <div>Gift Certificate List</div>
</h1>
<div id="HdrLinks"><a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Gift Certificates'; return true;" onmouseout="window.status=''; return true;" title="Save Gift Certificates" class="BtnSave">Save</a></div>
<? 
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `prod_discount_codes`.* , `photo_event_disc`.`event_disc_id`
	FROM `prod_discount_codes` 
	LEFT JOIN `photo_event_disc`
		ON ((`prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
			AND `photo_event_disc`.`event_id` = '$EId')
			OR `photo_event_disc`.`disc_id` IS NULL)
	WHERE `prod_discount_codes`.`cust_id` = '$CustId' AND `prod_discount_codes`.`disc_use` = 'y' AND `prod_discount_codes`.`disc_type` = 'g'
	".$Sort_val.";"); ?>

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
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll','Records');" /></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Gift Certificate Name,<? echo ($Sort == "Gift Certificate Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Gift Certificate Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Gift Certificate Name">Gift Certificate Name</a></th>
        
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','E-mail,<? echo ($Sort == "E-mail" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By E-mail'; return true;" onmouseout="window.status=''; return true;" title="Sort By E-mail">E-mail</a></th>
        
        <th class="R"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Amount,<? echo ($Sort == "Amount" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Amount'; return true;" onmouseout="window.status=''; return true;" title="Sort By Amount">Amount</a></th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,2)).",".$r['event_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><input type="checkbox" name="Discount_items[]" id="Discount_items_<? echo ($k+1); ?>" value="<? echo $r['disc_id']; ?>"<? if($r['event_disc_id'] != NULL) echo ' checked="checked"'; ?> /></td>
        <td><? echo ((strlen($r['disc_name']) > 30) ?  substr($r['disc_name'],0,30)."..." : $r['disc_name']); ?></td>
        <td><? echo ((strlen($r['disc_email']) > 30) ?  substr($r['disc_email'],0,30)."..." : $r['disc_email']); ?></td>
        <td class="R"><? echo "$".number_format($r['disc_price'],2,".",","); ?></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>