<?php
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
$EId = $path[2];
include $r_path . 'security.php';
if (isset($_POST['Controller']) && $_POST['Controller'] == "Save") {
    $items = $_POST['Discount_items'];
    $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 'c';");
    $getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");

    $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$items','c');");
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
$onclick = 'document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();';
?>
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
    <div>Coupons</div>
</h1>
<div id="HdrLinks"><a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status = 'Save Coupons';
        return true;" onmouseout="window.status = '';
        return true;" title="Save Coupons" class="BtnSave">Save</a></div>
<?
$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT `prod_discount_codes`.* , `photo_event_disc`.`event_disc_id`
	FROM `prod_discount_codes` 
	LEFT JOIN `photo_event_disc`
            ON ((`prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
                    AND `photo_event_disc`.`event_id` = '$EId')
                    OR `photo_event_disc`.`disc_id` IS NULL)
	WHERE `prod_discount_codes`.`cust_id` = '0' AND `prod_discount_codes`.`disc_use` = 'y' AND `prod_discount_codes`.`evnt_mrk_id` = '0' AND `prod_discount_codes`.`disc_type` = 'c'
	" . $Sort_val . ";");


foreach ($getInfo->Rows() as $r) {
    if (strtolower($r['disc_name']) == "none") {
        $Default = $r['disc_id'];
    } else if ($r['event_disc_id'] != NULL) {
        $Default = $r['disc_id'];
        break;
    }
}
?>

<script type="text/javascript">
    function goCheckAll(ID1, ID2){
    var CheckAll = false; if(document.getElementById(ID1).checked == true) CheckAll = true;
    var Boxes = document.getElementById(ID2).getElementsByTagName('input'); var n = 1;
    while(n<Boxes.length){ Boxes[n].checked = CheckAll; n++; } }
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
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th>&nbsp;</th>
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
        } else if ($k == ($getInfo->TotalRows() - 1))
            $class1 = 'B';
        $class2 = (($k == ($getInfo->TotalRows() - 1)) ? 'B' : '') . $class2;
        ?>
                    <tr<? if (strlen(trim($class1)) > 0) echo ' class="' . $class1 . '"'; ?> onMouseOver="this.className = '<? echo $class2; ?>';" onMouseOut="this.className = '<? echo $class1; ?>';">
                        <td><input type="radio" name="Discount_items" id="Discount_items_<? echo ($k + 1); ?>" value="<? echo $r['disc_id']; ?>"<? if ($r['disc_id'] == $Default) echo ' checked="checked"'; ?> /></td>
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