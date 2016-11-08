<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
include $r_path . 'security.php';
require_once $r_path . 'scripts/fnct_format_date.php';
if ($path[0] == 'Evnt')
    $EId = $path[2];
if (isset($_POST['Controller']) && $_POST['Controller'] == "Delete") {
    $items = array();
    $items = $_POST['Discount_items'];
    foreach ($items as $key => $value) {
        $rmvInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
        $rmvInfo->mysql("UPDATE `prod_discount_codes` SET `disc_use` = 'n' WHERE `disc_id` = '$value';");
        $rmvInfo->mysql("OPTIMIZE TABLE `prod_discount_codes`;");
    }
} else if (isset($_POST['Controller']) && $_POST['Controller'] == "Save" && isset($_POST['Discount_items'])) {
    $items = array();
    $items = $_POST['Discount_items'];
    $getAction = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getAction->mysql("DELETE FROM `photo_event_disc` WHERE `event_id` = '$EId' AND `disc_type` = 'g';");
    $getAction->mysql("OPTIMIZE TABLE `photo_event_disc`;");
    foreach ($items as $key => $value) {
        $getAction->mysql("INSERT INTO `photo_event_disc` (`event_id`,`disc_id`,`disc_type`) VALUES ('$EId','$value','g');");
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

if ($Sort == "Name") {
    $Sort_val = " ORDER BY `disc_name` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Code") {
    $Sort_val = " ORDER BY `disc_code` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "E-mail") {
    $Sort_val = " ORDER BY `disc_email` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Price") {
    $Sort_val = " ORDER BY `disc_price` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
} else if ($Sort == "Redeemed") {
    $Sort_val = " ORDER BY `disc_total` " . $Order;
    $Addselect = "";
    $Innerjoin = "";
}
if ($path[0] == 'Evnt') {
    $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getInfo->mysql("SELECT `prod_discount_codes`.*, `photo_event_disc`.`event_disc_id`, SUM(`orders_invoice_codes`.`disc_total`) AS `disc_total`
		FROM `prod_discount_codes` 
		INNER JOIN `photo_event_disc`
			ON (`prod_discount_codes`.`disc_id` = `photo_event_disc`.`disc_id`
				AND `photo_event_disc`.`event_id` = '$EId')
		LEFT JOIN `orders_invoice_codes`
			ON (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
				OR `orders_invoice_codes`.`disc_id` IS NULL)
		WHERE `prod_discount_codes`.`cust_id` = '$CustId' AND `prod_discount_codes`.`disc_use` = 'y' AND `prod_discount_codes`.`disc_type` = 'g'
		GROUP BY `prod_discount_codes`.`disc_id`
		" . $Sort_val . ";");
} else {
    $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getInfo->mysql("SELECT `prod_discount_codes`.*, SUM(`orders_invoice_codes`.`disc_total`) AS `disc_total`
		FROM `prod_discount_codes` 
		LEFT JOIN `orders_invoice_codes`
			ON (`orders_invoice_codes`.`disc_id` = `prod_discount_codes`.`disc_id`
				OR `orders_invoice_codes`.`disc_id` IS NULL)			
		WHERE `prod_discount_codes`.`prod_id` = '0' 
			AND `prod_discount_codes`.`cust_id` = '$CustId' 
			AND `prod_discount_codes`.`disc_use` = 'y' 
			AND `prod_discount_codes`.`disc_type` = 'g'
			
		GROUP BY `prod_discount_codes`.`disc_id` 
		" . $Sort_val . ";");
    //AND (	`disc_total` IS NULL || `disc_total` < `prod_discount_codes`.`disc_price` )
}
$HotMenu = "Busn,Gift:query";
$Key = array_search($HotMenu, $StrArray);
?>

<h1 id="HdrType2" class="<?
switch ($path[0]) {
    case 'Evnt': echo 'EvntMarket';
        break;
    case 'Busn': echo 'BsnDiscCodes';
        break;
    default: echo 'EvntMarket';
        break;
}
?>">
    <div>Gift Certificates </div>
</h1>
<div id="HdrLinks">
    <? //if($path[0]!='Evnt'){ ?>
    <a href="javascript:document.getElementById('Controller').value = 'Delete'; document.getElementById('form_action_form').submit();" onclick="javascript:if (confirm('Are you sure you want to remove these Gift Certificates'))
                return true;
            else
                return false;" onmouseover="window.status = 'Remove Gift Certificates';
            return true;" onmouseout="window.status = '';
            return true;" title="Remove Gift Certificates" class="BtnDel">Remove Gift Certificates</a>
       <? //}  ?>
    <a href="#" onclick="javascript:document.getElementById('Controller').value = 'false';
            set_form('', '<? echo implode(",", $path); ?>', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
            return false;" onmouseover="window.status = 'Add New Gift Certificates';
            return true;" onmouseout="window.status = '';
            return true;" title="Add New Gift Certificates" class="BtnAdd">Add New Gift Certificates</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key !== false) ? '2' : '1'; ?>);" title="Add to Hot Menu" onmouseover="window.status = 'Add to Hot Menu';
            return true;" onmouseout="window.status = '';
            return true;" class="BtnHotMenu<? echo ($Key !== false) ? 'Added' : ''; ?>" id="BtnHotMenu">Add to Hot Menu</a>
       <? /* if($path[0]=='Evnt'){ ?>
         <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Gift Certificates'; return true;" onmouseout="window.status=''; return true;" title="Save Gift Certificates" class="BtnSave">Save</a>
         <? } */ ?>
</div>
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
            <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
                <tr>
                    <th><input type="checkbox" name="CheckAll" id="CheckAll" value="" onclick="javascript:goCheckAll('CheckAll', 'TableRecords1');" /></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Name,<? echo ($Sort == "Name" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Name';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By Name">Name</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Code,<? echo ($Sort == "Code" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Code';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By Code">Code</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Percent,<? echo ($Sort == "E-mail" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By E-mail';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By E-mail">E-mail</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Price,<? echo ($Sort == "Price" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort By Price';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort By Price">Price</a></th>
                    <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",", $path); ?>','<? echo $cont; ?>','Redeemed,<? echo ($Sort == "Redeemed" && $Order == "ASC") ? 'DESC' : 'ASC'; ?>','');" onmouseover="window.status = 'Sort Amount Redeemed';
                return true;" onmouseout="window.status = '';
                return true;" title="Sort Amount Redeemed">Redeemed</a></th>
                    <th class="R">&nbsp;</th>
                </tr>
                <?
                foreach ($getInfo->Rows() as $k => $r) {
                    $EvntAction = "javascript:set_form('','" .
                            (($path[0] == 'Evnt') ? implode(",", array_slice($path, 0, 4)) : implode(",", array_slice($path, 0, 2)))
                            . "," . $r['disc_id'] . "','view','" . $sort . "','" . $rcrd . "');";
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
                        <td><input type="checkbox" name="Discount_items[]" id="Discount_items_<? echo ($k + 1); ?>" value="<? echo $r['disc_id']; ?>"<? // if($path[0]=='Evnt' && $r['event_disc_id'] != NULL) echo ' checked="checked"';     ?> /></td>
                        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status = 'Edit Discount <? echo str_replace("'", "\'", $r['disc_name']); ?>';
                    return true;" onmouseout="window.status = '';
                    return true;" title="Edit Discount <? echo str_replace("'", "\'", $r['disc_name']); ?>"><? echo ((strlen($r['disc_name']) > 30) ? substr($r['disc_name'], 0, 30) . "..." : $r['disc_name']); ?></a></td>
                        <td><? echo $r['disc_code']; ?></td>
                        <td><? echo $r['disc_email']; ?></td>
                        <td><? echo "$" . number_format($r['disc_price'], 2, ".", ","); ?></td>
                        <td><? echo "$" . number_format($r['disc_total'], 2, ".", ","); ?></td>
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
