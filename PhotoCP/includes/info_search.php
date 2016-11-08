<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; 
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';


$Search = clean_variable($_POST['Search'],true);
$ArraySearch = explode(" ",$Search);
$Found = false;
// Event
$getSearch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getSearch->mysql("SELECT DISTINCT COUNT(`photo_event_images`.`image_id`) AS `image_count`,
	(SELECT count(`group_id`) 
		FROM `photo_event_group` 
		WHERE `photo_event_group`.`event_id` = `photo_event`.`event_id` 
			AND `photo_event_group`.`group_use` = 'y')  AS `group_count`,
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
LEFT JOIN `photo_photographers`
	ON (`photo_photographers`.`photo_id` = `photo_event`.`photo_id`
		OR `photo_photographers`.`photo_id` IS NULL)
LEFT JOIN `photo_event_group`
		ON (`photo_event_group`.`event_id` = `photo_event`.`event_id`
		OR `photo_event_group`.`event_id` IS NULL)
		AND `photo_event_group`.`group_use` = 'y'
LEFT JOIN `photo_event_images`
		ON (`photo_event_images`.`group_id` = `photo_event_group`.`group_id`
		OR `photo_event_images`.`group_id` IS NULL)
WHERE 
	(
		`event_num` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`event_name` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`event_desc` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`owner` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`owner_phone` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
		`owner_email` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`owner_location` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`owner_city` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`owner_state` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_fname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
		".((isset($ArraySearch[1]))?"`photo_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
		`photo_lname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
		".((isset($ArraySearch[1]))?"`photo_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
		`photo_add` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_add2` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_suiteapt` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_city` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_state` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_zip` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`photo_phone` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
		`photo_email` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`group_name` LIKE '%".str_replace(" ","%%",$Search)."%' ".
		//`image_tiny` LIKE '%".str_replace(" ","%%",$Search)."%'
	")
	AND `photo_event`.`cust_id` = '$CustId'
	AND `photo_event`.`event_use` = 'y'
	GROUP BY `photo_event`.`event_id`
	ORDER BY `event_name` ASC;");
	// echo $getSearch->query_info;
if($getSearch->TotalRows() > 0){ $Found = true; ?>

<h1 id="HdrType2" class="UpcmngEvnt">
  <div>Search Results for Events</div>
</h1>
<div id="RecordTable" class="Red">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th>Event Name</th>
        <th>Code</th>
        <th>Link</th>
        <th>Date</th>
        <th>Ends</th>
        <th>Grps</th>
        <th>Imgs</th>
        <th>Used</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getSearch->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Evnt,Evnt,".$r['event_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getSearch->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getSearch->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getSearch->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>"><? echo ((strlen($r['event_name']) > 30) ?  substr($r['event_name'],0,30)."..." : $r['event_name']); ?></a></td>
        <td><? echo $r['event_num']; ?></td>
        <td><? echo "<a href=\"/photo_viewer.php?Photographer=".$r['cust_handle']."&code=".$r['event_num']."&email=".$Email."&full=true\" target=\"_blank\" onmouseover=\"window.status='View Event ".str_replace("'","\'",$r['event_name'])."'; return true;\" onmouseout=\"window.status=''; return true;\" title=\"View Event ".str_replace("'","\'",$r['event_name'])."\">View</a>"; ?></td>
        <td><? echo format_date($r['event_date'],"Dash",false,true,false); ?></td>
        <td><? echo format_date($r['event_end'],"Dash",false,true,false); ?></td>
        <td><? echo $r['group_count']; ?></td>
        <td><? echo $r['image_count']; ?></td>
        <td><? echo (round($r['total_size']/1024*100)/100)." GB"; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['event_name']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <div id="Bottom"></div>
</div>
<? } // Customers
$getSearch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getSearch->mysql("SELECT `cust_customers`.* FROM `cust_customers` 
INNER JOIN `orders_invoice`
	ON `orders_invoice`.`cust_id` = `cust_customers`.`cust_id`
LEFT JOIN `orders_invoice_photo`
	ON (`orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
		OR `orders_invoice_photo`.`invoice_id` IS NULL)
LEFT JOIN `orders_invoice_border`
	ON (`orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id`
		OR `orders_invoice_border`.`invoice_id` IS NULL)
INNER JOIN `photo_event_images`
	ON (`photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
		OR `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`)
WHERE 
	(
		`cust_fname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
		".((isset($ArraySearch[1]))?"`cust_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
		`cust_mint` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_lname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
		".((isset($ArraySearch[1]))?"`cust_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
		`cust_suffix` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_cname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_add` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_add_2` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_suite_apt` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_city` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_state` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_zip` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`cust_phone` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
		`cust_cell` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
		`cust_fax` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
		`cust_work` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
		`cust_email` LIKE '%".str_replace(" ","%%",$Search)."%'
	)
	AND `photo_event_images`.`cust_id` = '$CustId'
	GROUP BY `cust_email`;"); 
	// echo $getSearch->query_info;
if($getSearch->TotalRows() > 0){ $Found = true; ?>
<h1 id="HdrType2" class="ClntListClnt">
  <div>Search Results for Customers</div>
</h1>
<div id="RecordTable" class="Yellow">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th>Customer Name</th>
        <th>Address</th>
        <th>Phone Number</th>
        <th>E-mail</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getSearch->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Clnt,Clnt,".$r['cust_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getSearch->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getSearch->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getSearch->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>"><? echo $r['cust_lname'].", ".$r['cust_fname']; ?></a></td>
        <td><? echo $r['cust_city']." ".$r['cust_state']." ".$r['cust_zip']; ?></td>
        <td><? echo phone_number($r['cust_phone']); ?></td>
        <td><? echo $r['cust_email']; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>'; return true;" onmouseout="window.status=''; return true;" title="View Customer <? echo str_replace("'","\'",$r['cust_lname'].", ".$r['cust_fname']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <div id="Bottom"></div>
</div>
<? } // Orders
$getSearch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getSearch->mysql("SELECT *, SUM(`count_image_ids`) AS `count_image_ids`
	FROM (
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_photo`.`image_id` ) AS `count_image_ids` 
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_photo` 
				ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE (
					`cust_fname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
					".((isset($ArraySearch[1]))?"`cust_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
					`cust_mint` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_lname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
					".((isset($ArraySearch[1]))?"`cust_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
					`cust_suffix` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_cname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_add` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_add_2` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_suite_apt` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_city` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_state` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_zip` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_phone` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_cell` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_fax` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_work` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_email` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`invoice_num` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%'
				)
				AND `photo_event_images`.`cust_id` = '$CustId'
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable1`
	UNION 
		SELECT * FROM 
			(SELECT `orders_invoice`.*, `photo_event`.`event_name`, `photo_event`.`event_num`, `cust_customers`.`cust_fname`, `cust_customers`.`cust_lname`, COUNT( `orders_invoice_border`.`image_id` ) AS `count_image_ids` 
			FROM `photo_event_images` 
			INNER JOIN `orders_invoice_border` 
				ON `orders_invoice_border`.`image_id` = `photo_event_images`.`image_id` 
			INNER JOIN `photo_event_group` 
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` 
			INNER JOIN `photo_event` 
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id` 
			INNER JOIN `orders_invoice` 
				ON `orders_invoice`.`invoice_id` = `orders_invoice_border`.`invoice_id` 
			INNER JOIN `cust_customers` 
				ON `cust_customers`.`cust_id` = `orders_invoice`.`cust_id` 
			WHERE (
					`cust_fname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
					".((isset($ArraySearch[1]))?"`cust_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
					`cust_mint` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_lname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
					".((isset($ArraySearch[1]))?"`cust_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
					`cust_suffix` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_cname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_add` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_add_2` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_suite_apt` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_city` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_state` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_zip` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`cust_phone` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_cell` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_fax` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_work` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%' OR
					`cust_email` LIKE '%".str_replace(" ","%%",$Search)."%' OR
					`invoice_num` LIKE '%".preg_replace("/[^a-zA-Z0-9]/", "",$Search)."%'
				)
				AND `photo_event_images`.`cust_id` = '$CustId'
			GROUP BY `orders_invoice`.`invoice_id`) AS `DerivedTable2`
) AS `MainTable`
GROUP BY `invoice_id`");
	// echo $getSearch->query_info;
if($getSearch->TotalRows() > 0){ $Found = true; ?>
<h1 id="HdrType2" class="BsnInvsAll">
  <div>Search Results for Invoices</div>
</h1>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th>Invoice</th>
        <th>Date</th>
        <th>Total</th>
        <th>Name</th>
        <th>Event</th>
        <th>Number</th>
        <th>Imgs</th>
        <th>&nbsp;</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getSearch->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Busn,All,".$r['invoice_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getSearch->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getSearch->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getSearch->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Open Invoice <? echo $r['invoice_num']; ?>'; return true;" onmouseout="window.status=''; return true;" title="Open Invoice <? echo $r['invoice_num']; ?>"><? echo $r['invoice_num']; ?></a></td>
        <td><? echo format_date($r['invoice_date'],"Dash",false,true,false); ?></td>
        <td><? echo "$".number_format($r['invoice_total'],2,".",","); ?></td>
        <td><? echo $r['cust_fname']." ".$r['cust_lname']; ?></td>
        <td><? echo $r['event_name']; ?></td>
        <td><? echo $r['event_num']; ?></td>
        <td><? echo $r['count_image_ids']; ?></td>
        <td><? echo '<a href="/checkout/invoice.php?invoice='.$r['invoice_enc'].'" target="_blank">View</a>'; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Open Invoice <? echo $r['invoice_num']; ?>'; return true;" onmouseout="window.status=''; return true;" title="Open Invoice <? echo $r['invoice_num']; ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <div id="Bottom"></div>
</div>
<? } // MessageBoard
$getSearch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getSearch->mysql("SELECT `photo_cust_favories`.*, `photo_event`.`event_id`, `photo_cust_favories_message`.`fav_message`, `photo_cust_favories_message`.`fav_message_id`
FROM `photo_cust_favories` 
LEFT JOIN `photo_cust_favories_message`
	ON (`photo_cust_favories_message`.`fav_id` = `photo_cust_favories`.`fav_id`
		OR `photo_cust_favories_message`.`fav_id` IS NULL)
INNER JOIN `photo_event`
	ON `photo_cust_favories`.`fav_code` = CONCAT(`photo_event`.`event_num`,'$CHandle')
WHERE 
	(
		`fav_fname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`fav_fname` LIKE '%".str_replace(" ","%%",$ArraySearch[0])."%' OR
		".((isset($ArraySearch[1]))?"`fav_fname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
		`fav_lname` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`fav_lname` LIKE '%".str_replace(" ","%%",$ArraySearch[0])."%' OR
		".((isset($ArraySearch[1]))?"`fav_lname` LIKE '%".str_replace(" ","%%",trim($ArraySearch[1]))."%' OR ":" ")."
		`fav_email` LIKE '%".str_replace(" ","%%",$ArraySearch[0])."%' OR
		`fav_message` LIKE '%".str_replace(" ","%%",$Search)."%'
	)
	AND `photo_event`.`cust_id` = '$CustId'
	AND `photo_event`.`event_use` = 'y'
	GROUP BY `fav_email`;");
	// echo $getSearch->query_info;
if($getSearch->TotalRows() > 0){ $Found = true; ?>
<h1 id="HdrType2" class="MsgBrdClnt">
  <div>Search Results for Message Board</div>
</h1>
<div id="RecordTable" class="Yellow">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th>E-mail</th>
        <th>Date</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getSearch->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Clnt,Board,".$r['event_id'].",Board,".$r['fav_id'].".".$r['fav_message_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getSearch->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getSearch->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getSearch->TotalRows()-1))?'B':'').$class2; ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit <? echo str_replace("'","\'",$r['fav_email']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit <? echo str_replace("'","\'",$r['fav_email']); ?>"><? echo ((strlen($r['fav_email']) > 30) ?  substr($r['fav_email'],0,30)."..." : $r['fav_email']); ?></a></td>
        <td><? echo format_date($r['fav_date'],"Standard",false,true,false); ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['fav_email']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['fav_email']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <div id="Bottom"></div>
</div>
<? } // Discount Codes
$getSearch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getSearch->mysql("SELECT * FROM `prod_discount_codes` 
WHERE 
	(
		`disc_email` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`disc_name` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
		`disc_code` LIKE '%".str_replace(" ","%%",$Search)."%'
	)
	AND `cust_id` = '$CustId'
	AND `prod_id` = '0'
	AND `disc_type` = 's'
	AND `disc_use` = 'y'
	GROUP BY `disc_id`;");
	// echo $getSearch->query_info;
if($getSearch->TotalRows() > 0){ $Found = true; ?>
<h1 id="HdrType2" class="BsnDiscCodes">
  <div>Search Results for Discount Codes </div>
</h1>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th>Code Name</th>
        <th>Code</th>
        <th>Percent</th>
        <th>Exp.</th>
        <th>Used</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getSearch->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Busn,Disc,".$r['disc_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getSearch->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getSearch->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getSearch->TotalRows()-1))?'B':'').$class2;
					
					$getUsed = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
					$getUsed->mysql("SELECT COUNT(`disc_id`) as `code_count` FROM `orders_invoice_codes`  WHERE `disc_id` = '".$r['disc_id']."';");
					$getUsed = $getUsed->Rows(); ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Discount <? echo str_replace("'","\'",$r['disc_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Discount <? echo str_replace("'","\'",$r['disc_name']); ?>"><? echo ((strlen($r['disc_name']) > 30) ?  substr($r['disc_name'],0,30)."..." : $r['disc_name']); ?></a></td>
        <td><? echo $r['disc_code']; ?></td>
        <td><? echo $r['disc_percent']; ?></td>
        <td><? echo format_date($r['disc_exp'],"Dash",false,true,false); ?></td>
        <td><? echo $getUsed[0]['code_count'].' / '.$r['disc_num_uses']; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['disc_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['disc_name']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <div id="Bottom"></div>
</div>
<? } // Gift Certificates
$getSearch = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getSearch->mysql("SELECT * FROM `prod_discount_codes` 
WHERE 
	(
		`disc_email` LIKE '%".str_replace(" ","%%",$Search)."%' OR
		`disc_name` LIKE '%".str_replace(" ","%%",trim($ArraySearch[0]))."%' OR
		`disc_code` LIKE '%".str_replace(" ","%%",$Search)."%'
	)
	AND `cust_id` = '$CustId'
	AND `prod_id` = '0'
	AND `disc_type` = 'g'
	AND `disc_use` = 'y'
	GROUP BY `disc_id`;");
	// echo $getSearch->query_info;
if($getSearch->TotalRows() > 0){ $Found = true; ?>
<h1 id="HdrType2" class="BsnDiscCodes">
  <div>Search Results for Gift Certificates</div>
</h1>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <table border="0" cellpadding="0" cellspacing="0" id="TableRecords1">
      <tr>
        <th>Code Name</th>
        <th>Code</th>
        <th>Percent</th>
        <th>Exp.</th>
        <th>Used</th>
        <th class="R">&nbsp;</th>
      </tr>
      <? foreach($getSearch->Rows() as $k => $r){ $EvntAction = "javascript:set_form('','Busn,Disc,".$r['disc_id']."','view','".$sort."','".$rcrd."');";
					$class1 = ""; $class2 = "ROver";
					if(intval($k%2)==1){ $class1 = (($k == ($getSearch->TotalRows()-1))?'B':'').'SRow'; }
					else if ($k == ($getSearch->TotalRows()-1)) $class1 = 'B';
					$class2 = (($k == ($getSearch->TotalRows()-1))?'B':'').$class2;
					
					$getUsed = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
					$getUsed->mysql("SELECT COUNT(`disc_id`) as `code_count` FROM `orders_invoice_codes`  WHERE `disc_id` = '".$r['disc_id']."';");
					$getUsed = $getUsed->Rows(); ?>
      <tr<? if(strlen(trim($class1))>0) echo ' class="'.$class1.'"'; ?> onMouseOver="this.className='<? echo $class2; ?>';" onMouseOut="this.className='<? echo $class1; ?>';">
        <td><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Discount <? echo str_replace("'","\'",$r['disc_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Discount <? echo str_replace("'","\'",$r['disc_name']); ?>"><? echo ((strlen($r['disc_name']) > 30) ?  substr($r['disc_name'],0,30)."..." : $r['disc_name']); ?></a></td>
        <td><? echo $r['disc_code']; ?></td>
        <td><? echo $r['disc_percent']; ?></td>
        <td><? echo format_date($r['disc_exp'],"Dash",false,true,false); ?></td>
        <td><? echo $getUsed[0]['code_count'].' / '.$r['disc_num_uses']; ?></td>
        <td class="R"><a href="<? echo $EvntAction; ?>" onmouseover="window.status='Edit Event <? echo str_replace("'","\'",$r['disc_name']); ?>'; return true;" onmouseout="window.status=''; return true;" title="Edit Event <? echo str_replace("'","\'",$r['disc_name']); ?>">Open</a></td>
      </tr>
      <? } ?>
    </table>
  </div>
  <div id="Bottom"></div>
</div>
<? } if($Found == false){ ?>
<div id="RecordTable" class="Green">
  <div id="Top"></div>
  <div id="Records">
    <p>We were unable to find anything that matches your search criteria</p>
  </div>
  <div id="Bottom"></div>
</div>
<? } ?>
