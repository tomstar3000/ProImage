<?php
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';

$HotMenu = implode(",", array_slice($path, 0, 2)) . ":query";
$Key = array_search($HotMenu, $StrArray);

$EId = 0;

if (isset($Defaults['Discount_items'])) {
    $items = $Defaults['Discount_items'];
}
if (count($path) == 2 && implode(',', $path) != 'Evnt,Evnt') {
    if (isset($_POST['Controller']) && $_POST['Controller'] == "Save") {
        $items = $_POST['Discount_items'];
        foreach ($items as &$item) {
            $item = preg_replace('/[^0-9]/', '', $item);
            unset($item);
        }

        $SaveEvents = $_POST['Events'];
        if (count($SaveEvents)) {
            foreach ($SaveEvents as $Event) {
                $Event = preg_replace('/[^0-9]/', '', $Event);
                $getPreEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getPreEvents->mysql("SELECT disc_id FROM `photo_event_disc` WHERE `event_id` = " . $Event . " AND `disc_type` = 's';");

                $PreItems = array();
                foreach ($getPreEvents->Rows() as $k => $r) {
                    $PreItems[] = intval($r['disc_id'], 10);
                }
                $add = array_diff($items, $PreItems);
                $delete = array_diff($PreItems, $items);

                if (count($add) > 0) {
                    $baseSQL = "INSERT INTO photo_event_disc (`event_id`,`disc_id`,`disc_type`) VALUES ";
                    foreach ($add as &$value) {
                        $value = "('" . $Event . "','" . $value . "','s')";
                        unset($value);
                    }
                    $getInfo->mysql($baseSQL . implode(',', $add) . ";");
                }
                if (count($delete) > 0) {
                    $getInfo->mysql("DELETE FROM photo_event_disc WHERE event_id = " . $Event . "
                    AND disc_id IN (" . implode(',', $delete) . ") ;");
                    $getInfo->mysql("OPTIMIZE TABLE `photo_event_disc`;");
                }
            }
        }
        define('SAVEMESSAGE', 'Presets applied to the selected events');
    }
} else {
    $EId = $path[2];
    if (isset($_POST['Controller']) && $_POST['Controller'] == "Save") {
        $items = array();
        $items = $_POST['Discount_items'];
        $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 's';");
        $getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");
        foreach ($items as $key => $value) {
            $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$value','s');");
        }
    }
}
if ($sort != "") {
    $Sorting = explode(",", $sort, 3);
    $Sort = str_replace("_", " ", $Sorting[0]);
    $Order = str_replace("_", " ", $Sorting[1]);
} else {
    $Sort = "Code Name";
    $Order = "ASC";
}
$sort = $Sort . "," . $Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",", $TempSort);
$Sorting = str_replace(" ", "_", $Sorting);
unset($TempSort);

if ($Sort == "Code Name") {
    $Sort_val = " ORDER BY `prod_discount_codes`.`disc_name` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Code") {
    $Sort_val = " ORDER BY `prod_discount_codes`.`disc_code` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
}
if (!isset($onclick)) {
    $onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
}
?>
<? if (implode(',', $path) != 'Evnt,Evnt') { ?>
    <h1 id="HdrType2" class="<?
    switch ($path[0]) {
        case 'Clnt': echo 'EvntMarket';
            break;
        case 'Busn': echo 'BsnPreDiscCodes';
            break;
        default: echo 'EvntMarket';
            break;
    }
    ?>">
        <div>Preset Discount Codes</div>
    </h1>
<?php } else { ?>
    <h1 id="HdrType2-5" class="<?
    switch ($path[0]) {
        case 'Clnt': echo 'EvntMarket';
            break;
        case 'Busn': echo 'BsnPreDiscCodes';
            break;
        default: echo 'EvntMarket';
            break;
    }
    ?>">
        <div>Preset Discount Codes</div>
    </h1>
    <div id="btnCollapse">
        <a href="#" onclick="javascript: Open_Sec('AllPresetDiscountCodes', this);
                    return false;" onmouseover="window.status = 'Expand Preset Discount Codes';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Expand Preset Discount Codes">+</a>
    </div>
<?php } ?>


