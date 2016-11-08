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
$query_get_oldest= "SELECT `invoice_paid_date` FROM `orders_invoice` ORDER BY `invoice_paid_date` ASC LIMIT 0,1";
$get_oldest = mysql_query($query_get_oldest, $cp_connection) or die(mysql_error());
$row_get_oldest = mysql_fetch_assoc($get_oldest);
$year = substr($row_get_oldest['invoice_paid_date'],0,4);
$month = substr($row_get_oldest['invoice_paid_date'],5,2);
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
<div id="Report_Date">
  <p>Yearly Report
    <select name="Report_Year" id="Report_Year" onchange="set_time(this.value);">
      <option value="0">-- Select Year --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){ ?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,1,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,1,0,($n+1))); ?>"><? echo $n; ?></option>
      <? } ?>
    </select>
    Monthly Report
    <select name="Report_Month" id="Report_Month" onchange="set_time(this.value);">
      <option value="0">-- Select Month --</option>
      <? for($n = intval($year); $n<=date("Y"); $n++){
				if($n == $year) $z = $month; else $z = 1;
				if($year == date("Y"))$end = date("m"); else $end = 12;
				for($z = intval($z); $z<=$end; $z++){?>
      <option value="<? echo date("Y-m-d", mktime(0,0,0,$z,1,$n))."[-]".date("Y-m-d", mktime(0,0,0,($z+1),0,$n)); ?>"><? echo date("M",mktime(0,0,0,$z,1,date("Y")))." (".$n.")"; ?></option>
      <? } }?>
    </select>
    <br />
    Select Report From:
    <input type="text" name="From" id="From" value="<? echo format_date($Start,"Dash",false,true,false); ?>" />
    To:
    <input type="text" name="To" id="To" value="<? echo format_date($End,"Dash",false,true,false); ?>" />
    <input type="submit" name="Submit" id="Submit" value="Generate Report" />
  </p>
