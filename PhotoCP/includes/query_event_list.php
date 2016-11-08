<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete") {
	$items = $_POST['Event_items'];
	if(is_array($items) && count($items)>0){
		foreach($items as $r){
			$rmvInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$rmvInfo->mysql("SELECT `event_use` FROM `photo_event` WHERE `event_id` = '".clean_variable($r,true)."';");
			$Use = $rmvInfo->Rows();
			if($Use[0]['event_use'] == 'n'){
				$rmvInfo->mysql("UPDATE `photo_event` SET `event_del` = 'y' WHERE `event_id` = '".clean_variable($r,true)."';");
			} else {
				$rmvInfo->mysql("UPDATE `photo_event` SET `event_use` = 'n' WHERE `event_id` = '".clean_variable($r,true)."';");
			}
			$rmvInfo->mysql("OPTIMIZE TABLE `photo_event`;");
		}
	}
} else if(isset($_POST['Controller']) && $_POST['Controller'] == "ReRelease") {
	$items = $_POST['Event_items'];
	if(is_array($items) && count($items)>0){
		foreach($items as $r){
			$rmvInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$rmvInfo->mysql("UPDATE `photo_event` SET `event_use` = 'y' WHERE `event_id` = '".clean_variable($r,true)."';");
			$rmvInfo->mysql("OPTIMIZE TABLE `photo_event`;");
		}
	}
}
if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Event Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Event Id"){
	$Sort_val = " ORDER BY `photo_event`.`event_id` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Event Name"){
	$Sort_val = " ORDER BY `photo_event`.`event_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Code"){
	$Sort_val = " ORDER BY `photo_event`.`event_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Photo"){
	$Sort_val = " ORDER BY `photo_photographers`.`photo_fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Link"){
	$Sort_val = " ORDER BY `photo_event`.`event_name` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Date"){
	$Sort_val = " ORDER BY `photo_event`.`event_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Ends"){
	$Sort_val = " ORDER BY `photo_event`.`event_end` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Grps"){
	$Sort_val = " ORDER BY `group_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Imgs"){
	$Sort_val = " ORDER BY `image_count` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Used"){
	$Sort_val = " ORDER BY `total_size` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Total"){
	$Sort_val = " ORDER BY `order_grand_total` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$HotMenu = "Evnt,Evnt:query"; $Key = array_search($HotMenu, $StrArray);
?>

<h1 id="HdrType2" class="UpcmngEvnt">
  <div>Event List</div>
</h1>
<div id="HdrLinks"><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<div>
  <h3>Notice! Once the Event End Date has passed. The Event will be placed in the Expired Event Section.</h3>
</div>
<? $getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$query = "SELECT 
  DISTINCT COUNT(`photo_event_images`.`image_id`) AS `image_count`,
  (SELECT count(`group_id`) 
    FROM `photo_event_group` 
    WHERE `photo_event_group`.`event_id` = `photo_event`.`event_id` 
      AND `photo_event_group`.`group_use` = 'y') AS `group_count`,
  

  ( SELECT SUM(`invoice_grand`) + SUM(`invoice_disc`) FROM (
      (
        SELECT `orders_invoice`.*, `photo_event_group`.`event_id`
        FROM `orders_invoice`
        INNER JOIN `orders_invoice_photo` 
          ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
        INNER JOIN `photo_event_images` 
          ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
        INNER JOIN `photo_event_group` 
          ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
        INNER JOIN `photo_event` 
          ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
        WHERE `photo_event`.`cust_id` = '$CustId'
          AND `photo_event`.`event_use` = 'y'
        GROUP BY `orders_invoice`.`invoice_id`
      ) UNION (
        SELECT `orders_invoice`.*, `photo_event_group`.`event_id`
        FROM `orders_invoice` 
        INNER JOIN `orders_invoice_border` 
          ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
        INNER JOIN `photo_event_images` 
          ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`
        INNER JOIN `photo_event_group` 
          ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
        INNER JOIN `photo_event` 
          ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
        WHERE `photo_event`.`cust_id` = '$CustId'
          AND `photo_event`.`event_use` = 'y'
        GROUP BY `orders_invoice`.`invoice_id`
      ) 
    ) AS `DV1`
    WHERE `event_id` = `photo_event`.`event_id`
  ) AS `order_grand_total`,
  
  
  ROUND((SUM(`photo_event_images`.`image_size`)*100)/100) AS `total_size`,
  `photo_event`.`event_id`,
  `photo_event`.`event_name`,
  `photo_event`.`event_num`,
  `photo_event`.`event_date`,
  `photo_event`.`event_end`,
  `cust_customers`.`cust_handle`,
  `photo_photographers`.`photo_fname`,
  `photo_photographers`.`photo_lname`
  ".$Addselect."
  FROM `photo_event`
  ".$Innerjoin."
  INNER JOIN `cust_customers` 
    ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
  LEFT JOIN `photo_event_group`
    ON (`photo_event_group`.`event_id` = `photo_event`.`event_id`
    OR `photo_event_group`.`event_id` IS NULL)
    AND `photo_event_group`.`group_use` = 'y'
  LEFT JOIN `photo_event_images`
    ON (`photo_event_images`.`group_id` = `photo_event_group`.`group_id`
    OR `photo_event_images`.`group_id` IS NULL)
    AND `photo_event_images`.`image_active` = 'y'
  LEFT JOIN `photo_photographers`
    ON (`photo_event`.`photo_id` = `photo_photographers`.`photo_id`
      OR `photo_event`.`photo_id` IS NULL)
  WHERE `photo_event`.`cust_id` = '$CustId'
    AND `event_use` = 'y'
  GROUP BY `photo_event`.`event_id`
  ".$Sort_val.";";

// echo $query;
$getInfo->mysql( $query ); ?>
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
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1" class="fixed-width-table">
      <tr>
        <th class="fixed-width-checkbox"><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll','TableRecords1');" /></th>
        <th class="fixed-width-event-name"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Event Name,<? echo ($Sort == "Event Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Name">Event Name</a></th>
        <th class="fixed-width-event-code"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Code,<? echo ($Sort == "Code" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Code'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Code">Code</a></th>
        <th class="fixed-width-event-date"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event When Event Starts'; return true;" onmouseout="window.status=''; return true;" title="Sort By When Event Starts">Date</a></th>
        <th class="fixed-width-event-ends"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Ends,<? echo ($Sort == "Ends" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By When the Group Ends'; return true;" onmouseout="window.status=''; return true;" title="Sort By When the Group Ends">Ends</a></th>
        <th class="fixed-width-event-photographer"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Photo,<? echo ($Sort == "Photo" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Photographer'; return true;" onmouseout="window.status=''; return true;" title="Sort By Photographer">Photographer</a></th>
        <th class="fixed-width-event-group-number"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Grps,<? echo ($Sort == "Grps" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Number of Groups'; return true;" onmouseout="window.status=''; return true;" title="Sort By Number of Groups">Grps</a></th>
        <th class="fixed-width-event-image-count"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Imgs,<? echo ($Sort == "Imgs" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Number of Images'; return true;" onmouseout="window.status=''; return true;" title="Sort By Number of Images">Imgs</a></th>
        <!-- <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Used,<? echo ($Sort == "Used" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Used Space'; return true;" onmouseout="window.status=''; return true;" title="Sort By Used Space">Used</a></th> -->
        <th class="fixed-width-event-sales-amount"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Total,<? echo ($Sort == "Total" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Sales'; return true;" onmouseout="window.status=''; return true;" title="Sort By Sales">Sales</a></th>
        <th class="fixed-width-event-link"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Link,<? echo ($Sort == "Link" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Event Link'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Link">Link</a></th>
        <th class="R fixed-width-event-edit">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,2)).",".$r['event_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><input type="checkbox" name="Event_items[]" id="Event_items_<? echo ($k+1); ?>" value="<? echo $r['event_id']; ?>" /></td>
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>"><? echo ((strlen($r['event_name']) > 25) ?  substr($r['event_name'],0,25)."..." : $r['event_name']); ?></a></td>
        <td><? echo $r['event_num']; ?></td>
        <td><? echo format_date($r['event_date'],"DashShort",false,true,false); ?></td>
        <td><? echo format_date($r['event_end'],"DashShort",false,true,false); ?></td>
        <td><? echo $r['photo_fname']; ?></td>
        <td><? echo $r['group_count']; ?></td>
        <td><? echo $r['image_count']; ?></td>
        <!-- <td><? echo (round($r['total_size']/1024*100)/100)." GB"; ?></td> -->
        <td><? echo "$".number_format($r['order_grand_total'],2,".",","); ?></td>
        <td><? echo "<a href=\"/photo_viewer.php?Photographer=".$r['cust_handle']."&code=".$r['event_num']."&email=".$Email."&full=true\" target=\"_blank\" onmouseover=\"window.status='View Event ".str_replace("'","\'",$r['event_name'])."'; return true;\" onmouseout=\"window.status=''; return true;\" title=\"View Event ".str_replace("'","\'",$r['event_name'])."\">View</a>"; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>">Edit</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
