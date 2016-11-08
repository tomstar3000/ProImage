<?php
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
    <?php if ($is_enabled) { ?>
        <tr>
            <th colspan="2" id="Form_Header">
        <div id="Add">
            <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<?php echo implode(",", $path); ?>', '<?php echo $is_back; ?>', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" />
        </div>
        <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
        <p>Admin Messages Information</p>
    </th>
    </tr>
<?php } else { ?>
    <tr>
        <th colspan="2" id="Form_Header">
    <div id="Add">
        <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_', '<?php echo implode(",", array_slice($path, 0, 3)); ?>', 'edit', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" />
        <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<?php echo implode(",", array_slice($path, 0, 2)); ?>', '<?php echo $is_back; ?>', '<?php echo $sort; ?>', '<?php echo $rcrd; ?>');" />
    </div>
    <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Admin Messages Information</p>
    </th>
    </tr>
<?php } ?>
<tr>
    <td><strong>Name:</strong></td>
    <td width="75%"><?php
        if (!$is_enabled) {
            echo $NName;
        } else {
            ?>
            <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $NName; ?>" />
        <?php } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Active:</strong></td>
    <td><?php
        if (!$is_enabled) {
            if ($NAct == 1) {
                print("Yes");
            } else {
                print("No");
            }
        } else {
            ?>
            <input type="radio" name="Active" id="Active" value="y"<?php
            if ($NAct == 1) {
                print(" checked=\"checked\"");
            }
            ?> />
            Yes
            <input type="radio" name="Active" id="Active" value="n"<?php
            if ($NAct == 0) {
                print(" checked=\"checked\"");
            }
            ?> />
            No
        <?php } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Expiration:<br />(Leave blank for never ending) </strong></td>
    <td><?
        if (!$is_enabled) {
            if ($NDiscon != "") {
                echo format_date($NDiscon, "Dash", false, true, true);
            } else {
                echo 'Never';
            }
        } else {
            ?>
            <input type="text" name="Expiration" id="Expiration" maxlength="75" value="<?
            if ($NDiscon != "") {
                echo format_date($NDiscon, "Dash", false, true, true);
            }
            ?>" />
            <a href="#" onclick="newwindow = window.open('scripts/calendar.php?future=true&time=false&field=Expiration', '', 'scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no');
                        newwindow.opener = self;"> Select Date</a> (yyyy-mm-dd)
           <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td colspan="2" style="padding:10px;<? if ($is_enabled) { ?> height:300px;<? } ?>" valign="top"><?
        if (!$is_enabled) {
            echo $NDesc;
        } else {
            $oFCKeditor = new FCKeditor('Description');
            $oFCKeditor->BasePath = 'FCKeditor/';
            $oFCKeditor->Value = $NDesc;
            $oFCKeditor->Width = '100%';
            $oFCKeditor->Height = '290';
            $oFCKeditor->Create();
            $output = $oFCKeditor->CreateHtml();
        }
        ?>
        &nbsp;</td>
</tr>
</table>
