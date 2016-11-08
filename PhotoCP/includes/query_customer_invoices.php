<? if(isset($r_path)===false){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once($r_path.'scripts/fnct_format_date.php');
?>

<h1 id="HdrType2-5" class="ClntInvClnt">
  <div>Invoices</div>
</h1>

<div id="btnCollapse"><a href="#" onclick="javascript: Open_Sec('CstmrInvs',this); return false;" onmouseover="window.status='Expand Event Information'; return true;" onmouseout="window.status=''; return true;" title="Expand Event Information">+</a></div>
<?
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
if($path[2] == "SpcMrch"){
	$getInfo->mysql("SELECT `fname` AS `cust_fname` , `lname` AS `cust_lname` ,`email` AS `cust_email` FROM `photo_invoices` WHERE `invoice_id` = '$CId';");
} else {
	$getInfo->mysql("SELECT `cust_fname` ,`cust_lname` ,`cust_email` FROM `cust_customers` WHERE `cust_id` = '$CId';");
}
$getInfo = $getInfo->Rows();

$temp_fname = $getInfo[0]['cust_fname'];
$temp_lname = $getInfo[0]['cust_lname'];
$temp_email = $getInfo[0]['cust_email'];

if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Invoice Number";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Invoice Number"){
	$Sort_val = " ORDER BY `invoice_num` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if($Sort == "Date"){
	$Sort_val = " ORDER BY `invoice_date` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Total"){
	$Sort_val = " ORDER BY `invoice_total` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Customer Name"){
	$Sort_val = " ORDER BY `cust_fname` ".$Order.", `cust_lname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Number of Images"){
	$Sort_val = " ORDER BY `count_image_ids` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} 

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("( SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` 
	FROM `photo_event_images` 
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id`
	WHERE `cust_customers`.`cust_fname` = '$temp_fname' 
		AND `cust_customers`.`cust_lname` = '$temp_lname' 
		AND `cust_customers`.`cust_email` = '$temp_email'
		AND `photo_event_images`.`cust_id` = '$CustId'
	GROUP BY `orders_invoice`.`invoice_id`)
UNION
	( SELECT `orders_invoice`.*, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_border`.`image_id` ) AS `count_image_ids` 
	FROM `photo_event_images` 
	INNER JOIN `orders_invoice_border` 
		ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
	WHERE `cust_customers`.`cust_fname` = '$temp_fname' 
		AND `cust_customers`.`cust_lname` = '$temp_lname' 
		AND `cust_customers`.`cust_email` = '$temp_email' 
		AND `photo_event_images`.`cust_id` = '$CustId'
	GROUP BY `orders_invoice`.`invoice_id` )
	".$Sort_val.";"); ?>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>"> <a id="CstmrInvs"></a>
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Invoice Number,<? echo ($Sort == "Invoice Number" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Invoice Number'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Name">Invoice Number</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Date,<? echo ($Sort == "Date" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Date'; return true;" onmouseout="window.status=''; return true;" title="Sort By Event Date">Date</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Total,<? echo ($Sort == "Total" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Total'; return true;" onmouseout="window.status=''; return true;" title="Sort By Total">Total</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Customer Name,<? echo ($Sort == "Customer Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Customer Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Number of Images,<? echo ($Sort == "Number of Images" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Number of Images'; return true;" onmouseout="window.status=''; return true;" title="Sort By Number of Images">Number of Images</a></th>
        <th>&nbsp;</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,3)).",Inv,".$r['invoice_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['invoice_num']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice Number <? echo str_replace("'","\'",$r['invoice_num']); ?>"><? echo $r['invoice_num']; ?></a></td>
        <td><? echo format_date($r['invoice_date'],"Short",false,true,false); ?></td>
        <td><? echo "$".number_format($r['invoice_total'],2,".",","); ?></td>
        <td><? echo $r['cust_fname']." ".$r['cust_lname']; ?></td>
        <td><? echo $r['count_image_ids']; ?></td>
        <td><? echo '<a href="/checkout/invoice.php?invoice='.$r['invoice_enc'].'" target="_blank">View</a>'; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Invoice Number <? echo str_replace("'","\'",$r['invoice_num']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Invoice Number <? echo str_replace("'","\'",$r['invoice_num']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>
