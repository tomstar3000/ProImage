<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
$EId = $path[2];
if (isset($_POST['Controller']) && $_POST['Controller'] == "Apply") {
    $items = $_POST['Event_Notifications_items'];
    if (count($items) > 0) {
        foreach ($items as &$item) {
            $item = intval(preg_replace('/[^0-9]/', '', $item), 10);
            unset($item);
        }
        $getNoteEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $getNoteEvents->mysql("SELECT * FROM `photo_event_note_events` WHERE event_id = $EId;");

        $NoteEvents = array();
        foreach ($getNoteEvents->Rows() as $k => $r) {
            $NoteEvents[] = intval($r['event_note_id'], 10);
        }

        $add = array_diff($items, $NoteEvents);
        $delete = array_diff($NoteEvents, $items);

        if (count($add) > 0) {
            $baseSQL = "INSERT INTO photo_event_note_events VALUES ";
            foreach ($add as &$value) {
                $value = "(" . $EId . "," . $value . ")";
                unset($value);
            }
            $getNoteEvents->mysql($baseSQL . implode(',', $add) . ";");
        }
        if (count($delete) > 0) {
            $getNoteEvents->mysql("DELETE FROM photo_event_note_events WHERE event_note_id IN (" . implode(',', $delete) . ") AND event_id = $EId;");
            $getNoteEvents->mysql("OPTIMIZE TABLE `photo_event_note_events`;");
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
    $Sort_val = " ORDER BY `event_before` " . $Order . ",`event_date` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "On Date") {
    $Sort_val = " ORDER BY `event_date` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
}
?>

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

<div id="HdrLinks">
    <a href="javascript:document.getElementById('Controller').value = 'Apply'; document.getElementById('form_path').value='<? echo implode(",", array_slice($path, 0, 5)); ?>'; document.getElementById('form_action_form').submit();" onclick="javascript:if (confirm('Are you sure you want to apply these Notifications'))
                return true;
            else
                return false;" onmouseover="window.status = 'Apply Notifications';
            return true;" onmouseout="window.status = '';
            return true;" title="Apply Notifications" class="BtnSave">Apply Notifications</a>
</div>
<?
$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT photo_event_notes.*, photo_event_note_events.event_id
    FROM `photo_event_notes`
    LEFT JOIN photo_event_note_events
      ON (photo_event_note_events.event_note_id = photo_event_notes.event_note_id
        AND photo_event_note_events.`event_id` = '$EId')
    LEFT JOIN photo_event_notes AS check_clone
        ON (check_clone.event_note_id = photo_event_notes.event_clone_id AND check_clone.cust_id = '$CustId')
    LEFT JOIN photo_event_notes_cust_del
        ON (photo_event_notes_cust_del.event_note_id = photo_event_notes.event_note_id AND photo_event_notes_cust_del.cust_id = '$CustId')
    WHERE (photo_event_notes.cust_id = '$CustId' 
            OR ( photo_event_notes.cust_id = 0 AND photo_event_notes.event_id = 0))
        AND check_clone.event_note_id IS NULL
        AND photo_event_notes_cust_del.event_note_id IS NULL
    GROUP BY photo_event_notes.event_note_id
    ORDER BY `event_note_name` ASC;");
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
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Note Name,<? echo ($Sort == "Note Name" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Note Name';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By Note Name">Note Name</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Number of Days,<? echo ($Sort == "Number of Days" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Number of Days';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By Number of Days">Number of Days</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Time,<? echo ($Sort == "Time" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Time';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By Time">Time</a></th>
                    <th class="R">&nbsp;</th>
                </tr>
                <?
                foreach ($getInfo->Rows() as $k => $r) {
                    $EvntAction = "javascript:set_form('','" . implode(",", array_slice($path, 0, 4)) . "," . $r['event_note_id'] . "','edit','" . $sort . "','" . $rcrd . "');";
                    $class1 = "";
                    $class2 = "ROver";
                    if (intval($k % 2) == 1) {
                        $class1 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                    } else if ($k == ($getInfo->TotalRows() - 1))
                        $class1 = 'B';
                    $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;
                    ?>
                    <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?> onMouseOver="this.className = '<? echo $class2; ?>';" onMouseOut="this.className = '<? echo $class1; ?>';">
                        <td><input type="checkbox" name="Event_Notifications_items[]" id="Event_Notifications_items_<? echo ($k + 1); ?>" value="<? echo $r['event_note_id']; ?>"<?php if ($r['event_id'] == $EId) echo ' checked="checked"'; ?> /></td>
                        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit <? echo str_replace("'", "\'", $r['event_note_name']); ?>';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Edit <? echo str_replace("'", "\'", $r['event_note_name']); ?>"><? echo ((strlen($r['event_note_name']) > 30) ? substr($r['event_note_name'], 0, 30) . "..." : $r['event_note_name']); ?></a></td>
                        <td><? echo $r['event_days']; ?></td>
                        <td><? echo ($r['event_before'] == "b") ? "Before Event Release Date" : (($r['event_before'] == "s") ? "After Event Release Date" : (($r['event_before'] == "e") ? "Before Event Expiration Date" : format_date($r['event_date'], "Short", false, true, false) . " at 12:01am")); ?></td>

                        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Event <? echo str_replace("'", "\'", $r['event_note_name']); ?>';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Edit Event <? echo str_replace("'", "\'", $r['event_note_name']); ?>">Open</a></td>
                    </tr>
                <? } ?>
            </table>
        <? } else { ?>
            <p>There are no records on file</p>
        <? } ?>
    </div>
    <div id="Bottom"></div>
</div>
