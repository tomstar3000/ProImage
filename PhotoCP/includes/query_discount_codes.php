<?php
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_format_date.php';
if (isset($_POST['Controller']) && $_POST['Controller'] == "Delete") {
    $items = array();
    $items = $_POST['Discount_items'];
    foreach ($items as $key => $value) {
        $rmvInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $rmvInfo->mysql("UPDATE `prod_discount_codes` SET `disc_use` = 'n' WHERE `disc_id` = '$value';");
        $rmvInfo->mysql("OPTIMIZE TABLE `prod_discount_codes`;");
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
    $Sort_val = " ORDER BY `disc_name` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Code") {
    $Sort_val = " ORDER BY `disc_code` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Percent") {
    $Sort_val = " ORDER BY `disc_percent` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Exp.") {
    $Sort_val = " ORDER BY `disc_exp` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Used") {
    $Sort_val = " ORDER BY `disc_num_uses` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
}

$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT * FROM `prod_discount_codes`  WHERE `prod_id` = '0' AND `cust_id` = '$CustId' AND `disc_use` = 'y' AND `disc_type` = 's' " . $Sort_val . ";");
$HotMenu = "Busn,Disc:query";
$Key = array_search($HotMenu, $StrArray);
?>

<h1 id="HdrType2" class="<?
switch ($path[0]) {
    case 'Clnt': echo 'EvntMarket';
        break;
    case 'Busn': echo 'BsnDiscCodes';
        break;
    default: echo 'EvntMarket';
        break;
}
?>">
    <div>Custom Discount Codes</div>
</h1>
<div id="HdrLinks"><a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_action_form').submit();" onclick="javascript:if (confirm('Are you sure you want to remove these Discounts'))
            return true;
        else
            return false;" onmouseover="window.status = 'Remove Discounts';
        return true;" onmouseout="window.status = '';
        return true;" title="Remove Discounts" class="BtnDel">Remove Discounts</a>
    <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false';
        set_form('', '<? echo implode(",", $path); ?>', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
        return false;" onmouseover="window.status = 'Add New Discount Code';
        return true;" onmouseout="window.status = '';
        return true;" title="Add New Discount Code" class="BtnAdd">Add New Discount Code</a>
    <a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key !== false) ? '2' : '1'; ?>);" title="Add to Hot Menu" onmouseover="window.status = 'Add to Hot Menu';
        return true;" onmouseout="window.status = '';
        return true;" class="BtnHotMenu<? echo ($Key !== false) ? 'Added' : ''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
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
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Code Name,<? echo ($Sort == "Code Name" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Code Name';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Code Name">Code Name</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Code,<? echo ($Sort == "Code" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Code';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Code">Code</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Percent,<? echo ($Sort == "Percent" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Percent';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Percent">Percent</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Exp." && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Event Expiration Date';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Expiration Date">Exp.</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Used,<? echo ($Sort == "Used" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Number Times Used';
            return true;" onmouseout="window.status = '';
            return true;" title="Sort By Number Times Used">Used</a></th>
                    <th class="R">&nbsp;</th>
                </tr>
                <?
                foreach ($getInfo->Rows() as $k => $r) {
                    $EvntAction = "javascript:set_form('','" . implode(",", array_slice($path, 0, 2)) . "," . $r['disc_id'] . "','view','" . $sort . "','" . $rcrd . "');";
                    $class1 = "";
                    $class2 = "ROver";
                    if (intval($k % 2) == 1) {
                        $class1 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . 'SRow';
                    } else if ($k == ($getInfo->TotalRows() - 1))
                        $class1 = 'B';
                    $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;

                    $getUsed = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
                    $getUsed->mysql("SELECT COUNT(`disc_id`) as `code_count` FROM `orders_invoice_codes`  WHERE `disc_id` = '" . $r['disc_id'] . "';");
                    $getUsed = $getUsed->Rows();
                    ?>
                    <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?> onMouseOver="this.className = '<? echo $class2; ?>';" onMouseOut="this.className = '<? echo $class1; ?>';">
                        <td><input type="checkbox" name="Discount_items[]" id="Discount_items_<? echo ($k + 1); ?>" value="<? echo $r['disc_id']; ?>" /></td>
                        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Discount <? echo str_replace("'", "\'", $r['disc_name']); ?>';
                return true;" onmouseout="window.status = '';
                return true;" title="Edit Discount <? echo str_replace("'", "\'", $r['disc_name']); ?>"><? echo ((strlen($r['disc_name']) > 30) ? substr($r['disc_name'], 0, 30) . "..." : $r['disc_name']); ?></a></td>
                        <td><? echo $r['disc_code']; ?></td>
                        <td><? echo $r['disc_percent']; ?></td>
                        <td><? echo format_date($r['disc_exp'], "Dash", false, true, false); ?></td>
                        <td><? echo $getUsed[0]['code_count'] . ' / ' . $r['disc_num_uses']; ?></td>
                        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Event <? echo str_replace("'", "\'", $r['disc_name']); ?>';
                return true;" onmouseout="window.status = '';
                return true;" title="Edit Event <? echo str_replace("'", "\'", $r['disc_name']); ?>">Open</a></td>
                    </tr>
                <? } ?>
            </table>
        <? } else { ?>
            <p>There are no records on file</p>
        <? } ?>
    </div>
    <div id="Bottom"></div>
</div>
