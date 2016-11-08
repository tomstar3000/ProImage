<? if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_format_phone.php'; 
require_once $r_path.'scripts/fnct_clean_entry.php';

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
	$Sort_val = " ORDER BY `cust_customers`.`cust_lname` ".$Order.", `cust_customers`.`cust_fname` ".$Order;
	$Addselect = "";
	$Innerjoin = "";
} else if ($Sort == "Address"){
	$Sort_val = " ORDER BY `cust_customers`.`cust_city` ".$Order.",`cust_customers`.`cust_state` ".$Order.",`cust_customers`.`cust_zip` ".$Order;
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

$FName = (isset($_POST['First_Name'])) ? clean_variable($_POST['First_Name'],true) : '';
$LName = (isset($_POST['Last_Name'])) ? clean_variable($_POST['Last_Name'],true) : '';
$Email = (isset($_POST['Email'])) ? clean_variable($_POST['Email'],true) : '';
$Phone = (isset($_POST['Phone_Number'])) ? clean_variable(preg_replace("/[^0-9]/", "",$_POST['Phone_Number']),true) : '';

$HotMenu = "Clnt,Search:query"; $Key = array_search($HotMenu, $StrArray);

define('NoSave',true);
if(isset($required_string))
	$onclick = 'MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; document.getElementById(\'form_action_form\').submit();}';
else 
	$onclick = 'document.getElementById(\'Controller\').value = \'false\'; document.getElementById(\'form_action_form\').submit();'; ?>

<h1 id="HdrType2" class="ClntListClnt">
  <div>Search Customer List</div>
</h1>
<div id="HdrLinks"><a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Search'; return true;" onmouseout="window.status=''; return true;" title="Search" class="BtnSearch">Search</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn2"> <span>
    <label for="First_Name" class="CstmFrmElmntLabel">First Name</label>
    <input type="text" name="First Name" id="First_Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='First Name'; return true;" onmouseout="window.status=''; return true;" title="First Name" class="CstmFrmElmntInput" value="<? echo $FName; ?>" />
    </span><span>
    <label for="Last_Name" class="CstmFrmElmntLabel">Last Name</label>
    <input type="text" name="Last Name" id="Last_Name" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Last Name'; return true;" onmouseout="window.status=''; return true;" title="Last Name" class="CstmFrmElmntInput" value="<? echo $LName; ?>" />
    </span> <br clear="all" />
    <span>
    <label for="Email" class="CstmFrmElmntLabel">E-mail Address</label>
    <input type="text" name="Email" id="Email" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail Address'; return true;" onmouseout="window.status=''; return true;" title="E-mail Address" class="CstmFrmElmntInput" value="<? echo $Email; ?>" />
    </span> <span>
    <label for="Phone_Number" class="CstmFrmElmntLabel">Phone Number</label>
    <input type="text" name="Phone Number" id="Phone_Number" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Phone Number'; return true;" onmouseout="window.status=''; return true;" title="Phone Number" class="CstmFrmElmntInput" value="<? if(is_numeric($Phone))echo phone_number($Phone); ?>" />
    </span><br clear="all" />
    <p align="right">
      <input type="submit" name="BtnSearch" id="BtnSearch" value="Search" title="Search" onclick="javascript: <? echo $onclick; ?>" />
    </p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<?
if($FName != '' || $LName != '' || $Email != '' || $Phone != ''){
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT `cust_customers`.`cust_id`,`cust_customers`.`cust_fname`,`cust_customers`.`cust_lname`,`cust_customers`.`cust_city`,`cust_customers`.`cust_state`,`cust_customers`.`cust_zip`,`cust_customers`.`cust_phone`,`cust_customers`.`cust_email` FROM `photo_event_images` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` INNER JOIN `orders_invoice` ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` WHERE `photo_event_images`.`cust_id` = '$CustId' AND (`cust_customers`.`cust_fname` LIKE '%".$FName."%' AND `cust_customers`.`cust_lname` LIKE '%".$LName."%' AND `cust_customers`.`cust_email` LIKE '%".$Email."%' AND `cust_customers`.`cust_phone` LIKE '%".$Phone."%') GROUP BY `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, `cust_customers`.`cust_email`".$Sort_val.";"); ?>
<div id="RecordTable" class="Yellow">
  <div id="Top"></div>
  <div id="Records">
    <? if($getInfo->TotalRows() > 0){ ?>
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Customer Name,<? echo ($Sort == "Customer Name" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Customer Name'; return true;" onmouseout="window.status=''; return true;" title="Sort By Customer Name">Customer Name</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Address,<? echo ($Sort == "Address" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Address'; return true;" onmouseout="window.status=''; return true;" title="Sort By Address">Address</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'Phone Number,<? echo ($Sort == "Phone Number" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By Phone Number'; return true;" onmouseout="window.status=''; return true;" title="Sort By Phone Number">Phone Number</a></th>
        <th><a href="javascript:document.getElementById('form_sort').value = 'E-mail,<? echo ($Sort == "E-mail" && $Order == "ASC")?'DESC':'ASC'; ?>'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Sort By E-mail'; return true;" onmouseout="window.status=''; return true;" title="Sort By E-mail">E-mail</a></th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getInfo->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','".implode(",",array_slice($path,0,1)).",Clnt,".$r['cust_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getInfo->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getInfo->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getInfo->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>"><? echo $r['cust_lname'].", ".$r['cust_fname']; ?></a></td>
        <td><? echo $r['cust_city']." ".$r['cust_state']." ".$r['cust_zip']; ?></td>
        <td><? echo phone_number($r['cust_phone']); ?></td>
        <td><? echo $r['cust_email']; ?></td>
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
<? } ?>
