<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
include $r_path . 'FCKeditor/fckeditor.php';
$is_enabled = ((count($path) <= 3 && $cont == "view") || (count($path) > 3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <? if ($is_enabled) { ?>
        <tr>
            <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<? echo implode(",", $path); ?>', '<? echo $is_back; ?>', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" /> </div>
        <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
        <p>Discount Code Information</p></th>
    </tr>
<? } else { ?>
    <tr>
        <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_', '<? echo implode(",", array_slice($path, 0, 3)); ?>', 'edit', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" /><img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_', '<? echo implode(",", array_slice($path, 0, 2)); ?>', '<? echo $is_back; ?>', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" /> </div>
    <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Discount Code Information</p></th>
    </tr>
    <?
}
if ($Error)
    echo "<tr><td colspan=\"2\" style=\"background-color:#FFFFFF; color:#CC0000;\">" . $Error . "</td></tr>";
?>
<tr>
    <td width="20%"><strong>Discount Name: </strong></td>
    <td><?
        if (!$is_enabled) {
            echo $CName;
        } else {
            ?>
            <input type="text" name="Name" id="Name" maxlength="75" value="<? echo $CName; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Discount Code: </strong></td>
    <td><?
        if (!$is_enabled) {
            echo $CCode;
        } else {
            ?>
            <input type="text" name="Code" id="Code" maxlength="50" value="<? echo $CCode; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Discount Type: </strong></td>
    <td><?
        if (!$is_enabled) {
            switch ($CType) {
                case 'c':
                    echo 'Coupon';
                    break;
                case 's':
                default:
                    echo 'Discount Code';
                    break;
            }
        } else {
            ?>
            <select id="Discount_Type" name="Discount_Type">
                <option value="s" title="Discount Code"<?php if ($CType == 's') echo ' selected="selected"'; ?>>Discount Code</option>
                <option value="c" title="Coupon"<?php if ($CType == 'c') echo ' selected="selected"'; ?>>Coupon</option>
            </select>
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Product:</strong></td>
    <td><?
        $query_get_info = "SELECT * FROM `prod_products` WHERE `prod_use` = 'y' AND `prod_border` = 'n' ORDER BY `prod_name` ASC";
        $get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
        $totalRows_get_info = mysql_num_rows($get_info);

        if (!$is_enabled) {
            while ($row_get_info = mysql_fetch_assoc($get_info)) {
                if ($row_get_info['prod_id'] == $Product)
                    echo $row_get_info['prod_name'];
            }
        } else {
            ?>
            <select name="Product" id="Product">
                <option value="0"<? if ($Product == 0) echo ' selected="selected"'; ?>>-- Select Product --</option>
                <? while ($row_get_info = mysql_fetch_assoc($get_info)) { ?>
                    <option value="<? echo $row_get_info['prod_id']; ?>"<? if ($row_get_info['prod_id'] == $Product) echo ' selected="selected"'; ?>>
                        <? echo $row_get_info['prod_name']; ?>
                    </option>
                <? } ?>
            </select>
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Percent:</strong></td>
    <td><?
        if (!$is_enabled) {
            echo $CPercent;
        } else {
            ?>
            <input type="text" name="Percent" id="Percent" maxlength="50" value="<? echo $CPercent; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Price:</strong></td>
    <td><?
        if (!$is_enabled) {
            echo $CPrice;
        } else {
            ?>
            <input type="text" name="Price" id="Price" maxlength="50" value="<? echo $CPrice; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Buy 1 Get 1:</strong></td>
    <td><?
        if (!$is_enabled) {
            echo "Buy " . $CBuy . " Get " . $CGet;
        } else {
            ?>
            Buy
            <input type="text" name="Buy" id="Buy" maxlength="50" value="<? echo $CBuy; ?>" />
            Get
            <input type="text" name="Get" id="Get" maxlength="50" value="<? echo $CGet; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Apply to multiple Items:<br />
            (Buy 1 Get 1)</strong></td>
    <td valign="top"><?
        if (!$is_enabled) {
            echo ($MItems == "y") ? "Yes" : "No";
        } else {
            ?>
            <input type="radio" name="Multiple Items" id="Multiple_Items" value="y"<? if ($MItems == "y") echo ' checked="checked"'; ?> />
            Yes
            <input type="radio" name="Multiple Items" id="Multiple_Items" value="n"<? if ($MItems == "n") echo ' checked="checked"'; ?> />
            No
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>More than Price:</strong></td>
    <td><?
        if (!$is_enabled) {
            echo $CLimit;
        } else {
            ?>
            <input type="text" name="Limit" id="Limit" maxlength="50" value="<? echo $CLimit; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Discontinue:</strong></td>
    <td><?
        if (!$is_enabled) {
            if ($CDiscon != "")
                echo $PDiscon;
        } else {
            ?>
            <input type="text" name="Discontinue" id="Discontinue" maxlength="75" value="<?php echo $CDiscon; ?>" readonly="readonly" onclick="newwindow = window.open('scripts/calendar.php?future=true&time=false&field=Discontinue', '', 'scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no');
                    newwindow.opener = self;" />
            <img src="/<?php echo $AevNet_Path; ?>/images/ico_cal.jpg" width="19" height="22" border="0" align="absbottom" onclick="newwindow = window.open('scripts/calendar.php?future=true&time=false&field=Discontinue', '', 'scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no');
                    newwindow.opener = self;" style="cursor:pointer" /> (mm/dd/yyyy)
             <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Number of Uses:</strong> </td>
    <td><?
        if (!$is_enabled) {
            echo $CUses;
        } else {
            ?>
            <input type="text" name="Number of Uses" id="Number_of_Uses" maxlength="50" value="<? echo $CUses; ?>" />
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td><strong>Image:</strong></td>
    <td colspan="3"><? if ($cont == "add" || $cont == "edit") { ?>
            <input type="file" name="Image" id="Image">
            <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev; ?>">
            <? echo $Disc_IWidth . " x " . $Disc_IHeight; ?>
            <?
        }
        if ($Imagev != "") {
            ?>
            &nbsp;<a href="<? echo $Disc_Folder; ?>/<? echo $Imagev; ?>" target="_blank">View</a>
            <?
        }
        if ($cont == "add" || $cont == "edit") {
            ?>
            &nbsp;
            <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
            Remove Image
        <? } ?>
        &nbsp;</td>
</tr>
<tr>
    <td valign="top"><strong>Note:</strong></td>
    <td colspan="3"><?
        if (!$is_enabled) {
            echo $Note;
        } else {
            ?>
            <textarea name="Code_Note" cols="50" rows="5" id="Code_Note"><? echo $Note; ?>
            </textarea>
        <? } ?>
        &nbsp;</td>
</tr>
</table>
