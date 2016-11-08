<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once($r_path . 'scripts/fnct_format_date.php');

define('NoSave', true);
if (isset($required_string)) {
    $onclick = 'MM_validateForm(' . $required_string . '); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
} else {
    $onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
}
if ($sort != "") {
    $Sorting = explode(",", $sort, 3);
    $Sort = str_replace("_", " ", $Sorting[0]);
    $Order = str_replace("_", " ", $Sorting[1]);
} else {
    $Sort = "Event Name";
    $Order = "ASC";
}
$sort = $Sort . "," . $Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",", $TempSort);
$Sorting = str_replace(" ", "_", $Sorting);
unset($TempSort);

if ($Sort == "Event Name") {
    $Sort_val = " ORDER BY `photo_event`.`event_name` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Code") {
    $Sort_val = " ORDER BY `photo_event`.`event_num` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Link") {
    $Sort_val = " ORDER BY `photo_event`.`event_name` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Date") {
    $Sort_val = " ORDER BY `photo_event`.`event_date` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Ends") {
    $Sort_val = " ORDER BY `photo_event`.`event_end` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Grps") {
    $Sort_val = " ORDER BY `group_count` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Imgs") {
    $Sort_val = " ORDER BY `image_count` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Used") {
    $Sort_val = " ORDER BY `total_size` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
}
?>
<script type="text/javascript">
    function goCheckAll(ID1, ID2) {
        var CheckAll = false;
        if (document.getElementById(ID1).checked == true)
            CheckAll = true;
        var Boxes = document.getElementById(ID2).getElementsByTagName('input');
        var n = 1;
        while (n < Boxes.length) {
            Boxes[n].checked = CheckAll;
            n++;
        }
    }
</script>
<h1 id="HdrType2" class="<?
switch ($path[1]) {
    case "Mrkt": echo "UpcmngEvnt";
        break;
    case "Guest": echo "GstBkClnt";
        break;
    case "Board": echo "MsgBrdClnt";
        break;
    case "Pre": echo "BsnPreDiscCodes";
        break;
}
?>">
    <div>Event List</div>
</h1>
<?
$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT 
	DISTINCT COUNT(`photo_event_images`.`image_id`) AS `image_count`,
	(SELECT count(`group_id`) 
		FROM `photo_event_group` 
		WHERE `photo_event_group`.`event_id` = `photo_event`.`event_id` 
			AND `photo_event_group`.`group_use` = 'y') AS `group_count`,
	ROUND((SUM(`photo_event_images`.`image_size`)*100)/100) AS `total_size`,
	`photo_event`.`event_id`,
	`photo_event`.`event_name`,
	`photo_event`.`event_num`,
	`photo_event`.`event_date`,
	`photo_event`.`event_end`,
	`cust_customers`.`cust_handle`
	" . $Addselect . "
	FROM `photo_event`
	" . $Innerjoin . "
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
	WHERE `photo_event`.`cust_id` = '$CustId'
		AND `event_use` = 'y'
	GROUP BY `photo_event`.`event_id`
	" . $Sort_val . ";");
?>
<div id="RecordTable" class="<?
switch ($path[0]) {
    case 'Evnt': echo 'Red';
        break;
    case 'Clnt': echo 'Yellow';
        break;
    case 'Busn': echo 'Green';
        break;
    default: echo 'Red';
        break;
}
?>">
    <div id="Top"></div>
    <div id="Records">
        <p class="Error">Select Events that you would like to apply Event Marketing options to.</p>
    </div>
    <div id="Bottom"></div>
</div>
<br clear="all" />
<div id="RecordTable" class="<?
switch ($path[0]) {
    case 'Evnt': echo 'Red';
        break;
    case 'Clnt': echo 'Yellow';
        break;
    case 'Busn': echo 'Green';
        break;
    default: echo 'Red';
        break;
}
?>">
    <div id="Top"></div>
    <div id="Records">
        <? if ($getInfo->TotalRows() > 0) { ?>
            <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
                <tr>
                    <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll', 'TableRecords1');" /></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Event Name,<? echo ($Sort == "Event Name" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Event Name';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Event Name">Event Name</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Code,<? echo ($Sort == "Code" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Event Code';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Event Code">Code</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Link,<? echo ($Sort == "Link" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Event Link';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Event Link">Link</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Date" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Event When Event Starts';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By When Event Starts">Date</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Ends,<? echo ($Sort == "Ends" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By When the Group Ends';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By When the Group Ends">Ends</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Grps,<? echo ($Sort == "Grps" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Number of Groups';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Number of Groups">Grps</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Imgs,<? echo ($Sort == "Imgs" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Number of Images';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Number of Images">Imgs</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Used,<? echo ($Sort == "Used" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Used Space';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Used Space">Used</a></th>
                    <th class="R">&nbsp;</th>
                </tr>
                <?
                foreach ($getInfo->Rows() as $k => $r) {
                    $EvntAction = "javascript:set_form('','Evnt,Evnt," . $r['event_id'] . "','view','" . $sort . "','" . $rcrd . "');";
                    $class1 = "";
                    $class2 = "ROver";
                    if (intval($k % 2) == 1) {
                        $class1 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                    } else if ($k == ($getInfo->TotalRows() - 1))
                        $class1 = 'B';
                    $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;
                    ?>
                    <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?> onMouseOver="this.className = '<? echo $class2; ?>';" onMouseOut="this.className = '<? echo $class1; ?>';">
                        <td><input type="checkbox" name="Event_items[]" id="Event_items_<? echo ($k + 1); ?>" value="<? echo $r['event_id']; ?>" /></td>
                        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Event <? echo str_replace("'", "\'", $r['event_name']); ?>';
                return true;" onmouseout="window.status = '';
                return true;" title="Edit Event <? echo str_replace("'", "\'", $r['event_name']); ?>"><? echo ((strlen($r['event_name']) > 30) ? substr($r['event_name'], 0, 30) . "..." : $r['event_name']); ?></a></td>
                        <td><? echo $r['event_num']; ?></td>
                        <td><? echo "<a href=\"/photo_viewer.php?Photographer=" . $r['cust_handle'] . "&code=" . $r['event_num'] . "&email=" . $Email . "&full=true\" target=\"_blank\" onmouseover=\"window.status='View Event " . str_replace("'", "\'", $r['event_name']) . "'; return true;\" onmouseout=\"window.status=''; return true;\" title=\"View Event " . str_replace("'", "\'", $r['event_name']) . "\">View</a>"; ?></td>
                        <td><? echo format_date($r['event_date'], "Dash", false, true, false); ?></td>
                        <td><? echo format_date($r['event_end'], "Dash", false, true, false); ?></td>
                        <td><? echo $r['group_count']; ?></td>
                        <td><? echo $r['image_count']; ?></td>
                        <td><? echo (round($r['total_size'] / 1024 * 100) / 100) . " GB"; ?></td>
                        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Event <? echo str_replace("'", "\'", $r['event_name']); ?>';
                return true;" onmouseout="window.status = '';
                return true;" title="Edit Event <? echo str_replace("'", "\'", $r['event_name']); ?>">Open</a></td>
                    </tr>
                <? } ?>
            </table>
        <? } else { ?>
            <p>There are no records on file</p>
        <? } ?>
    </div>
    <div id="Bottom"></div>
</div>
