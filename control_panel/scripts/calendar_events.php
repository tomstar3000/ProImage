<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
$Month = (isset($_POST['Month'])) ? clean_variable($_POST['Month'],true) : date("m");
$Year = (isset($_POST['Year'])) ? clean_variable($_POST['Year'],true) : date("Y");
?>

<div id="Form_Header"> <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <p>Event Calendar </p>
</div>
<div>
 <p align="center"><strong><? echo date("F",mktime (0,0,0,$Month,1,$Year)); ?>, <? echo $Year ?></strong></p>
 <p align="right">
 <select name="Month" id="Month" tabindex="1" onchange="javascript:set_form('form_','Web,Evnt','calendar','','');">
  <option value="01"<? if($Month == "01")echo ' selected="selected"';?>>January</option>
  <option value="02"<? if($Month == "02")echo ' selected="selected"';?>>February</option>
  <option value="03"<? if($Month == "03")echo ' selected="selected"';?>>March</option>
  <option value="04"<? if($Month == "04")echo ' selected="selected"';?>>April</option>
  <option value="05"<? if($Month == "05")echo ' selected="selected"';?>>May</option>
  <option value="06"<? if($Month == "06")echo ' selected="selected"';?>>June</option>
  <option value="07"<? if($Month == "07")echo ' selected="selected"';?>>July</option>
  <option value="08"<? if($Month == "08")echo ' selected="selected"';?>>August</option>
  <option value="09"<? if($Month == "09")echo ' selected="selected"';?>>September</option>
  <option value="10"<? if($Month == "10")echo ' selected="selected"';?>>October</option>
  <option value="11"<? if($Month == "11")echo ' selected="selected"';?>>November</option>
  <option value="12"<? if($Month == "12")echo ' selected="selected"';?>>December</option>
 </select>
 <select name="Year" id="Year" tabindex="2" onchange="javascript:set_form('form_','Web,Evnt','calendar','','');">
  <? $Syear = date("Y")-5;
		 for ($y = 0; $y < 10; $y++){
			$Syear = $Syear + 1;
			echo "<option value='$Syear'";
			if ($Syear == $Year) echo ' selected="selected"';
			echo ">$Syear</option>\n";
		} ?>
 </select>
 </p>
 <table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#CCCCCC">
  <tr>
   <td colspan="16"><img src="/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">S</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">M</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">T</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">W</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">T</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">F</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
   <td align="center">S</td>
   <td rowspan="14"><img src="/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
   <td colspan="15" class="bar"><img src="/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <? $stMonth = date("w", mktime(0,0,0,$Month,1,$Year));
		if ($stMonth == 0) $stMonth = 7;
		$ltDay = date("d", mktime(0,0,0,$Month+1,0,$Year));
		$stDate = -$stMonth;
		for ($d = 1; $d <= 6; $d++){
			echo '<tr valign="top">';
			for($i = 1; $i <=7; $i++){
				$stDate++;
				if (($stDate <= 0) || ($stDate > $ltDay)){
					echo '<td></td>';
				} else if (($stDate >= 1) && ($stDate <= $ltDay)){
					$date = date("Y-m-d", mktime(0,0,0,$Month,$stDate,$Year));
					if(strlen($stDate)==1) $varDate = "0".$stDate; else $varDate = $stDate;
					if($date == date("Y-m-d")) $BGColor = "#9999FF"; else $BGColor = "#FFFFFF";
					$lowdate = date("Y-m-d H:i:s",mktime(0,0,0,$Month,$stDate,$Year));
					$highdate = date("Y-m-d H:i:s",mktime(0,0,0,$Month,($stDate+1),$Year));
					$addthis = "";
					$query_Events = "SELECT `event_id`,`event_name` FROM `web_events` WHERE `event_start` >= '$lowdate' AND `event_start` < '$highdate' ORDER BY `event_start` ASC";
					$Events = mysql_query($query_Events, $cp_connection) or die(mysql_error());
					$totalRows_Events = mysql_num_rows($Events);
										
					if($totalRows_Events!=0){
						while($row_Events = mysql_fetch_assoc($Events)) {
						$eventname = $row_Events['event_name'];
						if(strlen($eventname)>15)	$eventname = substr($eventname,0,15)."...";
						$addthis .= '<tr><td bgcolor = "'.$BGColor.'" onMouseOver="this.bgColor = \'#DDDDDD\';" onMouseOut="this.bgColor = \''.$BGColor.'\'" onClick="javascript:set_form(\'form_\',\'Web,Evnt,'.$date.','.$row_Events['event_id'].'\',\'view\',\'\',\'\');" style="cursor:pointer; border-top: 1px solid #000000;">'.$eventname.'</td></tr>';
						}
					}
					mysql_free_result($Events);

					echo '<td align="left">';
					echo '<table border="0" cellspacing="0" cellpading="0" width="75" height="75" align="left">
					<tr><td bgcolor = "'.$BGColor.'" onMouseOver="this.bgColor = \'#DDDDDD\';" onMouseOut="this.bgColor = \''.$BGColor.'\'" onClick="javascript:set_form(\'form_\',\'Web,Evnt,'.$date.'\',\'query\',\'\',\'\');" style="cursor:pointer;" valign="top">'.$stDate.'</td></tr>'.$addthis.'</table></td>';
				}
			}
			echo '</tr><tr><td colspan="15"><img src="/images/spacer.gif" width="1" height="1"></td></tr>';
		}	?>
  <tr>
   <td colspan="15" class="bar"><img src="/images/spacer.gif" width="1" height="1"></td>
  </tr>
 </table>
</div>
