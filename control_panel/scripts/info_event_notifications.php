<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++) {
        $r_path .= "../";
    }
}
include $r_path . 'security.php';
include $r_path . 'FCKeditor/fckeditor.php';
$is_enabled = ((count($path) <= 3 && $cont == "view") || (count($path) > 3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <? if ($is_enabled) { ?>
        <tr>
            <th colspan="4" id="Form_Header">
        <div id="Add">
            <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<? echo implode(",", $path); ?>', '<? echo $is_back; ?>', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" />
        </div>
        <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
        <p>Event Notification</p>
    </th>
    </tr>
<? } else { ?>
    <tr>
        <th colspan="4" id="Form_Header">
    <div id="Add">
        <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_', '<? echo implode(",", array_slice($path, 0, 3)); ?>', 'edit', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<? echo implode(",", array_slice($path, 0, 2)); ?>', '<? echo $is_back; ?>', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" />
    </div>
    <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Event Notification</p>
    </th>
    </tr>
<?php } ?>
<tr>
    <td width="20%"><strong>Product Name: </strong></td>
    <td colspan="3"><?
        if (!$is_enabled) {
            echo $Name;
        } else {
            ?>
            <input type="text" name="Name" id="Name" maxlength="75" value="<? echo $Name; ?>" />
        <? } ?>
        &nbsp;</td>

</tr>
<tr>
    <td><strong>On Date:</strong></td>
    <td><?
        if (!$is_enabled) {
            if ($Date != "") {
                echo format_date($Date, "DayShort", false, true, true);
            }
        } else {
            ?>
            <input type="text" name="On Date" id="On_Date" maxlength="75" value="<? echo date('Y-m-d', strtotime($Date)); ?>" />
            <a href="#" onclick="newwindow = window.open('scripts/calendar.php?future=true&time=false&field=On_Date', '', 'scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no');
                        newwindow.opener = self;"> Select
                Date</a> (yyyy-mm-dd)
        <? } ?>
        &nbsp;</td>
    <td>
        <?php
        if (!$is_enabled) {
            echo ($Before == "") ? 'X' : '';
        } else {
            ?>
            <input type="radio" name="BeforeDate" id="BeforeDate" value="" onclick="document.getElementById('Days').value = 0;" title="On Specified Date" <? if ($Before == "") echo ' checked="checked"'; ?> />
        <?php } ?>
    </td>
    <td>On Specified Date</td>
</tr>
<tr>
    <td><strong>Number of Days</strong></td>
    <td> 
        <?php
        if (!$is_enabled) {
            echo $Days;
        } else {
            ?>
            <input type="text" name="Days" id="Days" title="Number of Days for Notification" value="<? echo $Days; ?>" />
        <?php } ?>
    </td>
    <td>
        <?php
        if (!$is_enabled) {
            echo ($Before == "b") ? 'X' : '';
        } else {
            ?>
            <input type="radio" name="BeforeDate" id="BeforeDate" value="b" title="Before Event Release Date" <? if ($Before == "b") echo ' checked="checked"'; ?> />
        <?php } ?>
    </td>
    <td>Before Event Release Date</td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td>
        <?php
        if (!$is_enabled) {
            echo ($Before == "s") ? 'X' : '';
        } else {
            ?>
            <input type="radio" name="BeforeDate" id="BeforeDate" value="s" title="After Event Release Date" <? if ($Before == "s") echo ' checked="checked"'; ?> />
        <?php } ?>
    </td>
    <td>After Event Release Date</td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td><?php
        if (!$is_enabled) {
            echo ($Before == "e") ? 'X' : '';
        } else {
            ?><input type="radio" name="BeforeDate" id="BeforeDate" value="e" title="Before Event Expiration Date" <? if ($Before == "e") echo ' checked="checked"'; ?> />
        <?php } ?>
    </td>
    <td>Before Event Expiration Date</td>
</tr>
<tr>
    <td><strong>Image:</strong></td>
    <td><?php if ($is_enabled) { ?><input type="file" name="Image" id="Image" title="Notification Image" />
        <? } if ($Imagev === true) { ?>
            &nbsp;<a href="/PhotoCP/downloader.php?type=5&id=<? echo urlencode(base64_encode($ENId)); ?>" target="_blank">View</a>
        <? } ?>
    </td>
    <td>
        <? if ($Imagev === true && $is_enabled) { ?>
            <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" title="Remove Notification Image" />
        <?php } ?> &nbsp;
    </td>
    <td><? if ($Imagev === true && $is_enabled) { ?>Remove Image<?php } ?> &nbsp;</td>
</tr>
<tr>
    <td colspan="4">Image will display 150 pixels wide in the top left corner</td>
</tr>
<tr>
    <td colspan="4"><strong>Description:</strong></td>
</tr>
<tr>
    <td colspan="4" style="padding:10px;<? if ($cont == "add" || $cont == "edit") { ?> height:300px;<? } ?>" valign="top">
        <?
        if (!$is_enabled) {
            echo $Desc;
        } else {
            ?>
            <!-- TinyMCE -->
            <script type="text/javascript" src="/PhotoCP/TinyMCE/3.5.8/tiny_mce.js"></script>
            <script type="text/javascript">
                    tinyMCE.init({
                        // General options
                        mode: "exact",
                        elements: "Description",
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
            <textarea id="Description" name="Description" style="width:700px; height:300px;"><? echo $Desc; ?></textarea>
            <?php
//            $oFCKeditor = new FCKeditor('Description');
//            $oFCKeditor->BasePath = 'FCKeditor/';
//            $oFCKeditor->Value = $Desc;
//            $oFCKeditor->Width = '100%';
//            $oFCKeditor->Height = '290';
//            $oFCKeditor->Create();
//            $output = $oFCKeditor->CreateHtml();
        }
        ?>
        &nbsp;</td>
</tr>

</table>
