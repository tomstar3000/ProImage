<?
if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php'; 
require_once $r_path.'scripts/fnct_format_phone.php';
require_once $r_path.'scripts/fnct_format_date.php';
$new_line = false;
$last = 0;
$date = 0;
$total = 0;
$temp_profit = -1;
$temp_total = 0;
$temp_disc = 0;
$temp_tax = 0;
$temp_ship = 0;
$temp_grand = 0;
$temp_comm = 0;
$temp_cost = 0;
$temp_month = array();
?>
<script type="text/javascript">
function set_time(date_val){
	date_val = date_val.split("[-]");
	// yyyy-mm-dd
	// 0123456789
	date_val[0] = date_val[0].substr(5,2)+"/"+date_val[0].substr(8,2)+"/"+date_val[0].substr(0,4);
	date_val[1] = date_val[1].substr(5,2)+"/"+date_val[1].substr(8,2)+"/"+date_val[1].substr(0,4);
	document.getElementById('From').value = date_val[0];
	document.getElementById('To').value = date_val[1];
	document.getElementById('form_action_form').submit();
}
</script>
<style>
.TotalCell{
	float:left;
	border-right:solid 1px #999999;
	padding-top:5px;
	padding-bottom:5px;
	text-align:center;
}
.TotalCellnoBD{
	float:left;
	padding-top:5px;
	padding-bottom:5px;
	text-align:right;
}
</style>
<?
if(isset($path[2]) && is_int(intval($path[2]))) {
	$query_get_oldest= "SELECT `invoice_paid_date` FROM `orders_invoice` INNER JOIN `orders_invoice_photo` ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` ".$addthis." ORDER BY `invoice_paid_date` ASC LIMIT 0,1";
} else {
	$query_get_oldest= "SELECT `invoice_paid_date` FROM `orders_invoice` ORDER BY `invoice_paid_date` ASC LIMIT 0,1";
}
$get_oldest = mysql_query($query_get_oldest, $cp_connection) or die(mysql_error());
$row_get_oldest = mysql_fetch_assoc($get_oldest);
$year = substr($row_get_oldest['invoice_paid_date'],0,4);
if($year == 0) $year = date("Y");
$month = substr($row_get_oldest['invoice_paid_date'],5,2);
?>
<div id="Report_Date">
  <p>Yearly Report
    <select name="Report_Year" id="Report_Year" onchange="set_time(this.value);">
      <option value="0">-- Select Year --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){ ?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,1,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,1,1,($n+1))); ?>"><? echo $n; ?></option>
      <? } ?>
    </select>
    Monthly Report
    <select name="Report_Month" id="Report_Month" onchange="set_time(this.value);">
      <option value="0">-- Select Month --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){
				if($n == $year) $z = $month; else $z = 1;
				if($year == date("Y"))$end = date("m"); else $end = 12;
				for($z = intval($z); $z<=$end; $z++){?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,$z,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,($z+1),1,$n)); ?>"><? echo date("M",mktime(0,0,0,$z,1,date("Y")))." (".$n.")"; ?></option>
      <? } }?>
    </select>
    <br />
    Select Report From:
    <input type="text" name="From" id="From" value="<? echo format_date($Start,"Dash",false,true,false); ?>" />
    To:
    <input type="text" name="To" id="To" value="<? echo format_date($End,"Dash",false,true,false); ?>" />
    <input type="submit" name="Submit" id="Submit" value="Generate Report" />
    <? if(isset($path[2]) && is_int(intval($path[2]))) {		
		$query_get_events = "SELECT `event_id`, `event_name`, `event_num` FROM `photo_event` WHERE `cust_id` = '$CustId' AND `event_use` = 'y' ORDER BY `event_name` ASC";
		$get_events  = mysql_query($query_get_events , $cp_connection) or die(mysql_error());
		?>
    <br />
    Event Name:
    <select name="Events" id="Events" onchange="document.getElementById('form_action_form').submit();">
      <option value="0"> -- Active Events -- </option>
      <? while($row_get_events  = mysql_fetch_assoc($get_events )){ ?>
      <option value="<? echo $row_get_events['event_id']; ?>"<? if($row_get_events['event_id'] == $Events) echo '  selected="selected"'; ?>><? echo $row_get_events['event_name']." - ".$row_get_events['event_num']; ?></option>
      <? } $query_get_events = "SELECT `event_id`, `event_name`, `event_num` FROM `photo_event` WHERE `cust_id` = '$CustId' AND `event_use` = 'n' ORDER BY `event_name` ASC";
		$get_events  = mysql_query($query_get_events , $cp_connection) or die(mysql_error()); ?>
      <option value="-1"> -- Archived Events -- </option>
      <? while($row_get_events  = mysql_fetch_assoc($get_events )){ ?>
      <option value="<? echo $row_get_events['event_id']; ?>"<? if($row_get_events['event_id'] == $Events) echo '  selected="selected"'; ?>><? echo $row_get_events['event_name']." - ".$row_get_events['event_num']; ?></option>
      <? } ?>
    </select>
    <? } else { 
		$query_get_photos = "SELECT `cust_fname`, `cust_lname`, `cust_id`, `cust_cname` FROM `cust_customers` WHERE `cust_photo` = 'y'ORDER BY `cust_cname`, `cust_lname`, `cust_fname` ASC";
		$get_photos  = mysql_query($query_get_photos , $cp_connection) or die(mysql_error());?>
    <br />
    Photographers: <select name="Photographers" id="Photographer" onchange="document.getElementById('form_action_form').submit();">
		<option value="0"> -- Show All --</option>
		<? while($row_photos = mysql_fetch_assoc($get_photos)){ ?>
      <option value="<? echo $row_photos['cust_id']; ?>"<? if($row_photos['cust_id'] == $Photog) echo '  selected="selected"'; ?>><? echo $row_photos['cust_cname']." - ".$row_photos['cust_lname'].", ".$row_photos['cust_fname']; ?></option>
      <? } ?>
    </select>
    <br />
    <br />
    <input type="button" name="Save Changes" id="Save_Changes" value="Save Changes" onclick="document.getElementById('Controller').value ='Save_Comm'; document.getElementById('form_action_form').submit();" />
    <? } ?>
  </p>