</div>
<?
if(count($sorted_array)>0){
	echo '<div style="border:solid 1px #666666; margin-top:10px;"><p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#CCCCCC"><strong>Taxes</strong></p>';
	foreach($sorted_array as $k => $v){
		$temp_date = explode(".",$k);
		$temp_date = date("F Y",mktime(substr($temp_date[0],11,2),substr($temp_date[0],14,2),substr($temp_date[0],17,2),substr($temp_date[0],5,2),substr($temp_date[0],8,2),substr($temp_date[0],0,4)))." - ".date("F Y",mktime(substr($temp_date[1],11,2),substr($temp_date[1],14,2),substr($temp_date[1],17,2),substr($temp_date[1],5,2),substr($temp_date[1],8,2),substr($temp_date[1],0,4)));
		echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
		echo '<div style="height:22px;" class="TotalCellnoBD"><p><strong>'.$temp_date.'</strong></p></div><br clear="all" />';
		echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
		echo '<div style="background-color:#CCCCCC; height:22px;">
			<div style="width:140px; text-align:left" class="TotalCell"><p><strong>State</strong></p></div>
			<div style="width:90px;" class="TotalCell"><p><strong>State Taxes</strong></p></div>
			<div style="width:90px;" class="TotalCell"><p><strong>County Taxes</strong></p></div>
			<div style="width:90px;" class="TotalCell"><p><strong>City Taxes</strong></p></div>
			<div style="width:90px;" class="TotalCell"><p><strong>CD Taxes</strong></p></div>
			<div style="width:90px;" class="TotalCell"><p><strong>FD Taxes</strong></p></div>
			<div style="width:90px;" class="TotalCell"><p><strong>Other Taxes</strong></p></div>
			<div style="width:90px;" class="TotalCellnoBD"><p><strong>Total</strong></p></div>
			</div>';
		foreach($v['States'] as $key => $value){
			if(isset($value['Taxes'])){
				echo '<div style="height:22px;">
			<div style="width:140px; text-align:left" class="TotalCell"><p>'.trim(mb_convert_encoding($key,"HTML-ENTITIES","UTF-8")).'</p></div>
			<div style="width:90px;" class="TotalCell"><p>$'.number_format($value['Taxes']['STATE'],2,".",",").'</p></div>
			<div style="width:90px;" class="TotalCell"><p>$'.number_format($value['Taxes']['COUNT'],2,".",",").'</p></div>
			<div style="width:90px;" class="TotalCell"><p>$'.number_format($value['Taxes']['CITY'],2,".",",").'</p></div>
			<div style="width:90px;" class="TotalCell"><p>$'.number_format($value['Taxes']['CD'],2,".",",").'</p></div>
			<div style="width:90px;" class="TotalCell"><p>$'.number_format($value['Taxes']['FD'],2,".",",").'</p></div>
			<div style="width:90px;" class="TotalCell"><p>$'.number_format($value['Taxes']['OTHER'],2,".",",").'</p></div>
			<div style="width:90px;" class="TotalCellnoBD"><p>$'.number_format($value['Taxes']['TOTAL'],2,".",",").'</p></div>
			</div>';
			}
		}
		echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>
		<div style="height:22px;">
		<div style="width:140px; text-align:left" class="TotalCell"><p><strong>Total</strong></p></div>
		<div style="width:90px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Taxes']['STATE'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Taxes']['COUNT'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Taxes']['CITY'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Taxes']['CD'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Taxes']['FD'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Taxes']['OTHER'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCellnoBD"><p><strong>$'.number_format($v['Totals']['Taxes']['TOTAL'],2,".",",").'</strong></p></div>
		</div>';
	}
	echo '</div>';
	echo '<div style="border:solid 1px #666666; margin-top:10px;"><p style="margin:0px; padding-top:5px; padding-bottom:5px; background-color:#CCCCCC"><strong>Shipping</strong></p>';
	foreach($sorted_array as $k => $v){
		$temp_date = explode(".",$k);
		$temp_date = date("F Y",mktime(substr($temp_date[0],11,2),substr($temp_date[0],14,2),substr($temp_date[0],17,2),substr($temp_date[0],5,2),substr($temp_date[0],8,2),substr($temp_date[0],0,4)))." - ".date("F Y",mktime(substr($temp_date[1],11,2),substr($temp_date[1],14,2),substr($temp_date[1],17,2),substr($temp_date[1],5,2),substr($temp_date[1],8,2),substr($temp_date[1],0,4)));
		echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
		echo '<div style="height:22px;" class="TotalCellnoBD"><p><strong>'.$temp_date.'</strong></p></div><br clear="all" />';
		echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>';
		echo '<div style="background-color:#CCCCCC; height:22px;">
			<div style="width:280px; text-align:left" class="TotalCell"><p><strong>State</strong></p></div>
			<div style="width:100px;" class="TotalCell"><p><strong>UPS</strong></p></div>
			<div style="width:100px;" class="TotalCell"><p><strong>USPS</strong></p></div>
			<div style="width:100px;" class="TotalCell"><p><strong>FedEx</strong></p></div>
			<div style="width:100px;" class="TotalCell"><p><strong>DHL</strong></p></div>
			<div style="width:90px;" class="TotalCellnoBD"><p><strong>Total</strong></p></div>
			</div>';
		foreach($v['States'] as $key => $value){
			echo '<div style="height:22px;">
		<div style="width:280px; text-align:left" class="TotalCell"><p>'.trim(mb_convert_encoding($key,"HTML-ENTITIES","UTF-8")).'</p></div>
		<div style="width:100px;" class="TotalCell"><p>$'.number_format($value['Ship']['UPS'],2,".",",").'</p></div>
		<div style="width:100px;" class="TotalCell"><p>$'.number_format($value['Ship']['USPS'],2,".",",").'</p></div>
		<div style="width:100px;" class="TotalCell"><p>$'.number_format($value['Ship']['FedEx'],2,".",",").'</p></div>
		<div style="width:100px;" class="TotalCell"><p>$'.number_format($value['Ship']['DHL'],2,".",",").'</p></div>
		<div style="width:90px;" class="TotalCellnoBD"><p>$'.number_format(($value['Ship']['Total']),2,".",",").'</p></div>
		</div>';
		}
		echo '<div style="height:1px; background-color:#999999; padding:0px; margin:0px;"></div>
		<div style="height:22px;">
		<div style="width:280px; text-align:left" class="TotalCell"><p><strong>Total</strong></p></div>
		<div style="width:100px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Ship']['UPS'],2,".",",").'</strong></p></div>
		<div style="width:100px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Ship']['USPS'],2,".",",").'</strong></p></div>
		<div style="width:100px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Ship']['FedEx'],2,".",",").'</strong></p></div>
		<div style="width:100px;" class="TotalCell"><p><strong>$'.number_format($v['Totals']['Ship']['DHL'],2,".",",").'</strong></p></div>
		<div style="width:90px;" class="TotalCellnoBD"><p><strong>$'.number_format($v['Totals']['Ship']['Total'],2,".",",").'</strong></p></div>
		</div>';
	}
	echo '</div>';
} else {
	echo '<div style="background-color:#CCCCCC; height:22px; padding-top:5px;"><p><strong>There are no reports for this date range.</strong></p></div>';
}	?>
