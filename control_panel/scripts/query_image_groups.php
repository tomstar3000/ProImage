<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';

if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	$items = array();
	$items = $_POST['Image_items'];
	function delete_vals($value){
		global $cp_connection;
		$del= "UPDATE `photo_event_images` SET `image_active` = 'n' WHERE `image_id` = '$value'";
		$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
		
		$optimize = "OPTIMIZE TABLE `photo_event_images`";
		$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	}
	if(count($items)>0){
		foreach ($items as $key => $value){
			delete_vals($value);
		}
	}
}
$query_get_info = "SELECT `image_folder`,`image_tiny`, `image_id` FROM `photo_event_images` WHERE `group_id` = '$GId' AND `image_active` = 'y' ORDER BY `image_tiny`";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$total_get_info = mysql_num_rows($get_info);
?>
<script type="text/javascript">
function form_submit(id){
	mywindow = window.open("/control_panel/image_updater.php?<? echo ($token!="") ? $token."&": ""; ?>image="+id,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=300,height=250");
}
function changeselect(checkvalue,fieldid,fieldcount){
	for(n=1;n<=document.getElementById(fieldcount).value;n++){
		document.getElementById(fieldid+"_"+n).checked = checkvalue;
	}
}
function clickthis(field,fieldid){
	if(document.getElementById(fieldid+"_"+field).checked == true){
		document.getElementById(fieldid+"_"+field).checked = false;
	} else {
		document.getElementById(fieldid+"_"+field).checked = true;
	}
}
function confirmdelete(formid, message, action, controller){
	if(confirm("You Sure You Want to "+message)){
		document.getElementById(controller).value = action;
		document.getElementById(formid).submit();
	}
}
function showdiv(divid){
	if(document.getElementById(divid).style.display == "none"){
		document.getElementById(divid).style.display = "";
	} else {
		document.getElementById(divid).style.display = "none";
	}
}
</script>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Group Images</h2>
 <p id="Add"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','upload','<? echo $sort; ?>','<? echo $rcrd; ?>');">Add</a></p>
</div>
<? if($total_get_info >0 ){ ?>
<div>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td colspan="2"><img src="/control_panel/images/btn_select_all.jpg" name="Check All" onclick="changeselect(true,'Image_items','Image_count');" alt="Check All" /><img src="/control_panel/images/btn_deselect.jpg" name="Un-Check All" onclick="changeselect(false,'Image_items','Image_count');" alt="Un-Check All" /></td>
   <td align="right" style="text-align:right" colspan="2"><img src="/control_panel/images/btn_moveto.jpg" alt="Move To" width="97" height="23" hspace="5" border="0"  onclick="confirmdelete('form_action_form','Move Image(s)','Move', 'Controller');" /><img src="/control_panel/images/btn_delete.jpg" name="Delete" onclick="confirmdelete('form_action_form','Delete Images(s)','Delete', 'Controller');" alt="Delete" /></td>
  </tr>
  <tr>
   <? 
		$a=0;
		$b=0;
		do{
			$b++;
			$index = strrpos($row_get_info['image_folder'], "/");
			$Folder = substr($row_get_info['image_folder'],0,$index);	
			$index = strrpos($Folder, "/");
			$Folder = substr($Folder,0,$index); ?>
   <td valign="top"><a href="/<? echo $Folder."/Thumbnails/".$row_get_info['image_tiny'];?>" target="_blank"><img src="/<? echo $Folder."/Icon/".$row_get_info['image_tiny'];?>" alt="<? echo $row_get_info['image_tiny']; ?>" width="50" hspace="2" vspace="2" border="0" align="left"></a>
    <input type="checkbox" name="Image_items[]" id="Image_items_<? echo $b; ?>" value="<? echo $row_get_info['image_id'];?>" />
    <? echo $row_get_info['image_tiny']; ?><br />
    <img src="/control_panel/images/btn_update.jpg" alt="Update Image" width="131" height="25" border="0" style="cursor:pointer" onclick="javascript:form_submit('<? echo $row_get_info['image_id']; ?>');" /></td>
   <? 
	$a++;
		if($a == 4){
			$a = 0;
			echo '</tr><tr><td colspan="4"><hr /></td></tr><tr>';
		}
	} while($row_get_info = mysql_fetch_assoc($get_info)); 
	if($a < 4) for($n=$a;$n<4;$n++){ echo '<td>&nbsp;</td>'; }
	?>
  </tr>
  <tr>
   <td colspan="2"><input type="hidden" name="Image_count" id="Image_count" value="<? echo $b; ?>" />
    <img src="/control_panel/images/btn_select_all.jpg" name="Check All" onclick="changeselect(true,'Image_items','Image_count');" alt="Check All" /><img src="/control_panel/images/btn_deselect.jpg" name="Un-Check All" onclick="changeselect(false,'Image_items','Image_count');" alt="Un-Check All" /></td>
   <td align="right" style="text-align:right" colspan="2"><img src="/control_panel/images/btn_moveto.jpg" alt="Move To" width="97" height="23" hspace="5" border="0"  onclick="confirmdelete('form_action_form','Move Image(s)','Move', 'Controller');" /><img src="/control_panel/images/btn_delete.jpg" name="Delete" onclick="confirmdelete('form_action_form','Delete Images(s)','Delete', 'Controller');" alt="Delete" /></td>
  </tr>
 </table>
</div>
<? } ?>
