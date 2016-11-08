<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
require_once($r_path . 'includes/info_calendar.php');
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
    <div>Event Notification Information</div>
</h1>
<div id="HdrLinks">
    <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Apply to Events Selected Below';
            return true;" onmouseout="window.status = '';
            return true;" title="Save and Apply to Events Selected Below" class="BtnSaveApplyToEvents">Save and Apply to Events Selected Below</a>
    <a href="#" onclick="javascript:set_form('form_', '<?php
    if ($path[3] == "Note") {
        echo implode(",", array_slice($path, 0, 4));
    } else {
        echo implode(",", array_slice($path, 0, 2));
    }
    ?>', 'query', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" onmouseover="window.status = 'Cancel';
            return true;" onmouseout="window.status = '';
            return true;" title="Cancel" class="BtnCancel">Cancel</a>
</div>
<div id="RecordTable" class="White">
    <div id="Top"></div>
    <div id="Records" class="Colmn2"> <span>

            <?
            $getEvents = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            $getEvents->mysql("SELECT 
                    `photo_event`.`event_id`,
                    `photo_event`.`event_name`
                FROM `photo_event`
                WHERE `photo_event`.`cust_id` = '$CustId'
                        AND `event_use` = 'y'
                ORDER BY `photo_event`.`event_name`;");
            ?>
            <label for="Name" class="CstmFrmElmntLabel">Name</label>
            <input type="text" name="Name" id="Name" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Notification Name';
            return true;" onmouseout="window.status = '';
            return true;" title="Notification Name" onchange="javascript:upDateWatermark();" class="CstmFrmElmntInput" value="<? echo $Name; ?>">
            <br />
            <label for="Month" class="CstmFrmElmntLabel">On Date</label>
            <div style="float:left; clear:none;">
                <select name="Month" id="Month" class="CstmFrmElmnt88" onmouseover="window.status = 'Month';
            return true;" onmouseout="window.status = '';
            return true;" title="Month">
                            <?
                            $TDate = date("m", mktime(0, 0, 1, substr($Date, 5, 2), 1, date("Y")));
                            for ($n = 0; $n < 12; $n++) {
                                $TDate2 = date("m", mktime(0, 0, 1, ($n + 1), 1, date("Y")));
                                ?>
                        <option value="<? echo $TDate2; ?>" title="<? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F", mktime(0, 0, 1, ($n + 1), 1, date("Y"))); ?></option>
                    <? } ?>
                </select>
            </div>
            <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
                <select name="Day" id="Day" class="CstmFrmElmnt53" onmouseover="window.status = 'Day';
            return true;" onmouseout="window.status = '';
            return true;" title="Day">
                            <?
                            $TDate = date("d", mktime(0, 0, 1, 1, substr($Date, 8, 2), date("Y")));
                            for ($n = 0; $n < 31; $n++) {
                                $TDate2 = date("d", mktime(0, 0, 1, 1, ($n + 1), date("Y")));
                                ?>
                        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                    <? } ?>
                </select>
            </div>
            <div style="float:left; clear:none;">
                <select name="Year" id="Year" class="CstmFrmElmnt64" onmouseover="window.status = 'Year';
            return true;" onmouseout="window.status = '';
            return true;" title="Year">
                            <?
                            $TDate = date("Y", mktime(0, 0, 1, 1, 1, substr($Date, 0, 4)));
                            for ($n = -2; $n < 5; $n++) {
                                $TDate2 = date("Y", mktime(0, 0, 1, 1, 1, (date("Y") + $n)));
                                ?>
                        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if ($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
                    <? } ?>
                </select>
            </div>
            <div id="BtnCalendar"><a href="javascript:StartCalDate('DateCalendar','Year','Month','Day',null);" onmouseover="window.status = 'Start Calendar';
            return true;" onmouseout="window.status = '';
            return true;" title="Start Calendar" id="DateCalendar">Calendar</a></div>
        </span> 
        <span>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>
                <input type="radio" name="BeforeDate" id="BeforeDate" value="" class="CstmFrmElmnt" title="On Specified Date" onmouseover="window.status = 'On Specified Date';
            return true;" onmouseout="window.status = '';
            return true;" onclick="document.getElementById('Days').value = 0;"<? if ($Before == "") echo ' checked="checked"'; ?>>
                <font class="fontSpecial">On Specified Date at 12:01am</font><br clear="all" />
            </p>
            <br clear="all" />
        </span>
        <br clear="all" />
        <div class="DividerBlack"></div>
        <span>
            <label for="Days" class="CstmFrmElmntLabel">Number of Days</label>
            <input type="text" name="Days" id="Days" onfocus="javascript:this.className = 'CstmFrmElmntInputNavSel';" onblur="javascript:this.className = 'CstmFrmElmntInput';" onmouseover="window.status = 'Number of Days for Notification';
            return true;" onmouseout="window.status = '';
            return true;" title="Number of Days for Notification" onchange="javascript:upDateWatermark();" class="CstmFrmElmntInput" value="<? echo $Days; ?>">
        </span><span>
            <p>
                <input type="radio" name="BeforeDate" id="BeforeDate" value="b" class="CstmFrmElmnt" title="Before Event Release Date" onmouseover="window.status = 'Before Event Release Date';
            return true;" onmouseout="window.status = '';
            return true;"<? if ($Before == "b") echo ' checked="checked"'; ?>>
                <font class="fontSpecial">Before Event Release Date</font><br clear="all" />
            </p>
            <p>
                <input type="radio" name="BeforeDate" id="BeforeDate" value="s" class="CstmFrmElmnt" title="After Event Release Date" onmouseover="window.status = 'After Event Release Date';
            return true;" onmouseout="window.status = '';
            return true;"<? if ($Before == "s") echo ' checked="checked"'; ?>>
                <font class="fontSpecial">After Event Release Date</font><br clear="all" />
            </p>
            <p>
                <input type="radio" name="BeforeDate" id="BeforeDate" value="e" class="CstmFrmElmnt" title="Before Event Expiration Date" onmouseover="window.status = 'Before Event Expiration Date';
            return true;" onmouseout="window.status = '';
            return true;"<? if ($Before == "e") echo ' checked="checked"'; ?>>
                <font class="fontSpecial">Before Event Expiration Date</font><br clear="all" />
            </p>
        </span><br clear="all" />
        <div class="DividerBlack"></div>
        <span>
            <label for="Image" class="CstmFrmElmntLabel">Image</label>
            <input type="file" name="Image" id="Image" title="Notification Image" onmouseover="window.status = 'Notification Image';
            return true;" onmouseout="window.status = '';
            return true;" />
                   <? if ($Imagev === true) { ?>
                &nbsp;<a href="/PhotoCP/downloader.php?type=5&id=<? echo urlencode(base64_encode($ENId)); ?>" target="_blank">View</a>
            <? } ?>
            <p><font  class="Small">Image will display 150 pixels wide in the top left corner</font></p>
        </span>
        <span>
            <p>
                <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" class="CstmFrmElmnt" title="Remove Notification Image" onmouseover="window.status = 'Remove Notification Image';
            return true;" onmouseout="window.status = '';
            return true;" />
                <font class="fontSpecial">Remove Image</font><br clear="all" />
            </p>
        </span> <br clear="all" />
    </div>
</div>
<div id="TinyMCE">

    <div class="TinyMCE">
        <?php
        ?>
        <!-- TinyMCE -->
        <script type="text/javascript" src="/PhotoCP/TinyMCE/3.5.8/tiny_mce.js"></script>
        <script type="text/javascript">
        tinyMCE.init({
            // General options
            mode: "exact",
            elements: "Text",
            theme: "advanced",
            skin: "o2k7",
            skin_variant: "black",
            plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
            // Theme options
            theme_advanced_buttons1: "bold,italic,underline,|,cut,copy,paste,pastetext,pasteword,|,search,replace",
            theme_advanced_buttons2: "",
            theme_advanced_buttons3: "",
            theme_advanced_buttons4: "",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_statusbar_location: "bottom",
            theme_advanced_resizing: true
        });
        </script>
        <!-- /TinyMCE -->
        <textarea id="Text" name="Text" style="width:700px; height:300px;"><? echo $Desc; ?></textarea>
        <br clear="all" />
    </div>
    <div id="Bottom"></div>
</div>
<br clear="all" />
<h1 id="HdrType2" class="UpcmngEvnt">
    <div>Event List</div>
</h1>
<div id="RecordTable" class="Red">
    <div id="Top"></div>
    <div id="Records">
        <p class="Error">Select Events that you would like to apply Event Notifications to.</p>
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
    ORDER BY `photo_event`.`event_name`;");
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
        <? if ($getEvents->TotalRows() > 0) { ?>
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
                        <td><input type="checkbox" name="Events[]" id="Event_items_<? echo ($k + 1); ?>" value="<? echo $r['event_id']; ?>"<?php if (in_array($r['event_id'], $NoteEvents)) echo ' checked="checked"'; ?> /></td>
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