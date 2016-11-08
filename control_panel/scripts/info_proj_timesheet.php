<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
if($path[3] == "Proj"){
	$crumb_count = 7;
} else {
	$crumb_count = 4;
}
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?php if($is_enabled){  ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,$crumb_count)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
    <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Timesheet Information</p></th>
  </tr><?php } else { ?>
  <tr>
    <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,$crumb_count-1)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
      <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Timesheet Information</p></th>
  </tr>
  <?php } 
	$query_get_emp = "SELECT `emp_id`, `emp_fname`, `emp_lname` FROM `emp_employees` ORDER BY `emp_lname`, `emp_fname` ASC";
	$get_emp = mysql_query($query_get_emp, $cp_connection) or die(mysql_error());
	$row_get_emp = mysql_fetch_assoc($get_emp);
	$totalRows_get_emp = mysql_num_rows($get_emp);
  ?>
  <tr>
    <td><strong>Employee:</strong></td>
    <td><?php if(!$is_enabled){
		do {
			if($row_get_emp['emp_id'] == $Emp_id) echo $row_get_emp['emp_lname'].", ".$row_get_emp['emp_fname'];
		} while ($row_get_emp = mysql_fetch_assoc($get_emp));
	} else { ?>
      <select name="Employee" id="Employee">
        <option value="0"<?php if($Emp_id == 0){print(" selected=\"selected\"");} ?>>-- Select Employee --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_emp['emp_id']; ?>"<?php if($row_get_emp['emp_id'] == $Emp_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_emp['emp_lname'].", ".$row_get_emp['emp_fname']; ?></option>
        <?php } while ($row_get_emp = mysql_fetch_assoc($get_emp)); ?>
      </select>
      <?php } ?>
      <input type="hidden" name="Time_Id" id="Time_Id" value="<?php echo $TId;?>" />
      <input type="hidden" name="Proj_Id" id="Proj_Id" value="<?php echo $Proj_Id; ?>" />
      &nbsp;</td>
  </tr>
  <?php  	
	mysql_free_result($get_emp);
  
	$query_get_worktype = "SELECT * FROM `proj_worktype` ORDER BY `proj_worktype` ASC";
	$get_worktype = mysql_query($query_get_worktype, $cp_connection) or die(mysql_error());
	$row_get_worktype = mysql_fetch_assoc($get_worktype);
	$totalRows_get_worktype = mysql_num_rows($get_worktype);
  ?>
  <tr>
    <td><strong>Work Type:</strong></td>
    <td><?php if(!$is_enabled){
		do {
			if($row_get_worktype['proj_worktype_id'] == $Work_id) echo $row_get_worktype['proj_worktype'];
		} while ($row_get_worktype = mysql_fetch_assoc($get_worktype));
	} else { ?>
      <select name="Work Type" id="Work_Type">
        <option value="0"<?php if($Work_id == 0){print(" selected=\"selected\"");} ?>>-- Select Work Type --</option>
        <?php do { ?>
        <option value="<?php echo $row_get_worktype['proj_worktype_id']; ?>"<?php if($row_get_worktype['proj_worktype_id'] == $Work_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_worktype['proj_worktype']; ?></option>
        <?php } while ($row_get_worktype = mysql_fetch_assoc($get_worktype)); ?>
      </select>
      <?php } 
	  mysql_free_result($get_worktype); ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td width="20%"><strong>Date</strong></td>
    <td width="80%"><?php if(!$is_enabled){ echo format_date($TDate,"DayShort","Standard",true,true); } else { ?>
      <input type="text" name="Date" id="Date" value="<?php echo $TDate; ?>" />
      <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=false&field=Date','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
      Date</a> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <script language="javascript" src="/<?php echo $AevNet_Path; ?>/javascript/currency_formating.js"></script>
  <script language="javascript">
  	function calc_total(){
		document.getElementById('Amount').value = document.getElementById('Bill_Rate').value * document.getElementById('Hours').value;
		newtotal = document.getElementById('Amount').value;
		newtotal = CurrencyFormatted(newtotal);
		document.getElementById('Total_Amt').innerHTML = "$"+newtotal;
	}
  </script>
  <tr>
    <td><strong>Bill Rate: </strong></td>
    <td><?php if(!$is_enabled){ echo $TRate; } else { ?>
      <input type="text" name="Bill Rate" id="Bill_Rate" value="<?php echo $TRate; ?>" size="3" onkeyup="calc_total();" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Hours:</strong></td>
    <td><?php if(!$is_enabled){ echo $THours; } else { ?>
      <input type="text" name="Hours" id="Hours" value="<?php echo $THours; ?>" size="3" onkeyup="calc_total();" />
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Total:</strong></td>
    <td><span id="Total_Amt">$<?php echo number_format($TAmt,2,".",","); ?></span>
      <input type="hidden" name="Amount" id="Amount" value="<?php echo $TAmt; ?>" />
      &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<?php if($is_enabled){ ?> height:300px;<?php } ?>" valign="top">
	<?php if(!$is_enabled){	echo $TDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $TDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
      &nbsp;</td>
  </tr>
</table>
