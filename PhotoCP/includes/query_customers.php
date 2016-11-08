<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_phone.php';

if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$Sort = "Customer Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order;
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Customer Name"){
	$Sort_val = " ORDER BY `cust_lname` ".$Order.", `cust_fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Address"){
	$Sort_val = " ORDER BY `cust_city` ".$Order.", `cust_state` ".$Order.", `cust_zip` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Phone Number"){
	$Sort_val = " ORDER BY `cust_phone` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "E-mail"){
	$Sort_val = " ORDER BY `cust_email` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
}
$HotMenu = "Clnt,Clnt:query"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="ClntListClnt">
  <div>Customer List</div>
</h1>
<div id="HdrLinks"> <a href="#" onclick="javascript:set_form('','<? echo implode(",",array_slice($path,0,2)); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Add Customer'; return true;" onmouseout="window.status=''; return true;" title="Add Customer" class="BtnAdd">Add Customer</a>

<a href="#" onclick="javascript:set_form('','<? echo implode(",",array_slice($path,0,1)); ?>,Search','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Customer Search'; return true;" onmouseout="window.status=''; return true;" title="Customer Search" class="BtnSearch">Customer Search</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<div id="RecordTabs"> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Clnt"; ?>','query','','');" id="BtnClntAll"<? if($path[1]=="Clnt" && $cont!="add") echo ' class="NavSel"'; ?> title="View All Clients">View All Clients</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Clnt"; ?>','add','','');" id="BtnClntNew"<? if($path[1]=="Clnt" && $cont=="add") echo ' class="NavSel"'; ?> title="Create New Client">Create New Client</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",Search"; ?>','query','','');" id="BtnClntSrch"<? if($path[1]=="Search") echo ' class="NavSel"'; ?> title="Search Clients">Search Clients</a> <a href="javascript: set_form('','<? echo implode(",",array_slice($path,0,1)).",SrchSvd"; ?>','query','','');" id="BtnClntSrchSvd"<? if($path[1]=="SrchSvd") echo ' class="NavSel"'; ?> title="Saved Searches">Saved Searches</a><br clear="all" />
</div>
<? 
$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT * FROM (
									SELECT * FROM (
										SELECT `cust_customers`.`cust_id`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_city`, `cust_customers`.`cust_state`, `cust_customers`.`cust_zip`, `cust_customers`.`cust_phone`, `cust_customers`.`cust_email` 
										FROM `photo_event_images`
										INNER JOIN `orders_invoice_photo`
											ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id`
										INNER JOIN `orders_invoice`
											ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
										INNER JOIN `cust_customers` 
											ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
										WHERE `photo_event_images`.`cust_id` = '$CustId' 
											AND `cust_photo` = 'n'
										GROUP BY `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`
										ORDER BY `cust_customers`.`cust_id` ASC
									) AS `DV1`
									
										
									UNION ALL 
									SELECT * FROM (
										SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_city`, `cust_state`, `cust_zip`, `cust_phone`, `cust_email` 
										FROM `cust_customers` 
										WHERE `photo_id` = '$CustId'
											AND `cust_photo` = 'n'
										GROUP BY `cust_fname`, `cust_lname`, `cust_email`
										ORDER BY `cust_id` ASC
									) AS `DV2`
									UNION ALL 
										SELECT * FROM (
										SELECT CONCAT('SpcMrch,',`invoice_id`) AS `cust_id`, `fname` AS `cust_fname`, `lname` AS `cust_lname`, `city` AS `cust_city`, `state` AS `cust_state`, `zip` AS `cust_zip`, `phone` AS `cust_phone`, `email` AS `cust_email` 
										FROM `photo_invoices` 
										WHERE `cust_id` = '$CustId'
										GROUP BY `cust_fname`, `cust_lname`, `cust_email`
										ORDER BY `cust_id` ASC
										) AS `DV3`
									) AS `DT1`
								GROUP BY `cust_fname`, `cust_lname`, `cust_email`
								".$Sort_val." ;"); ?>
<div id="RecordTable" class="<? switch($path[0]){
	case 'Evnt': echo 'Red'; break;
	case 'Clnt': echo 'Yellow'; break;
	case 'Busn': echo 'Green'; break;
	default: echo 'Red'; break; } ?>">
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Customer Name,<? echo ($Sort == "Customer Name" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Customer Name</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Address,<? echo ($Sort == "Address" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Address'; return true;" onmouseout="window.status=''; return true;" title="Sort By Address">Address</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','Phone Number,<? echo ($Sort == "Phone Number" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By Phone Number'; return true;" onmouseout="window.status=''; return true;" title="Sort By Phone Number">Phone Number</a></th>
        <th><a href="javascript:document.getElementById('Controller').value = 'false'; set_form('form_','<? echo implode(",",$path); ?>','<? echo $cont; ?>','E-mail,<? echo ($Sort == "E-mail" && $Order == "ASC")?'DESC':'ASC'; ?>','');" onmouseover="window.status='Sort By E-mail'; return true;" onmouseout="window.status=''; return true;" title="Sort By E-mail">E-mail</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,2)).",".$r['cust_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>"><? echo $r['cust_lname'].", ".$r['cust_fname']; ?></a></td>
        <td><? echo $r['cust_city']." ".$r['cust_state']." ".$r['cust_zip']; ?></td>
        <td><? echo phone_number($r['cust_phone']); ?></td>
        <td style="max-width: 110px; text-overflow: ellipsis; overflow: hidden;"><? echo $r['cust_email']; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
    <? } else { ?>
    <p>There are no records on file</p>
    <? } ?>
  </div>
  <div id="Bottom"></div>
</div>