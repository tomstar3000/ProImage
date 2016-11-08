<?
if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
?>
<script type="text/javascript">
function set_time(){
	if(parseInt(document.getElementById('Report_Year').value) != 0)	date_val = document.getElementById('Report_Year').value;
	else if(parseInt(document.getElementById('Report_Month').value) != 0)	date_val = document.getElementById('Report_Month').value;
	else return;
	date_val = date_val.split("[-]");
	date_val[0] = date_val[0].substr(5,2)+"/"+date_val[0].substr(8,2)+"/"+date_val[0].substr(0,4);
	date_val[1] = date_val[1].substr(5,2)+"/"+date_val[1].substr(8,2)+"/"+date_val[1].substr(0,4);
	document.getElementById('From').value = date_val[0]; document.getElementById('To').value = date_val[1];
	document.getElementById('form_action_form').submit(); }
</script>
<script type="text/javascript">
function changeHiddenDate($Tag,$Fld){
	$Year = document.getElementById($Tag+'_Year');
	$Month = document.getElementById($Tag+'_Month');
	$Day = document.getElementById($Tag+'_Day');
	document.getElementById($Fld).value = $Month.value+"/"+$Day.value+"/"+$Year.value;
}
</script>
<?
$getOldest = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getOldest->mysql("(SELECT `invoice_paid_date` 
		FROM `orders_invoice` 
		INNER JOIN `orders_invoice_photo` 
			ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` ".$addthis."
		LIMIT 0,1)
	UNION
		(SELECT `invoice_paid_date` 
		FROM `orders_invoice` 
		INNER JOIN `orders_invoice_border` 
			ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id` 
		INNER JOIN `photo_event_images` 
			ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id` 
		INNER JOIN `cust_customers` 
			ON `cust_customers`.`cust_id` = `photo_event_images`.`cust_id` ".$addthis."
		LIMIT 0,1)
		ORDER BY `invoice_paid_date` ASC;");
$getOldest = $getOldest->Rows();

$year = substr($getOldest[0]['invoice_paid_date'],0,4);
if($year == 0) $year = date("Y");
$month = substr($getOldest[0]['invoice_paid_date'],5,2);
require_once($r_path.'includes/info_calendar.php'); ?>
<h1 id="HdrType2" class="<? switch($path[0]){
	case 'Clnt': echo 'UpcmngEvnt'; break;
	case 'Busn': echo 'BsnEvntRpts'; $HotMenu = "Busn,Busn:query"; $Key = array_search($HotMenu, $StrArray); break;
	default: echo 'UpcmngEvnt'; break; } ?>">
  <div>Event Reports</div>
</h1>
<? if(isset($HotMenu) && $HotMenu != NULL){ ?>
<div id="HdrLinks"><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<? } ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records" class="Colmn2"> <span>
    <label for="Report_Year" class="CstmFrmElmntLabel">Yearly Report</label>
    <select name="Report_Year" id="Report_Year" class="CstmFrmElmnt" onchange="javascript: set_time();" onmouseover="window.status='Report by Year'; return true;" onmouseout="window.status=''; return true;" title="Report by Year">
      <option value="0" title="-- Select Year --">-- Select Year --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){ ?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,1,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,1,0,($n+1))); ?>" title="<? echo $n; ?>"><? echo $n; ?></option>
      <? } ?>
    </select>
    </span> <span>
    <label for="Report_Month" class="CstmFrmElmntLabel">Monthly Report</label>
    <select name="Report_Month" id="Report_Month" class="CstmFrmElmnt" onchange="set_time();" onmouseover="window.status='Report by Month'; return true;" onmouseout="window.status=''; return true;" title="Report by Month">
      <option value="0" title="-- Select Month --">-- Select Month --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){
				if($n == $year) $z = $month; else $z = 1;
				if($year == date("Y"))$end = date("m"); else $end = 12;
				for($z = intval($z); $z<=$end; $z++){?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,$z,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,($z+1),0,$n)); ?>" title="<? echo date("M",mktime(0,0,0,$z,1,date("Y")))." (".$n.")"; ?>"><? echo date("M",mktime(0,0,0,$z,1,date("Y")))." (".$n.")"; ?></option>
      <? } }?>
    </select>
    </span><span>
    <label for="From" class="CstmFrmElmntLabel">Select Report From:</label>
    <div style="float:left; clear:none;">
      <select name="Start Month" id="Start_Month" class="CstmFrmElmnt88" onchange="changeHiddenDate('Start','From')" onmouseover="window.status='Month of Report'; return true;" onmouseout="window.status=''; return true;" title="Month of Report">
        <? $TDate = date("m",mktime(0,0,1,substr($Start,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="Start Day" id="Start_Day" class="CstmFrmElmnt53" onchange="changeHiddenDate('Start','From')" onmouseover="window.status='Day of Report'; return true;" onmouseout="window.status=''; return true;" title="Day of Report">
        <? $TDate = date("d",mktime(0,0,1,1,substr($Start,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="Start Year" id="Start_Year" class="CstmFrmElmnt64" onchange="changeHiddenDate('Start','From')" onmouseover="window.status='Year of Report'; return true;" onmouseout="window.status=''; return true;" title="Year of Report">
        <? $year = substr($getOldest[0]['invoice_paid_date'],0,4);
					if($year == 0) $year = date("Y");
					$TDate = date("Y",mktime(0,0,1,1,1,substr($Start,0,4)));
				for($n = intval($year); $n<=date("Y"); $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,$n)); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('ReleaseCalendar','Start_Year','Start_Month','Start_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="ReleaseCalendar">Calendar</a></div>
    <input type="hidden" name="From" id="From" value="<? echo format_date($Start,"Dash",false,true,false); ?>" />
    </span><span>
    <label for="To" class="CstmFrmElmntLabel">To:</label>
    <div style="float:left; clear:none;">
      <select name="End Month" id="End_Month" class="CstmFrmElmnt88" onchange="changeHiddenDate('End','To')" onmouseover="window.status='Month of Report'; return true;" onmouseout="window.status=''; return true;" title="Month of Report">
        <? $TDate = date("m",mktime(0,0,1,substr($End,5,2),1,date("Y")));
				for($n = 0; $n < 12; $n++){ $TDate2 = date("m",mktime(0,0,1,($n+1),1,date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo date("F",mktime(0,0,1,($n+1),1,date("Y"))); ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none; margin-left:3px; margin-right:3px;">
      <select name="End Day" id="End_Day" class="CstmFrmElmnt53" onchange="changeHiddenDate('End','To')" onmouseover="window.status='Day of Report'; return true;" onmouseout="window.status=''; return true;" title="Day of Report">
        <? $TDate = date("d",mktime(0,0,1,1,substr($End,8,2),date("Y")));
				for($n = 0; $n < 31; $n++){ $TDate2 = date("d",mktime(0,0,1,1,($n+1),date("Y"))); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div style="float:left; clear:none;">
      <select name="End Year" id="End_Year" class="CstmFrmElmnt64" onchange="changeHiddenDate('End','To')" onmouseover="window.status='Year of Report'; return true;" onmouseout="window.status=''; return true;" title="Year of Report">
        <? $year = substr($getOldest[0]['invoice_paid_date'],0,4);
					if($year == 0) $year = date("Y");
					$TDate = date("Y",mktime(0,0,1,1,1,substr($End,0,4)));
				for($n = intval($year); $n<=date("Y"); $n++){ $TDate2 = date("Y",mktime(0,0,1,1,1,$n)); ?>
        <option value="<? echo $TDate2; ?>" title="<? echo $TDate2; ?>"<? if($TDate == $TDate2) echo ' selected="selected"'; ?>><? echo $TDate2; ?></option>
        <? } ?>
      </select>
    </div>
    <div id="BtnCalendar"><a href="javascript:StartCalDate('ReleaseCalendar','End_Year','End_Month','End_Day',null);" onmouseover="window.status='Start Calendar'; return true;" onmouseout="window.status=''; return true;" title="Start Calendar" id="ReleaseCalendar">Calendar</a></div>
    <input type="hidden" name="To" id="To" value="<? echo format_date($End,"Dash",false,true,false); ?>" />
    </span>
    <? if(!isset($path[2])){
				 $getEvents = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				 $getEvents->mysql("SELECT `event_id`, `event_name`, `event_num` FROM `photo_event` WHERE `cust_id` = '$CustId' AND `event_use` = 'y' ORDER BY `event_name` ASC;"); ?>
    <span><label for="Events" class="CstmFrmElmntLabel">Event Name:</label>
    <select name="Events" id="Events" class="CstmFrmElmnt" onchange="document.getElementById('form_action_form').submit();" onmouseover="window.status='Report by Month'; return true;" onmouseout="window.status=''; return true;" title="Report by Month">
      <option value="0" title="-- Active Events --">-- Active Events --</option>
      <? foreach($getEvents -> Rows() as $r){ ?>
      <option value="<? echo $r['event_id']; ?>"<? if($r['event_id'] == $Events) echo '  selected="selected"'; ?> title="<? echo $r['event_name']." - ".$r['event_num']; ?>"><? echo $r['event_name']." - ".$r['event_num']; ?></option>
      <? } $getEvents = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		 $getEvents->mysql("SELECT `event_id`, `event_name`, `event_num` FROM `photo_event` WHERE `cust_id` = '$CustId' AND `event_use` = 'n' ORDER BY `event_name` ASC;"); ?>
      <option value="0">&nbsp;</option>
      <option value="0" title="-- Archived Events --">-- Archived Events --</option>
      <? foreach($getEvents -> Rows() as $r){ ?>
      <option value="<? echo $r['event_id']; ?>"<? if($r['event_id'] == $Events) echo '  selected="selected"'; ?> title="<? echo $r['event_name']." - ".$row_get_events['event_num']; ?>"><? echo $r['event_name']." - ".$row_get_events['event_num']; ?></option>
      <? } ?>
    </select>
    </span>
    <? } ?>
    <span>
    <input type="submit" name="Submit" id="Submit" value="Generate Report" />
    </span><br clear="all" /><br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<?
ob_start();	
$grand_sales = 0;
$grand_cost = 0;
$grand_discounts = 0;
$grand_print_discounts = 0;
$grand_profit = 0;
$grand_credit_card = 0;
$grand_due = 0;
$grand_ship = 0;
$grand_tax = 0;

if(count($sorted_array) > 0){
	foreach($sorted_array as $k => $v){
		$temp_comm = array(); ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records"> <? echo '<h5>'.$v['Info']['Name'].'</h5><p>';
		echo ($v['Info']['CName'] != "") ? $v['Info']['CName']."<br />" : '';
		echo ($v['Info']['Add'] != "") ? $v['Info']['Add'].(($v['Info']['Suite'] != "")?" Suite/Apt: ".$v['Info']['Suite'] : "")."<br />" : '';
		echo ($v['Info']['Add2'] != "") ? $v['Info']['Add2']."<br />" : '';
		echo ($v['Info']['City'] != "") ? $v['Info']['City'].' '.$v['Info']['State'].' '.$v['Info']['Zip']."<br />" : '';
		echo ($v['Info']['Phone'] != 0) ? 'p:'.phone_number($v['Info']['Phone'])."<br />" : '';
		echo ($v['Info']['Cell'] != 0) ? 'c:'.phone_number($v['Info']['Cell'])."<br />" : '';
		echo ($v['Info']['Fax'] != 0) ? 'f:'.phone_number($v['Info']['Fax'])."<br />" : '';
		echo ($v['Info']['Work'] != 0) ? 'w:'.phone_number($v['Info']['Work']).(($v['Info']['Ext'] != "")?" Ext: ".$v['Info']['Ext'] : 0)."<br />" : '';
		echo ($v['Info']['800'] != 0) ? '8:'.phone_number($v['Info']['800'])."<br />" : '';
		echo ($v['Info']['Email'] != "") ? '<a href="mailto:'.$v['Info']['Email'].'">'.$v['Info']['Email']."</a><br />" : '';
		echo ($v['Info']['Email2'] != "") ? '<a href="mailto:'.$v['Info']['Email2'].'">'.$v['Info']['Email2']."</a><br />" : '';
		echo ($v['Info']['Website'] != "") ? '<a href="'.$v['Info']['Website'].'" target="_blank">'.$v['Info']['Website']."</a><br />" : '';
		echo '</p>';
		
		echo '<p>Current Revenue Share: '.(($v['Totals']['Rev']==0)?10:$v['Totals']['Rev']).'%</p>';
		foreach($v['Dates'] as $key => $value){
			echo '<blockquote>';
			//YYYY-MM-DD HH:II:SS
			//0123456789012345678
			$temp_date = explode(".",$key);
			$temp_date = date("F Y",mktime(substr($temp_date[0],11,2),substr($temp_date[0],14,2),substr($temp_date[0],17,2),substr($temp_date[0],5,2),substr($temp_date[0],8,2),substr($temp_date[0],0,4)))." - ".date("F Y",mktime(substr($temp_date[1],11,2),substr($temp_date[1],14,2),substr($temp_date[1],17,2),substr($temp_date[1],5,2),substr($temp_date[1],8,2),substr($temp_date[1],0,4)));
			echo '<h6>'.$temp_date.'</h6>';
			echo '<div>
				<h1 id="RC1">Product</h1>
				<h1 id="RC2">Qty Sold</strong></h1>
				<h1 id="RC3">Qty Discount</strong></h1>
				<h1 id="RC4">Unit Price</strong></h1>
				<h1 id="RC5">Total</strong></h1>
				<h1 id="RC6">Per Print</strong></h1>
				<h1 id="RC7" class="B">Total Charge</strong></h1>
				<br clear="all" />
			</div>';
			$temp_cost = 0;
			$temp_disccost = 0;
			$temp_discprice = 0;
			if(count($value['Items']) > 0){
				foreach($value['Items'] as $key2 => $value2){
					$temp_cost += ($value2['COST']*($value2['Total']-$value2['Disc']['Total']));
					$temp_disccost +=($value2['COST']*$value2['Disc']['Total']);
					$temp_discprice +=($value2['PRICE']*$value2['Disc']['Total']);
					
					echo '<div>
						<p id="RC1">'.$value2['NAME'].'</p>
						<p id="RC2">'.$value2['Total'].'</p>
						<p id="RC3">'.$value2['Disc']['Total'].'</p>
						<p id="RC4">$'.number_format($value2['PRICE'],2,".",",").'</p>
						<p id="RC5">$'.number_format(($value2['PRICE']*($value2['Total']-$value2['Disc']['Total'])),2,".",",").'</p>
						<p id="RC6">$'.number_format($value2['COST'],2,".",",").'</p>
						<p id="RC7" class="B">$'.number_format(($value2['COST']*($value2['Total']-$value2['Disc']['Total'])),2,".",",").'</p>
						<br clear="all" />
					</div>';
			} }
			echo '<div class="Breaker" style="height:3px;"></div>';
			echo '<div>
					<p id="RC1A" class="B">&nbsp;</p>
					<p id="RC4">Total Sales&nbsp;</p>
					<p id="RC5">$'.number_format(($value['Totals']['Total']-$temp_discprice),2,".",",").'</p>';
			$grand_sales += ($value['Totals']['Total']-$temp_discprice);
			echo '<p id="RC6">Total Cost</p>
					<p id="RC7" class="B">($'.number_format(($temp_cost),2,".",",").')</p>
					<br clear="all" />
			</div>';
			$grand_cost += ($temp_cost);
			echo '<div class="Breaker"></div>';
			echo '<div>
					<p id="RC1B">Discounts:</p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B">($'.number_format($value['Totals']['Disc']+$value['Totals']['Disc2'],2,".",",").')</p>
					<br clear="all" />
				</div>';
			$grand_discounts += ($value['Totals']['Disc']+$value['Totals']['Disc2']);
			echo '<div class="Breaker"></div>';
			echo '<div>
					<p id="RC1B">Printer Discounts:</p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B">($'.number_format($temp_disccost,2,".",",").')</p>
					<br clear="all" />
				</div>';
			echo '<div class="Breaker"></div>';
			$grand_print_discounts += ($temp_disccost);
			/* $profit_share = ($value['Totals']['Total']-$temp_discprice-$value['Totals']['Disc']-$value['Totals']['Disc2'])*($v['Totals']['Rev']/100);
			echo '<div>
					<p id="RC1B">Revenue Share:</p>
					<p id="RC6">'.$v['Totals']['Rev'].'%</p>
					<p id="RC7" class="B">($'.number_format($profit_share,2,".",",").')</p>
					<br clear="all" />
				</div>';
			echo '<div class="Breaker"></div>';
			$grand_profit += ($profit_share); */
			$profit = 0;
			foreach($value['Totals']['Rev'] as $RevVal){
				$RevVals = each($RevVal);
				echo '<div>
						<p id="RC1B">Revenue Share as of '.format_date($RevVal['Date'],"Dash",false,true,false).':</p>
						<p id="RC6">'.$RevVals[0].'%</p>
						<p id="RC7" class="B">($'.number_format($RevVals[1],2,".",",").')</p>
						<br clear="all" />
					</div>';
				echo '<div class="Breaker"></div>';
				$profit += $RevVals[1];
			}
			$grand_profit += $profit;
			$credit_fee = ($value['Totals']['Total']-$temp_discprice-$value['Totals']['Disc']-$value['Totals']['Disc2'])*(3/100);
			echo '<div>
					<p id="RC1B">Processing:</p>
					<p id="RC6">3%</p>
					<p id="RC7" class="B">($'.number_format($credit_fee,2,".",",").')</p>
					<br clear="all" />
				</div>';
			echo '<div class="Breaker"></div>';
			$grand_credit_card += ($credit_fee);
			
			echo '<div>
					<p id="RC1B">Credit Card Terminal:</p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B">$'.number_format($value['Merchant']['Total'],2,".",",").'</p>
					<br clear="all" />
				</div>';
			echo '<div class="Breaker"></div>';
			echo '<div>
					<p id="RC1B">Credit Card Refunds:</p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B">($'.number_format($value['Merchant']['Refund'],2,".",",").')</p>
					<br clear="all" />
				</div>';
			echo '<div class="Breaker"></div>';
			$merchant_terminal = ($value['Merchant']['Total']-$value['Merchant']['Refund']);
			
			$Cntr = 0;
			if(count($value['Merchant']['Date']) > 0){
				foreach($value['Merchant']['Date'] as $MrchKey => $MrchVal){
					if($Cntr > 0) echo '<div class="Breaker"></div>';
					echo '<div>
							<p id="RC1B">Terminal Fee as of '.format_date($MrchKey,"Dash",false,true,false).':</p>
							<p id="RC6">'.($MrchVal['ID']*100).'%</p>
							<p id="RC7" class="B">($'.number_format($MrchVal['Fee'],2,".",",").')</p>
							<br clear="all" />
						</div>';
					$Cntr++; $merchant_terminal -= $MrchVal['Fee'];
			} } 
			unset($Cntr);
			
			$due = $value['Totals']['Total']-$temp_discprice-$temp_cost-$value['Totals']['Disc']-$value['Totals']['Disc2']-$profit-$credit_fee+$merchant_terminal;
			$value['Totals']['Due'] = $due;
			$temp_comm[$key] = $due;
			
			echo '<div style="background-color:#CCCCCC;">
					<p id="RC1B"><strong>Due to Photographer:</strong></p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B"><strong>$'.number_format($due,2,".",",").'</strong></p>
					<br clear="all" />
				</div>';
			$grand_due += ($due);
			echo '<div>
					<p id="RC1B">Shipping:</p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B">$'.number_format($value['Totals']['Ship'],2,".",",").'</p>
					<br clear="all" />
				</div>';
			$grand_ship += ($value['Totals']['Ship']);
			echo '<div class="Breaker"></div>';
			echo '<div>
					<p id="RC1B">Taxes:</p>
					<p id="RC6">&nbsp;</p>
					<p id="RC7" class="B">$'.number_format($value['Totals']['Tax'],2,".",",").'</p>
					<br clear="all" />
				</div>';
			echo '<div class="Breaker"></div>';
			echo '</blockquote>';
			$grand_tax += ($value['Totals']['Tax']);
		}
	} ?><br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } else { ?>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error">There are no orders for this month to view past orders select the date range for the orders you would like to view.</p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? }	
$records = ob_get_contents();
ob_end_clean();

echo $records; ?>
