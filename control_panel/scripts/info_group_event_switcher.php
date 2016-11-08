<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php'; ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Move Groups To</h2>
 <p id="Back"><a href="<? echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>">Back</a></p>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <?
	$query_get_info = "SELECT 
	`photo_event`.`event_id`,
	`photo_event`.`event_name`
	FROM `photo_event`
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	WHERE `photo_event`.`cust_id` = '$CustId'
		AND `event_use` = 'y'
	GROUP BY `photo_event`.`event_id`
	ORDER BY `photo_event`.`event_name` ASC";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());	
	?>
 <tr>
  <td>Event: </td>
  <td><select name="Event" id="Event" onchange="javascript: this.form.submit();">
    <option value="0"<? if($Event=="0")echo' selected="selected"';?>>-- Select Event --</option>
    <? while($row_get_info = mysql_fetch_assoc($get_info)){ ?>
    <option value="<? echo $row_get_info['event_id']; ?>"<? if($Event==$row_get_info['event_id'])echo' selected="selected"';?>> <? echo $row_get_info['event_name']; ?> </option>
    <? } ?>
   </select>
   &nbsp;</td>
 </tr><?
	$query_get_info = "SELECT `group_id`,
	`group_name`
	FROM `photo_event_group`
	WHERE `event_id` = '$Event'
		AND `group_use` = 'y'
		AND `parnt_group_id` = '0'
	GROUP BY `group_id`
	ORDER BY `group_name` ASC";
	$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
	$total_get_info = mysql_num_rows($get_info);
	if($total_get_info > 0){ 
	?>
 <tr>
  <td>Group: </td>
  <td><select name="Group[]" id="Group" onchange="javascript: this.form.submit();"<? if($Event == "0")echo ' disabled'; ?>>
    <option value="0"<? if(count($Group) == 0 || $Group[0]=="0")echo' selected="selected"';?>>-- Select Group --</option>
    <? while($row_get_info = mysql_fetch_assoc($get_info)){ ?>
    <option value="<? echo $row_get_info['group_id']; ?>"<? if($Group==$row_get_info['group_id'] || $Group[0]==$row_get_info['group_id'])echo' selected="selected"';?>> <? echo $row_get_info['group_name']; ?> </option>
    <? } ?>
   </select>
   &nbsp;</td>
 </tr>
 <? }
 	foreach($Group as $k => $v){ if($k < 2){
		$query_get_info = "SELECT `group_id`,
	`group_name`
	FROM `photo_event_group`
	WHERE `event_id` = '$Event'
		AND `group_use` = 'y'
		AND `parnt_group_id` = '$v'
	GROUP BY `group_id`
	ORDER BY `group_name` ASC";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$total_get_info = mysql_num_rows($get_info);
		if($total_get_info > 0){ ?> 
		<tr>
  <td>Group: </td>
  <td><select name="Group[]" id="Group" onchange="javascript: this.form.submit();">
    <option value="0"<? if(!isset($Group[($k+1)]) || $Group[($k+1)]=="0")echo' selected="selected"';?>>-- Select Group --</option>
    <? while($row_get_info = mysql_fetch_assoc($get_info)){ ?>
    <option value="<? echo $row_get_info['group_id']; ?>"<? if($Group[($k+1)]==$row_get_info['group_id'])echo' selected="selected"';?>> <? echo $row_get_info['group_name']; ?> </option>
    <? } ?>
   </select>
   &nbsp;</td>
 </tr>
<? } } } ?>
</table>
<? foreach($Groups as $k => $v){ ?>
<input type="hidden" name="Groups_items[]" id="Groups_items_<? echo ($k+1); ?>" value="<? echo $v; ?>" />
<? } ?>
<input type="hidden" name="Mover" id="Mover" value="true">
<? $cont = 'edit'; ?>
