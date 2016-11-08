<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';

$HotMenu = implode(",", array_slice($path, 0, 2)) . ":query";
$Key = array_search($HotMenu, $StrArray);

$items = array();
if (isset($Defaults['EventNotifications'])) {
    $items = $Defaults['EventNotifications'];
}
if (isset($_POST['Event_Notifications_items'])) {
    foreach ($_POST['Event_Notifications_items'] as $value) {
        $items[] = intval($value, 10);
    }
}

if (implode(',', $path) != 'Evnt,Evnt') {
    if (isset($_POST['Controller']) && $_POST['Controller'] == "Delete") {
        if (count($items) > 0) {
            $delInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            foreach ($items as $key => $value) {
                $delInfo->mysql("SELECT cust_id FROM `photo_event_notes` WHERE `event_note_id` = '$value';");
                $getInfo = $delInfo->Rows();
                $DEFAULT = ($getInfo[0]['cust_id'] == 0) ? true : false;

                if ($DEFAULT) {
                    $delInfo->mysql("INSERT IGNORE INTO `photo_event_notes_cust_del` (event_note_id, `cust_id`) VALUES ($value, '$CustId');");
                } else {
                    $delInfo->mysql("DELETE FROM `photo_event_notes` WHERE `event_note_id` = '$value' AND cust_id != 0;");
                    $delInfo->mysql("OPTIMIZE TABLE `photo_event_notes`;");
                }
            }
        }
    } else if (isset($_POST['Controller']) && $_POST['Controller'] == "Apply") {
        if (isset($_POST['Event_Notifications_items'])) {

            $EventList = array();
            if (isset($_POST['Event_items'])) {
                $EventList = $_POST['Event_items'];
                foreach ($EventList as &$EventId) {
                    $EventId = intval(preg_replace('/[^0-9]/', '', $EventId), 10);
                    unset($EventId);
                }
            }
            foreach ($items as $ENId) {
                $getNoteEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getNoteEvents->mysql("SELECT * FROM `photo_event_note_events` WHERE event_note_id = $ENId;");

                $NoteEvents = array();
                foreach ($getNoteEvents->Rows() as $k => $r) {
                    $NoteEvents[] = intval($r['event_id'], 10);
                }

                $add = array_diff($EventList, $NoteEvents);
                $delete = array_diff($NoteEvents, $EventList);
                if (count($add) > 0) {
                    $baseSQL = "INSERT INTO photo_event_note_events VALUES ";
                    foreach ($add as &$value) {
                        $value = "(" . $value . "," . $ENId . ")";
                        unset($value);
                    }
                    $getNoteEvents->mysql($baseSQL . implode(',', $add) . ";");
                }
                if (count($delete) > 0) {
                    $getNoteEvents->mysql("DELETE FROM photo_event_note_events WHERE event_id IN (" . implode(',', $delete) . ") AND event_note_id = $ENId;");
                }
            }
            define('SAVEMESSAGE', 'Event Notifications has been applied to selected events');
        }
    } else if (isset($_POST['Controller']) && $_POST['Controller'] == "Clone") {
        if (count($items) > 0) {
            foreach ($items as $key => $value) {
                $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getInfo->mysql("SELECT photo_event_notes.* 
                FROM `photo_event_notes` 
                WHERE `event_note_id` = '$value';");

                $info = $getInfo->Rows();
                $info = $info[0];

                $getInfo->mysql("INSERT INTO `photo_event_notes` (`cust_id`,`event_note_name`,`event_days`,`event_before`,`event_date`,`event_image`,`event_image_id`,`event_message`,`event_recps`) 
                            VALUES ('$CustId','" . preg_replace('/ \(Clone\)/', '', $info['event_note_name']) . " (Clone)','" . $info['event_days'] . "','" . $info['event_before'] . "','" . $info['event_date'] . "','" . $info['event_image'] . "','" . $info['event_image_id'] . "','" . $info['event_message'] . "','');");

                $getLast = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getLast->mysql("SELECT `event_note_id` FROM `photo_event_notes` WHERE `cust_id` = '$CustId' ORDER BY `event_note_id` DESC LIMIT 0,1;");
                $getLast = $getLast->Rows();

                $LastId = $getLast[0]['event_note_id'];

                $getNoteEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                $getNoteEvents->mysql("SELECT * FROM `photo_event_note_events` WHERE `event_note_id` = '$value';");
                $NoteEvents = array();
                foreach ($getNoteEvents->Rows() as $k => $r) {
                    $NoteEvents[] = intval($r['event_id'], 10);
                }
                if (count($NoteEvents) > 0) {
                    $baseSQL = "INSERT INTO photo_event_note_events VALUES ";
                    foreach ($NoteEvents as &$value2) {
                        $value2 = "(" . $value2 . "," . $LastId . ")";
                        unset($value2);
                    }
                    $getInfo->mysql($baseSQL . implode(',', $NoteEvents) . ";");
                }
            }
        }
    }
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

if ($Sort == "Note Name") {
    $Sort_val = " ORDER BY `event_note_name` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Number of Days") {
    $Sort_val = " ORDER BY `event_days` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Time") {
    $Sort_val = " ORDER BY `event_before`" . $Order . ", `event_date` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "On Date") {
    $Sort_val = " ORDER BY `event_date` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Event") {
    $Sort_val = " ORDER BY photo_event.event_name " . $Order;
    $Addselect = "";
    $Innerjoin = "";
}

$Sort_val2 = " ORDER BY `photo_event`.`event_name` ASC";
?>
<? if (implode(',', $path) != 'Evnt,Evnt') { ?>
    <h1 id="HdrType2" class="<?
    switch ($path[0]) {
        case 'Evnt': echo 'EvntNoteEvnt';
            break;
        case 'Clnt': echo 'EvntNoteClnt';
            break;
        default: echo 'EvntNoteBus';
            break;
    }
    ?>">
        <div>Event Notifications</div>
    </h1>
<?php } else { ?>
    <h1 id="HdrType2-5" class="<?
    switch ($path[0]) {
        case 'Evnt': echo 'EvntNoteEvnt';
            break;
        case 'Clnt': echo 'EvntNoteClnt';
            break;
        default: echo 'EvntNoteBus';
            break;
    }
    ?>">
        <div>Event Notifications</div>
    </h1>
    <div id="btnCollapse">
        <a href="#" onclick="javascript: Open_Sec('AllEventNotifications', this);
                    return false;" onmouseover="window.status = 'Expand Event Notifications';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Expand Event Notifications">+</a>
    </div>
<?php } ?>
<div id="HdrLinks" style="width:525px;">
    <?php if (implode(',', $path) == 'Evnt,Evnt') { ?>
        <a href="#" onclick="javascript: Save_Default_Info();
                    return false;" onmouseover="window.status = 'Save Default Settings';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Save Default Settings" class="BtnSaveDefault">Save Defaults</a>
        <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Discount Codes';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Save Discount Codes" class="BtnSave<?php if (implode(',', $path) == 'Evnt,Evnt' && $cont == 'add') echo 'Upload'; ?>">Save</a>
       <?php } else { ?>
        <a href="#" onclick="javascript:document.getElementById('Controller').value = 'Apply';
                    document.getElementById('form_path').value = '<? echo implode(",", array_slice($path, 0, 3));
           ?>';
                    document.getElementById('form_action_form').submit();" onmouseover="window.status = 'Apply to Events Selected Below';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Apply to Events Selected Below" class="BtnSaveToEvents">Apply to Events Selected Below</a>
        <a href="javascript:document.getElementById('Controller').value = 'Clone'; document.getElementById('form_path').value='<? echo implode(",", array_slice($path, 0, 3));
           ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status = 'Clone Notification';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Clone Notification" class="BtnClone">Clone Notification</a>

        <a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_path').value='<? echo implode(",", array_slice($path, 0, 3)); ?>'; document.getElementById('form_action_form').submit();" onclick="javascript:if (confirm('Are you sure you want to remove these Notifications'))
                        return true;
                    else
                        return false;" onmouseover="window.status = 'Remove Notification';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Remove Notification" class="BtnDel">Remove Notification</a>
        <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false';
                    set_form('', '<?php echo implode(",", array_slice($path, 0, 2)); ?>', 'add', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');
                    return false;" onmouseover="window.status = 'Add New Notification';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Add New Notification" class="BtnAdd">Add New Notification</a> 
        <a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key !== false) ? '2' : '1'; ?>);" title="Add to Hot Menu" onmouseover="window.status = 'Add to Hot Menu';
                    return true;" onmouseout="window.status = '';
                    return true;" class="BtnHotMenu<? echo ($Key !== false) ? 'Added' : ''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
       <?php } ?>
</div>
<?
$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT photo_event_notes.*, 
        COUNT(photo_event.event_id) AS event_name, 
        CASE WHEN photo_event_notes.event_clone_id = 0 THEN 'false' ELSE 'true' END AS is_clone
    FROM photo_event_notes
    LEFT JOIN photo_event_note_events
        ON (photo_event_note_events.event_note_id = photo_event_notes.event_note_id)
    LEFT JOIN photo_event
        ON (photo_event.event_id = photo_event_note_events.event_id AND photo_event.cust_id = '$CustId')
    LEFT JOIN photo_event_notes AS check_clone
        ON (check_clone.event_note_id = photo_event_notes.event_clone_id AND check_clone.cust_id = '$CustId')
    LEFT JOIN photo_event_notes_cust_del
        ON (photo_event_notes_cust_del.event_note_id = photo_event_notes.event_note_id AND photo_event_notes_cust_del.cust_id = '$CustId')
    WHERE (photo_event_notes.cust_id = '$CustId' 
            OR ( photo_event_notes.cust_id = 0 AND photo_event_notes.event_id = 0))
        AND check_clone.event_note_id IS NULL
        AND photo_event_notes_cust_del.event_note_id IS NULL
    GROUP BY photo_event_notes.event_note_id
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
?>"><a id="AllEventNotifications"></a>
    <div id="Top"></div>
    <div id="Records">
        <span id="AllEventNotificationRecords">
            <? if ($getInfo->TotalRows() > 0) { ?>
                <span id="NotificationList">
                    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll', 'NotificationList');" /></th>
                                <th>Note Name</th>
                                <th>Number of Days</th>
                                <th>Time</th>
                                <th>Num of Events</th>
                                <th class="R">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($getInfo->Rows() as $k => $r) {
                                $EvntAction = "javascript:set_form('','Evnt,Note," . $r['event_note_id'] . "','edit','" . $sort . "','" . $rcrd . "');";
                                $class1 = "";
                                $class2 = "ROver";
                                if (intval($k % 2) == 1) {
                                    $class1 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                                } else if ($k == ($getInfo->TotalRows() - 1))
                                    $class1 = 'B';
                                $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;
                                ?>
                                <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?>>
                                    <td><input type="checkbox" name="Event_Notifications_items[]" id="Event_Notifications_items_<? echo ($k + 1); ?>" value="<? echo $r['event_note_id']; ?>"<?php if (in_array($r['event_note_id'], $items)) echo ' checked="checked"'; ?> /></td>
                                    <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit <? echo str_replace("'", "\'", $r['event_note_name']); ?>';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Edit <? echo str_replace("'", "\'", $r['event_note_name']); ?>"><? echo ((strlen($r['event_note_name']) > 30) ? substr($r['event_note_name'], 0, 30) . "..." : $r['event_note_name']); ?></a></td>
                                    <td><? echo $r['event_days']; ?></td>
                                    <td><? echo ($r['event_before'] == "b") ? "Before Event Release Date" : (($r['event_before'] == "s") ? "After Event Release Date" : (($r['event_before'] == "e") ? "Before Event Expiration Date" : format_date($r['event_date'], "Short", false, true, false) . ' @ 12:01am')); ?></td>
            <!--                                <td><? echo (in_array($r['event_before'], array('b', 's', 'e')) ? '' : format_date($r['event_date'], "Short", false, true, false)); ?></td>-->
                                    <td><? echo $r['event_name']; ?></td>
                                    <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Event <? echo str_replace("'", "\'", $r['event_note_name']); ?>';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Edit Event <? echo str_replace("'", "\'", $r['event_note_name']); ?>">Open</a></td>
                                </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </span>
            <? } else { ?>
                <p>There are no records on file</p>
            <? } ?>
        </span>
    </div>
    <div id="Bottom"></div>
</div>

<?php if (implode(',', $path) != 'Evnt,Evnt') { ?>
    <h1 id="HdrType2" class="UpcmngEvnt">
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
	" . $Sort_val2 . ";");
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
                <span id="EventList">
                    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="CheckAll2" id="CheckAll2" value="" onclick="javascript:goCheckAll('CheckAll2', 'EventList');" /></th>
                                <th>Event Name</th>
                                <th>Code</th>
                                <th>Link</th>
                                <th>Date</th>
                                <th>Ends</th>
                                <th>Grps</th>
                                <th>Imgs</th>
                                <th>Used</th>
                                <th class="R">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                            foreach ($getInfo->Rows() as $k => $r) {
                                $EvntAction = "javascript:set_form('','Evnt,Evnt," . $r['event_id'] . "','view','" . $sort . "','" . $rcrd . "');";
                                $class1 = "";
                                $class2 = "ROver";
                                if (intval($k % 2) == 1) {
                                    $class1 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                                } else if ($k == ($getInfo->TotalRows() - 1)) {
                                    $class1 = 'B';
                                }
                                $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;
                                ?>
                                <tr<?php if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?>>
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
                        </tbody>
                    </table>
                </span>
            <? } else { ?>
                <p>There are no records on file</p>
            <? } ?>
        </div>
        <div id="Bottom"></div>
    </div>
<?php } ?>
<script type="text/javascript">
            $(document).ready(function()
            {
                $("tbody tr").bind('mouseenter', function() {
                    $(this).addClass("ROver");
                });
                $("tbody tr").bind('mouseleave', function() {
                    $(this).removeClass("ROver");
                });
                $("#NotificationList table").tablesorter({
                    sortList: [[1, 0]],
                    headers: {
                        0: {
                            sorter: false
                        },
                        5: {
                            sorter: false
                        }
                    }
                }).bind("sortEnd", function() {
                    setRows($(this));
                });
                $("#EventList table").tablesorter({
                    sortList: [[1, 0]],
                    headers: {
                        0: {
                            sorter: false
                        },
                        9: {
                            sorter: false
                        }
                    }
                }).bind("sortEnd", function() {
                    setRows($(this));
                });
            });
            function setRows($oElem) {
                $oElem.find('tbody tr:even').removeClass('SRow').removeClass('BSRow');
                $oElem.find('tbody tr:odd').removeClass('BSRow').addClass('SRow');
                if ($oElem.find('tbody tr:last').hasClass("SRow")) {
                    $oElem.find('tbody tr:last').removeClass('SRow').addClass('BSRow');
                } else {
                    $oElem.find('tbody tr:last').addClass('B');
                }
            }
</script>