</div>
<? 
function end_line(){
	disp_items();
	//echo '</blockquote>';
	disp_total();
	echo '</blockquote>';
}
function end_record(){
	global $temp_month;
	echo '<blockquote>';
	foreach($temp_month as $k => $v){
		echo '<div style="width:150px; height:100px; float:left; margin-left:5px; margin-bottom:5px;"><p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#DDDDDD"><strong>'.date("F",mktime(0,0,0,substr($k,5,2),1,substr($k,0,4))).'</strong></p><p style="margin:0px;">';
		echo 'Commision: $'.number_format($v['COMM'],2,".",",").'<br />';
		echo '</div>';
	}
	echo '<br clear="all" />';
	echo '</blockquote>';
	echo '<br clear="all" />';
	echo '</div>';
	$temp_month = array();
}
function disp_total(){
	global $temp_profit, $temp_total, $temp_disc, $temp_tax, $temp_ship, $temp_grand, $temp_comm, $temp_cost, $test_date, $temp_month, $date;
	echo '<div style="height:21px;">
		<div style="width:392px; text-align:right" class="TotalCell"><p>Total Sales&nbsp;</p></div>
		<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>$'.number_format($temp_total,2,".",",").'</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>Total Print Cost:</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($'.number_format($temp_cost,2,".",",").')</p></div>
		</div>';
	echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
	echo '<div style="height:21px;">
		<div style="width:463px; text-align:right" class="TotalCell"><p>Discounts:&nbsp;</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($'.number_format($temp_disc,2,".",",").')</p></div>
		</div>';
		
	echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
	switch($temp_profit){
		case 0:
			$temp_profit = 10;
			break;
		default:
			$temp_profit = $temp_profit;
			break;
	}
	$profit_share = ($temp_total-$temp_disc)*($temp_profit/100);
	echo '<div style="height:21px;">
		<div style="width:463px; text-align:right" class="TotalCell"><p>Revenue Share:&nbsp;</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>'.$temp_profit.'%</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($'.number_format($profit_share,2,".",",").')</p></div>
		</div>';
	echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
	
	$credit_fee = ($temp_total-$temp_disc)*(3/100);
	echo '<div style="height:21px;">
		<div style="width:463px; text-align:right" class="TotalCell"><p>Credit Card Fee:&nbsp;</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>3%</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>($'.number_format($credit_fee,2,".",",").')</p></div>
		</div>';
	$due = $temp_total-$temp_cost-$temp_disc-$profit_share-$credit_fee;
	echo '<div style="background-color:#CCCCCC; height:22px;">
		<div style="width:463px; text-align:right" class="TotalCell"><p><strong>Due to Photographer&nbsp;</strong></p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p><strong>$'.number_format($due,2,".",",").'</strong></p></div>
		</div>';
	echo '<div style="height:21px;">
		<div style="width:463px; text-align:right" class="TotalCell"><p>Shipping:&nbsp;</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$'.number_format($temp_ship,2,".",",").'</p></div>
		</div>';
	echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
	echo '<div style="height:21px;">
		<div style="width:463px; text-align:right" class="TotalCell"><p>Taxes:&nbsp;</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>&nbsp;</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$'.number_format($temp_tax,2,".",",").'</p></div>
		</div>';
	$temp_month[$date] = array();
	$temp_month[$date]['COMM'] = $due;
	$temp_profit = -1;
	$temp_total = 0;
	$temp_disc = 0;
	$temp_tax = 0;
	$temp_ship = 0;
	$temp_grand = 0;
	$temp_comm = 0;
	$temp_cost = 0;
}
function disp_items(){
	global $items, $temp_comm, $temp_cost;
	//echo '<blockquote>';
	array_multisort($items, SORT_REGULAR, SORT_ASC);
	foreach($items as $k => $v){
		echo '<div style="height:21px;">
		<div style="width:240px; text-align:left" class="TotalCell"><p>'.$v['NAME'].'</p></div>
		<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>'.$v['QTY'].'</p></div>
		<div style="width:75px; text-align:right; padding-right:5px;" class="TotalCell"><p>$'.number_format($v['PRICE'],2,".",",").'</p></div>
		<div style="width:65px; text-align:right; padding-right:5px;" class="TotalCell"><p>$'.number_format(($v['PRICE']*$v['QTY']),2,".",",").'</p></div>
		<div style="width:95px; text-align:right; padding-right:5px;" class="TotalCell"><p>$'.number_format($v['COST'],2,".",",").'</p></div>
		<div style="width:145px; text-align:right; padding-right:5px;" class="TotalCellnoBD"><p>$'.number_format(($v['COST']*$v['QTY']),2,".",",").'</p></div>
		</div>';
		
		$temp_comm += (($v['PRICE']-$v['COST'])*$v['QTY']);
		$temp_cost += $v['COST']*$v['QTY'];
	}
	echo '<div style="height:3px; background-color:#999999; padding:0px; margin:0px;"></div>';
	//echo '<br clear="all" />';
	//echo '</blockquote>';
}
if($total_info>0){
	while($row_get_info = mysql_fetch_assoc($get_info)){
		if($row_get_info['cust_id'] != $last){
			if($last != 0) {
				end_line();
				end_record();
			}
			$last = $row_get_info['cust_id'];
			$total = 0;
			$date = 0;
			$item_1 = 0;
			echo '<div style="border:solid 1px #666666; margin-top:10px; page-break-before:always"><p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#CCCCCC"><strong>'.$row_get_info['cust_fname'].' '.$row_get_info['cust_lname'].'</strong></p><p style="margin:0px;">';
			echo ($row_get_info['cust_cname'] != "") ? $row_get_info['cust_cname']."<br />" : '';
			echo ($row_get_info['cust_add'] != "") ? $row_get_info['cust_add'].(($row_get_info['cust_suite_apt'] != "")?" Suite/Apt: ".$row_get_info['cust_suite_apt'] : "")."<br />" : '';
			echo ($row_get_info['cust_add_2'] != "") ? $row_get_info['cust_add_2']."<br />" : '';
			echo ($row_get_info['cust_city'] != "") ? $row_get_info['cust_city'].' '.$row_get_info['cust_state'].' '.$row_get_info['st_zip']."<br />" : '';
			echo ($row_get_info['cust_phone'] != 0) ? 'p:'.phone_number($row_get_info['cust_phone'])."<br />" : '';
			echo ($row_get_info['cust_cell'] != 0) ? 'c:'.phone_number($row_get_info['cust_cell'])."<br />" : '';
			echo ($row_get_info['cust_fax'] != 0) ? 'f:'.phone_number($row_get_info['cust_fax'])."<br />" : '';
			echo ($row_get_info['cust_work'] != 0) ? 'w:'.phone_number($row_get_info['cust_work']).(($row_get_info['cust_ext'] != "")?" Ext: ".$row_get_info['cust_ext'] : 0)."<br />" : '';
			echo ($row_get_info['cust_800'] != 0) ? '8:'.phone_number($row_get_info['cust_800'])."<br />" : '';
			echo ($row_get_info['cust_email'] != "") ? '<a href="mailto:'.$row_get_info['cust_email'].'">'.$row_get_info['cust_email']."</a><br />" : '';
			echo ($row_get_info['cust_email_2'] != "") ? '<a href="mailto:'.$row_get_info['cust_email_2'].'">'.$row_get_info['cust_email_2']."</a><br />" : '';
			echo ($row_get_info['website'] != "") ? '<a href="'.$row_get_info['website'].'" target="_blank">'.$row_get_info['website']."</a><br />" : '';
			echo '</p>';
			echo '<p>Revenue Share: <input type="hidden" name="RevIds[]" id="RevIds[]" value="'.$row_get_info['cust_id'].'"><input type="text" name="Rev[]" id="Rev[]" value="'.(($row_get_info['cust_rev']==0)?10:$row_get_info['cust_rev']).'" size="3"> %</p>';
		}
		$test_date = date("Y-m",mktime(0,0,0,substr($row_get_info['invoice_paid_date'],5,2),1,substr($row_get_info['invoice_paid_date'],0,4)));
		if($test_date != $date){
			if($date != 0) {
				end_line();
			}
			$date = $test_date;
			$item_1 = 0;
			echo '<blockquote>';
			echo '<p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#DDDDDD"><strong>'.date("F",mktime(0,0,0,substr($test_date,5,2),1,substr($test_date,0,4))).'</strong></p>';
			echo '<div style="background-color:#CCCCCC; height:22px;">
		<div style="width:240px; text-align:left" class="TotalCell"><p><strong>Product</strong></p></div>
		<div style="width:70px;" class="TotalCell"><p><strong>Qty Sold</strong></p></div>
		<div style="width:80px;" class="TotalCell"><p><strong>Unit Price</strong></p></div>
		<div style="width:70px;" class="TotalCell"><p><strong>Total</strong></p></div>
		<div style="width:100px;" class="TotalCell"><p><strong>Per Print Cost</strong></p></div>
		<div style="width:150px;" class="TotalCellnoBD"><p><strong>Total Print Charge</strong></p></div>
		</div>';
			//echo "<blockquote>";
			$items = array();
		}
		$temp_id = $row_get_info['invoice_id'];	
		$query_get_event = "SELECT `photo_event`.* FROM `orders_invoice_photo` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` INNER JOIN `photo_event_group` ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id` INNER JOIN `photo_event` ON `photo_event`.`event_id` = `photo_event_group`.`event_id` WHERE `orders_invoice_photo`.`invoice_id` = '$temp_id' ORDER BY `photo_event`.`event_name` ASC";
		$get_event = mysql_query($query_get_event, $cp_connection) or die(mysql_error());
		$row_get_event = mysql_fetch_assoc($get_event);
		if($item_1 != $row_get_event['event_id']){
			$item_1 = $row_get_event['event_id'];
			if($temp_total > 0)	$new_line = true;
			//if(isset($path[2]) && is_int(intval($path[2]))) echo '</blockquote><blockquote><div style="background-color:#CCCCCC; height:17px; padding-top:5px;"><p><strong>'.$row_get_event['event_name'].'</strong>'.(($row_get_event['event_num'] != "") ? " - ".format_date($row_get_event['event_date'],"Dash",false,true,false) : '').'</p></div><br />';
		}
		$query_get_items = "SELECT `photo_event_images`.`image_tiny`, `photo_event_images`.`image_id`, `orders_invoice_photo`.`invoice_image_size_id`, `orders_invoice_photo`.`invoice_image_asis`, `orders_invoice_photo`.`invoice_image_sepia`, `orders_invoice_photo`.`invoice_image_bw`, `orders_invoice_photo`.`invoice_image_cost`, `orders_invoice_photo`.`invoice_image_price`, `prod_products`.`prod_name`, `prod_products`.`prod_id` FROM `orders_invoice_photo` INNER JOIN `photo_event_images` ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` INNER JOIN `prod_products` ON `prod_products`.`prod_id` = `orders_invoice_photo`.`invoice_image_size_id` WHERE `orders_invoice_photo`.`invoice_id` = '$temp_id' ORDER BY `photo_event_images`.`image_name` ASC";
		$get_items = mysql_query($query_get_items, $cp_connection) or die(mysql_error());
		
		while($row_get_items = mysql_fetch_assoc($get_items)){
			$key = $row_get_items['prod_id'].str_replace(".","",$row_get_items['invoice_image_price']);
			$item_total = $row_get_items['invoice_image_asis']+$row_get_items['invoice_image_bw']+$row_get_items['invoice_image_sepia'];
			if(!$items[$key]) $items[$key]['QTY'] = $item_total; else $items[$key]['QTY'] += $item_total;
			$items[$key]['NAME'] = $row_get_items['prod_name'];
			$items[$key]['PRICE'] = $row_get_items['invoice_image_price'];
			$items[$key]['COST'] = $row_get_items['invoice_image_cost'];
			if(isset($path[2]) && is_int(intval($path[2])) && $new_line === true){
				//$new_line = false;
				//end_line();
			}
		}
		$temp_profit = $row_get_info['cust_rev'];
		$temp_total += $row_get_info['invoice_total'];
		$temp_disc += $row_get_info['invoice_disc'];
		$temp_tax += $row_get_info['invoice_tax'];
		$temp_ship += $row_get_info['invoice_ship'];
		$temp_grand += $row_get_info['invoice_grand'];
	}
	if($temp_total>0)end_line();
	end_record();
} else {
echo '<div style="background-color:#CCCCCC; height:22px; padding-top:5px;"><p><strong>There are no reports for this date range.</strong></p></div>';
}	?>