<div id="HdrLinks">
    <?php if (implode(',', $path) == 'Evnt,Evnt') { ?>
        <a href="#" onclick="javascript: Save_Default_Info();
                    return false;" onmouseover="window.status = 'Save Default Settings';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
       <?php } ?>
       <?php if (count($path) == 2 && implode(',', $path) != 'Evnt,Evnt') { ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Apply to Events Selected Below';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Apply to Events Selected Below" class="BtnSaveToEvents">Apply to Events Selected Below</a>
        <a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key !== false) ? '2' : '1'; ?>);" title="Add to Hot Menu" onmouseover="window.status = 'Add to Hot Menu';
                    return true;" onmouseout="window.status = '';
                    return true;" class="BtnHotMenu<? echo ($Key !== false) ? 'Added' : ''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
       <?php } else { ?>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Discount Codes';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Save Discount Codes" class="BtnSave<?php if (implode(',', $path) == 'Evnt,Evnt' && $cont == 'add') echo 'Upload'; ?>">Save</a>
       <?php } ?>
</div>
<?
$discdate = date("Y-m-d");
$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT `prod_discount_codes`.* , `photo_event_disc`.`event_disc_id`
    FROM `prod_discount_codes` 
    LEFT JOIN `photo_event_disc`
        ON ((`prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
            AND `photo_event_disc`.`event_id` = '$EId')
            OR `photo_event_disc`.`disc_id` IS NULL)
    WHERE `prod_discount_codes`.`cust_id` = '0'
        AND `prod_discount_codes`.`disc_use` = 'y' 
        AND `prod_discount_codes`.`evnt_mrk_id` = '0'
        AND `prod_discount_codes`.`disc_type` = 's'
        AND (`prod_discount_codes`.`disc_exp` = '0000-00-00 00:00:00' 
            OR `prod_discount_codes`.`disc_exp` >= '" . $discdate . "')
     " . $Sort_val . ";");
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
<div id="RecordTable<?php echo(implode(',', $path) == 'Evnt,Evnt') ? '-5' : ''; ?>" class="<?
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
?>"><a id="AllPresetDiscountCodes"></a>
    <div id="Top"></div>
    <div id="Records">
        <? if ($getInfo->TotalRows() > 0) { ?>
            <table border="0" cellpadding="0" cellspacing="0" id="PresetRecordList">
                <tr>
                    <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll', 'PresetRecordList');" /></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Code Name,<? echo ($Sort == "Code Name" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Code Name';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Sort By Code Name">Code Name</a></th>
                    <th class="R"><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Code,<? echo ($Sort == "Code" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Code';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Sort By Code">Code</a></th>
                </tr>
                <?
                foreach ($getInfo->Rows() as $k => $r) {
                    $EvntAction = "javascript:set_form('','" . implode(",", array_slice($path, 0, 2)) . "," . $r['event_id'] . "','view','" . $sort . "','" . $rcrd . "');";
                    $class1 = "";
                    $class2 = "ROver";
                    if (intval($k % 2) == 1) {
                        $class1 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                    } else if ($k == ($getInfo->TotalRows() - 1)) {
                        $class1 = 'B';
                    }
                    $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;
                    ?>
                    <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?> onMouseOver="this.className = '<? echo $class2; ?>';" onMouseOut="this.className = '<? echo $class1; ?>';">
                        <td><input type="checkbox" name="Discount_items[]" id="Discount_items_<? echo ($k + 1); ?>" value="<? echo $r['disc_id']; ?>"<? if ($r['event_disc_id'] != NULL || (isset($items) && in_array($r['disc_id'], $items))) echo ' checked="checked"'; ?> /></td>
                        <td><? echo ((strlen($r['disc_name']) > 30) ? substr($r['disc_name'], 0, 30) . "..." : $r['disc_name']); ?></td>
                        <td class="R"><? echo $r['disc_code']; ?></td>
                    </tr>
                <? } ?>
            </table>
        <? } else { ?>
            <p>There are no records on file</p>
        <? } ?>
    </div>
    <div id="Bottom"></div>
</div>
<?php
if (count($path) == 2 && implode(',', $path) != 'Evnt,Evnt') {

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
    } else if ($Sort == "Code") {
        $Sort_val = " ORDER BY `photo_event`.`event_num` " . $Order;
    } else if ($Sort == "Link") {
        $Sort_val = " ORDER BY `photo_event`.`event_name` " . $Order;
    } else if ($Sort == "Date") {
        $Sort_val = " ORDER BY `photo_event`.`event_date` " . $Order;
    } else if ($Sort == "Ends") {
        $Sort_val = " ORDER BY `photo_event`.`event_end` " . $Order;
    } else if ($Sort == "Grps") {
        $Sort_val = " ORDER BY `group_count` " . $Order;
    } else if ($Sort == "Imgs") {
        $Sort_val = " ORDER BY `image_count` " . $Order;
    } else if ($Sort == "Used") {
        $Sort_val = " ORDER BY `total_size` " . $Order;
    } else {
        $Sort_val = " ORDER BY `photo_event`.`event_name` " . $Order;
    }
    ?>
    <h1 id="HdrType2" class="UpcmngEvnt">
        <div>Event List</div>
    </h1>

    <div id="RecordTable" class="Red">
        <div id="Top"></div>
        <div id="Records">
            <p class="Error">Select Events that you would like to apply Preset Discounts to.</p>
        </div>
        <div id="Bottom"></div>
    </div>
    <?php
    $getEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getEvents->mysql("SELECT 
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
	FROM `photo_event`
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
        case 'Evnt':
        case 'Clnt':
        case 'Busn':
            echo 'Red';
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
    <? if ($getEvents->TotalRows() > 0) { ?>
                <table border="0" cellpadding="0" cellspacing="0" id="EventRecordList">
                    <tr>
                        <th><input type="checkbox" name="CheckAll" id="CheckAllEvents" value="" onclick="javascript:goCheckAll('CheckAllEvents', 'EventRecordList');" /></th>
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
                    foreach ($getEvents->Rows() as $k => $r) {
                        $EvntAction = "javascript:set_form('','Evnt,Evnt," . $r['event_id'] . "','view','" . $sort . "','" . $rcrd . "');";
                        $class1 = "";
                        $class2 = "ROver";
                        if (intval($k % 2) == 1) {
                            $class1 = (($k == ($getEvents->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                        } else if ($k == ($getEvents->TotalRows() - 1)) {
                            $class1 = 'B';
                        }
                        $class2 = (($k == ($getEvents->TotalRows() - 1)) ? 'B' : '') . $class2;
                        ?>
                        <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?> onMouseOver="this.className = '<? echo $class2; ?>';" onMouseOut="this.className = '<? echo $class1; ?>';">
                            <td><input type="checkbox" name="Events[]" id="Event_items_<? echo ($k + 1); ?>" value="<? echo $r['event_id']; ?>"<?php if (isset($SaveEvents) && in_array($r['event_id'], $SaveEvents)) echo ' checked="checked"'; ?> /></td>
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
                            <td class="R"><a href="<?php echo $EvntAction; ?>" onmouseover="window.status = 'Edit Event <? echo str_replace("'", "\'", $r['event_name']); ?>';
                            return true;" onmouseout="window.status = '';
                            return true;" title="Edit Event <?php echo str_replace("'", "\'", $r['event_name']); ?>">Open</a></td>
                        </tr>
                <? } ?>
                </table>
            <? } else { ?>
                <p>There are no records on file</p>
    <? } ?>
        </div>
        <div id="Bottom"></div>
    </div>
    <br clear="all" />
<?php } ?>