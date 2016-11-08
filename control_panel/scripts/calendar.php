<?php
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("PhotoExpress Pro", true);
//require_once($r_path.'security.php');
require_once($r_path.'config.php');
$Month = (isset($_POST['month'])) ? $_POST['month'] : date("m");
$Year = (isset($_POST['year'])) ? $_POST['year'] : date("Y");
$Day = 0;
$Date_val = (isset($_POST['date'])) ? $_POST['date'] : date("Y-m-d");
$Hour = (isset($_POST['hour'])) ? $_POST['hour'] : date("g");
$Min = (isset($_POST['minute'])) ? $_POST['minute'] : date("i");
$Sec = (isset($_POST['second'])) ? $_POST['second'] : date("s");
$AP = (isset($_POST['AMPM'])) ? $_POST['AMPM'] : date("A");
$Time_val = (isset($_POST['time'])) ? $_POST['time'] : date("H:i:s");
//Time stamps for selected Month and Year
$curMonth = mktime (0,0,0,$Month,1,$Year);
$MonthName = date("F",$curMonth);
//$AevNet_Path = "AevNet";
?>
<html>
<head>
<title>Calendar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/<?php echo $AevNet_Path; ?>/stylesheet/PhotoExpress.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function set_date(day_id, date){
	n_val = 1;
	document.getElementById('day').value = day_id;
	while(document.getElementById('day_'+n_val)){
		if(document.getElementById('day_'+n_val).bgColor != "#cccccc" && document.getElementById('day_'+n_val).bgColor != "#9999ff"){
			if('day_'+n_val != day_id){
				document.getElementById('day_'+n_val).bgColor = "#FFFFFF";
			}
		}
		n_val++;
	}
	document.getElementById('date').value = date;
}
function change_bgcolor(day_id, color){
	if(document.getElementById(day_id).bgColor == "#cccccc" || document.getElementById(day_id).bgColor == "#9999ff"){
		document.getElementById(day_id).bgColor = color;
	} else {
		if(day_id != document.getElementById('day').value){
			document.getElementById(day_id).bgColor = color;
		}
	}
}
function set_time(){
	hours = parseInt(document.getElementById('hour').value);
	minutes = parseInt(document.getElementById('minute').value);
	seconds = parseInt(document.getElementById('second').value);
	ampm = document.getElementById('AMPM').value;
	if(!isNaN(hours) && !isNaN(minutes) && !isNaN(seconds)){
		if(seconds >= 60){
			multi = Math.floor(seconds/60);
			seconds = seconds-(60*multi);
			minutes = minutes+multi;
		}
		if(minutes >= 60){
			multi = Math.floor(minutes/60);
			minutes = minutes-(60*multi);
			hours = hours+multi;
		}
		if(hours>12){
			hours = 1;
			if(ampm == "AM"){
				ampm = "PM";
				document.getElementById('AMPM').selectedIndex = 1;
			} else {
				ampm = "AM";
				document.getElementById('AMPM').selectedIndex = 0;
			}
		}
		document.getElementById('hour').value = hours;
		document.getElementById('minute').value = minutes;
		document.getElementById('second').value = seconds;
		if(ampm == "PM"){
			hours += 12;
		}
		if(seconds<10){
			seconds = new String("0"+seconds);
		}
		if(minutes<10){
			minutes = new String("0"+minutes);
		}
		if(hours<10){
			hours = new String("0"+hours);
		}
		document.getElementById('time').value = hours+":"+minutes+":"+seconds;
	}
}
function select_date(opener){
	fieldname = document.getElementById('feild_id').value;
	//yyyy/mm/dd
	//0123456789
	date_vale = document.getElementById('date').value.substring(5,7)+"/"+document.getElementById('date').value.substring(8,10)+"/"+document.getElementById('date').value.substring(0,4);
	
	if(document.getElementById('return_time').value == "true"){
		opener.document.getElementById(fieldname).value = date_vale+" "+document.getElementById('time').value;
	} else {
		opener.document.getElementById(fieldname).value = date_vale;
	}
	window.close();
}
</script>
<style media="screen">
table {
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
}
input, select {
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
}
</style>
</head>
<body style="margin:0px; padding:0px;" >
<table border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
 <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="Cal_Form" id="Cal_Form" method="post" enctype="application/x-www-form-urlencoded">
  <tr>
   <th colspan="15" align="center"><?php echo $MonthName ?>, <?php echo $Year ?></th>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="23"></td>
  </tr>
  <tr>
   <td colspan="16" style="background-color:#CCCCCC"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <tr>
   <td colspan="15" class="monthheader" align="center"><input type="hidden" name="day" id="day" value="<?php echo $Day; ?>">
    <select name="month" id="month" tabindex="1" onChange="document.Cal_Form.submit();">
     <option value="01"<?php if($Month == "01"){print(" selected");}?>>January</option>
     <option value="02"<?php if($Month == "02"){print(" selected");}?>>February</option>
     <option value="03"<?php if($Month == "03"){print(" selected");}?>>March</option>
     <option value="04"<?php if($Month == "04"){print(" selected");}?>>April</option>
     <option value="05"<?php if($Month == "05"){print(" selected");}?>>May</option>
     <option value="06"<?php if($Month == "06"){print(" selected");}?>>June</option>
     <option value="07"<?php if($Month == "07"){print(" selected");}?>>July</option>
     <option value="08"<?php if($Month == "08"){print(" selected");}?>>August</option>
     <option value="09"<?php if($Month == "09"){print(" selected");}?>>September</option>
     <option value="10"<?php if($Month == "10"){print(" selected");}?>>October</option>
     <option value="11"<?php if($Month == "11"){print(" selected");}?>>November</option>
     <option value="12"<?php if($Month == "12"){print(" selected");}?>>December</option>
    </select>
    <select name="year" id="year" tabindex="2" onChange="document.Cal_Form.submit();">
     <?php 
			// Graps the current year and add four addition year selections
			if(isset($_GET['future']) && $_GET['future'] == "both"){
				$Syear = date("Y")-20;
				$numofyears = 25;
			} else if(isset($_GET['future']) && $_GET['future'] == "true"){
				$Syear = date("Y")-1;
				$numofyears = 5;
			} else {
				$Syear = date("Y")-20;
				$numofyears = 20;
			}
			 for ($y = 0; $y < $numofyears; $y++){
				$Syear = $Syear + 1;
				print("<option value='$Syear'");
				if ($Syear == $Year){print(" selected");}
				print(">$Syear</option>\n");
			}
		  ?>
    </select>
   </td>
   <td height="10"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="10"></td>
  </tr>
  <tr>
   <td colspan="16" style="background-color:#CCCCCC"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <!-- Days of the week plus cells to display a white bar between each day -->
  <tr>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">S</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">M</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">T</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">W</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">T</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">F</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td align="center">S</td>
   <td style="background-color:#CCCCCC" rowspan="14"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="10"></td>
  </tr>
  <tr>
   <td colspan="16" style="background-color:#CCCCCC"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <?php
		// Dynamically builds calendar based off of selected Month and Year
		// Finds what day the first day of the selected Month falls on (i.e. Monday, Tuesday)
		$stMonth = date("w",$curMonth);
		// If day of week is 0 set to Saturday, allows calendar to start building on Sunday
		if ($stMonth == 0){
			$stMonth = 7;
		}
		// Last day of Month
		$ltDay = date("d", mktime(0,0,0,$Month+1,0,$Year));
		// Start date variable 
		$stDate = -$stMonth;
		// Builds rows for calendar
		for ($d = 1; $d <= 6; $d++){
			print("<tr valign='top'>\n");
			// Builds columns for calendar
			for($i = 1; $i <=7; $i++){
				// Incriments the start date variable by 1 day
				$stDate++;
				// If day does not exist fill in background with color else insert date and any events that are held on that day
				if (($stDate <= 0) || ($stDate > $ltDay)){
					print("<td height=\"15\" bgcolor=\"#CCCCCC\">&nbsp;</td>\n");
				} else if (($stDate >= 1) && ($stDate <= $ltDay)){
					// Time variable for total date
					$curTimeStamp = mktime(0,0,0,$Month,$stDate,$Year);
					// Time Stamp for date to produce YYYY-MM-DD
					$date = date("Y-m-d", $curTimeStamp);
					// Fills each cell with information and makes the cell selectable
					// If date is equal to today fill in background with color if not leave blank
					if(strlen($stDate)==1){
						$varDate = "0".$stDate;
					} else {
						$varDate = $stDate;
					}
					if($date == date("Y-m-d")){
						echo "<td id=\"day_".$stDate."\" bgcolor = \"#9999FF\" onmouseover=\"change_bgcolor(this.id,'#CCCCCC');\" onmouseout=\"change_bgcolor(this.id,'#9999FF'); \" style=\"cursor:pointer;\" onClick=\"javascript: set_date(this.id, '".$Year."-".$Month."-".$varDate."'); \">".$stDate."\n";
					} else {
						echo "<td id=\"day_".$stDate."\" bgcolor = \"#FFFFFF\" onmouseover=\"change_bgcolor(this.id,'#DDDDDD');\" onmouseout=\"change_bgcolor(this.id,'#FFFFFF');\" style=\"cursor:pointer;\" onClick=\"javascript: set_date(this.id,'".$Year."-".$Month."-".$varDate."'); \">".$stDate."\n";
					}							
					print("</td>");
				}
			}
			print("<td><img src=\"/".$AevNet_Path."/images/spacer.gif\" width=\"1\" height=\"25\"></td></tr>\n <tr><td colspan=\"15\" style=\"background-color:#CCCCCC\"><img src=\"/".$AevNet_Path."/images/spacer.gif\" width=\"1\" height=\"1\"></td></tr>\n");
		}
	?>
  <tr>
   <td colspan="16" style="background-color:#CCCCCC"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <?php if($_GET['time'] == "true"){ ?>
  <tr>
   <td colspan="15" align="center"><input type="text" name="hour" id="hour" value="<?php echo $Hour; ?>" size="3" onKeyUp="set_time();">
    :
    <input type="text" name="minute" id="minute" value="<?php echo $Min; ?>" size="3" onKeyUp="set_time();">
    :
    <input type="text" name="second" id="second" value="<?php echo $Sec; ?>" size="3" onKeyUp="set_time();">
    &nbsp;
    <select name="AMPM" id="AMPM" onChange="set_time();">
     <option value="AM"<?php if ($AP == "AM"){print(" selected");}?>>AM</option>
     <option value="PM"<?php if ($AP == "PM"){print(" selected");}?>>PM</option>
    </select></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="23"></td>
  </tr>
  <tr>
   <td colspan="16"  style="background-color:#CCCCCC"><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
  </tr>
  <?php } ?>
  <tr>
   <td colspan="15" align="center"><input type="button" name="Select" id="Select" value="Select Date" onClick="select_date(opener);">
    <input type="hidden" name="feild_id" id="feild_id" value="<?php echo $_GET['field']; ?>">
    <input type="hidden" name="return_time" id="return_time" value="<?php echo $_GET['time']; ?>">
    <input type="hidden" name="date" id="date" value="<?php echo $Date_val; ?>">
    <input type="hidden" name="time" id="time" value="<?php echo $Time_val; ?>"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="23"></td>
  </tr>
  <!-- Spacer cells at bottom of calendar to keep table view correct -->
  <tr>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="25" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
   <td><img src="/<?php echo $AevNet_Path; ?>/images/spacer.gif" width="1" height="1"></td>
  </tr>
 </form>
</table>
</body>
</html>
