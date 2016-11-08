<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_borders.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
if(!$is_enabled){ ?>

<div id="Tri_Nav">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Overview</p></td>
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Rel'; ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Relationships</p></td>
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Key'; ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Keywords</p></td>
   <td align="center" class="Tabs" onclick="javascript:set_form('form_','<? echo implode(',',array_slice($path,0,3)).',Warnt'; ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');"><p>Warranty</p></td>
  </tr>
 </table>
</div>
<?php } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <?php if($is_enabled){  ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
   <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Border Information</p></th>
 </tr>
 <?php } else { ?>
 <tr>
  <th colspan="4" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,3)); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /><img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,2)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
   <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Border Information</p></th>
 </tr>
 <?php 
  }
  if($Image != "" || $Thumb != "" || $Icon != ""){
  	echo "<tr><td colspan=\"4\" style=\"background-color:#FFFFFF; color:#CC0000;\">".$Image."<br />".$Thumb."<br />".$Icon."</td></tr>";
  }
	$query_get_parent = "SELECT `spec_part_id` FROM `prod_specs` WHERE `spec_id` = '$SPId'";
	$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
	$row_get_parent = mysql_fetch_assoc($get_parent);
	$totalRows_get_parent = mysql_num_rows($get_parent);
	
	if($totalRows_get_parent != 0){
		$TempId = $row_get_parent['spec_part_id'];		
		
		$query_get_specs = "SELECT `spec_id`, `spec_name` FROM `prod_specs` WHERE `spec_part_id` = '$TempId'";
		$get_specs = mysql_query($query_get_specs, $cp_connection) or die(mysql_error());
		$row_get_specs = mysql_fetch_assoc($get_specs);
		$totalRows_get_specs = mysql_num_rows($get_specs);
	} else {
		$totalRows_get_specs = 0;
	}
	mysql_free_result($get_parent);

	if($totalRows_get_prarent_id != 0){
		mysql_free_result($get_specs);
	}
?>
 <tr>
  <td width="20%"><strong>Product Name: </strong></td>
  <td><?php if(!$is_enabled){	echo $PName; } else { ?>
   <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $PName; ?>" />
   <?php } ?>
   &nbsp;</td>
  <td colspan="2"><?php if($cont != "add" && $PDate != "0000-00-00 00:00:00" && $PDate != ""){ ?>
   Inserted/Updated: <?php echo format_date($PDate,"DayShort","Standard",true,true); }?>&nbsp;</td>
 </tr>
 <?php if(count($path) < 4){ ?>
 <tr>
  <td><strong>Print Channel: </strong></td>
  <td><?php if(!$is_enabled){	echo $PNumber; } else { ?>
   <input type="text" name="Product Number" id="Product_Number" maxlength="50" value="<?php echo $PNumber; ?>" />
   <?php } ?>
   &nbsp;</td>
  <td><strong>Serial Number:</strong></td>
  <td><?php if(!$is_enabled){	echo $PSerial; } else { ?>
   <input type="text" name="Serial Number" id="Serial_Number" maxlength="75" value="<?php echo $PSerial; ?>" />
   <?php } ?>
   &nbsp;</td>
 </tr>
 <?php
	$query_get_man = "SELECT `sell_id`,`sell_cname` FROM `sellers` ORDER BY `sell_cname` ASC";
	$get_man = mysql_query($query_get_man, $cp_connection) or die(mysql_error());
	$row_get_man = mysql_fetch_assoc($get_man);
  ?>
 <?php
	if(count($PSel_Cal>0) && is_array($PSel_Cal)){
		foreach($PSel_Cal as $key => $value){
			if($value != $PCats[$key]){
				if($PCats[$key] == 0){
					if($key == 0){
						$PCat = 0;
					} else {
						$PCat = $PCats[$key-1];
					}
				} else {
					$PCat = $PCats[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PCat,$n,'prod_categories','cat_parent_id','cat_id'); ?>
 <tr>
  <td valign="top"><strong>Category:</strong></td>
  <td valign="top" style="padding:0px; margin:0px;"><table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
    <?php
      for($n=count($parents)-1;$n>=0;$n--){ 
      $Temp_id = $parents[$n][0];
      $Cur_id = $parents[$n][1];
      
      $query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
      $get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
      $row_get_categories = mysql_fetch_assoc($get_categories);
      $totalRows_get_categories = mysql_num_rows($get_categories);
      ?>
    <tr>
     <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
		echo "&nbsp;";
	} else { ?>
      <select name="Category[]" id="Category[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
       <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Category --</option>
       <?php do { ?>
       <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
       <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <input type="hidden" name="Sel_Cat[]" id="Sel_Cat[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?></td>
    </tr>
    <?php mysql_free_result($get_categories); } 
  if($cont == "add" || $cont == "edit"){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
    <tr>
     <td><select name="Category[]" id="Category[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
       <option value="0">-- Select Category --</option>
       <?php do { ?>
       <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
       <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <input type="hidden" name="Sel_Cat[]" id="Sel_Cat[]" value="-1" /></td>
    </tr>
    <?php }	mysql_free_result($get_categories);	} ?>
   </table></td>
  <td valign="top" style="padding:0px; margin:0px;"><table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
    <?php
 	if(count($PSel_Cal_2>0) && is_array($PSel_Cal_2)){
		foreach($PSel_Cal_2 as $key => $value){
			if($value != $PCats_2[$key]){
				if($PCats_2[$key] == 0){
					if($key == 0){
						$PCat_2 = 0;
					} else {
						$PCat_2 = $PCats_2[$key-1];
					}
				} else {
					$PCat_2 = $PCats_2[$key];
				}
				break;
			}
		}
	}
	$n = 0;
	$parents = array();
	find_parents($PCat_2,$n,'prod_categories','cat_parent_id','cat_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
		$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
		$row_get_categories = mysql_fetch_assoc($get_categories);
		$totalRows_get_categories = mysql_num_rows($get_categories);
	?>
    <tr>
     <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
		echo "&nbsp;";
	} else { ?>
      <select name="Category_2[]" id="Category_2[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
       <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Category --</option>
       <?php do { ?>
       <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
       <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <input type="hidden" name="Sel_Cat_2[]" id="Sel_Cat_2[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?></td>
    </tr>
    <?php 
  	mysql_free_result($get_categories);
  } 
  if($cont == "add" || $cont == "edit"){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat_2'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
    <tr>
     <td><select name="Category_2[]" id="Category_2[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
       <option value="0">-- Select Category --</option>
       <?php do { ?>
       <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
       <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <input type="hidden" name="Sel_Cat_2[]" id="Sel_Cat_2[]" value="-1" /></td>
    </tr>
    <?php }	mysql_free_result($get_categories);	}?>
   </table></td>
  <td valign="top" style="padding:0px; margin:0px;"><table border="0" cellpadding="0" cellspacing="0" style="padding:0px; margin:0px;">
    <?php
 	if(count($PSel_Cal_3>0) && is_array($PSel_Cal_3)){
		foreach($PSel_Cal_3 as $key => $value){
			if($value != $PCats_3[$key]){
				if($PCats_3[$key] == 0){
					if($key == 0){
						$PCat_3 = 0;
					} else {
						$PCat_3 = $PCats_3[$key-1];
					}
				} else {
					$PCat_3 = $PCats_3[$key];
				}
				break;
			}
		}
	}
	
	$n = 0;
	$parents = array();
	find_parents($PCat_3,$n,'prod_categories','cat_parent_id','cat_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];
		
		$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
		$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
		$row_get_categories = mysql_fetch_assoc($get_categories);
		$totalRows_get_categories = mysql_num_rows($get_categories);
	?>
    <tr>
     <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
		echo "&nbsp;";
	} else { ?>
      <select name="Category_3[]" id="Category_3[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
       <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Category --</option>
       <?php do { ?>
       <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
       <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <input type="hidden" name="Sel_Cat_3[]" id="Sel_Cat_3[]" value="<?php echo $Cur_id; ?>" />
      <?php } ?></td>
    </tr>
    <?php mysql_free_result($get_categories); } 
  if($cont == "add" || $cont == "edit"){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat_3'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
    <tr>
     <td><select name="Category_3[]" id="Category_3[]" onchange="document.getElementById('Controller').value='false'; document.getElementById('form_action_form').submit();">
       <option value="0">-- Select Category --</option>
       <?php do { ?>
       <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
       <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
      </select>
      <input type="hidden" name="Sel_Cat_3[]" id="Sel_Cat_3[]" value="-1" /></td>
    </tr>
    <?php } mysql_free_result($get_categories); } ?>
   </table></td>
 </tr>
 <tr>
  <td><strong>Discontinue:</strong></td>
  <td colspan="3"><?php if(!$is_enabled){
		if($PDiscon != "") echo format_date($PDiscon,"DayShort","Standard",true,true);
	} else { ?>
   <input type="text" name="Discontinue" id="Discontinue" maxlength="75" value="<?php echo $PDiscon; ?>" />
   <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Discontinue','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
   Date</a> (yyyy-mm-dd hh:mm:ss)
   <?php } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td colspan="4"><strong>Short Description:</strong></td>
 </tr>
 <tr>
  <td colspan="4" style="padding:10px;<?php if($cont == "add" || $cont == "edit"){ ?> height:300px;<?php } ?>" valign="top"><?php if(!$is_enabled){	echo $PSDesc; } else {
		$oFCKeditor = new FCKeditor('Short_Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $PSDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create();
		$output = $oFCKeditor->CreateHtml();
	}?></td>
 </tr>
 <tr>
  <td colspan="4"><strong>Description:</strong></td>
 </tr>
 <tr>
  <td colspan="4" style="padding:10px;<?php if($cont == "add" || $cont == "edit"){ ?> height:300px;<?php } ?>" valign="top"><?php if(!$is_enabled){	echo $PDesc; } else {
		$oFCKeditor = new FCKeditor('Description');
		$oFCKeditor->BasePath = 'FCKeditor/';
		$oFCKeditor->Value = $PDesc;
		$oFCKeditor->Width = '100%';
		$oFCKeditor->Height = '290';
		$oFCKeditor->Create() ;
		$output = $oFCKeditor->CreateHtml();
	} ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Thumbnail:</strong></td>
  <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="Icon" id="Icon">
   <input type="hidden" name="Icon_val" id="Icon_val" value="<?php echo $Iconv;?>">
   <?php }
	  if($Iconv != ""){?>
   &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $Iconv;?>" target="_blank">View</a>
   <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove Icon" id="Remove_Icon" value="true" />
   Remove Icon
   <?php } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Horizontal Print:</strong></td>
  <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="HImage" id="HImage">
   <input type="hidden" name="HImage_val" id="HImage_val" value="<?php echo $HImagev;?>">
   <?php }
	  if($HImagev != ""){?>
   &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $HImagev;?>" target="_blank">View</a>
   <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove HImage" id="Remove_HImage" value="true" />
   Remove Print
   <?php } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Horizontal Small:</strong></td>
  <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="HThumb" id="HThumb">
   <input type="hidden" name="HThumb_val" id="HThumb_val" value="<?php echo $HThumbv;?>">
   <?php }
	  if($HThumbv != ""){?>
   &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $HThumbv;?>" target="_blank">View</a>
   <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove HThumb" id="Remove_HThumb" value="true" />
   Remove Thumbnail
   <?php } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Vertical Print:</strong></td>
  <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="VImage" id="VImage" />
   <input type="hidden" name="VImage_val" id="VImage_val" value="<?php echo $VImagev;?>" />
   <?php }
	  if($VImagev != ""){?>
   &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $VImagev;?>" target="_blank">View</a>
   <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove_VImage" id="Remove_VImage" value="true" />
   Remove Print
   <?php } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td><strong>Vertical Small:</strong></td>
  <td colspan="3"><?php if($cont == "add" || $cont == "edit"){ ?>
   <input type="file" name="VThumb" id="VThumb" />
   <input type="hidden" name="VThumb_val" id="VThumb_val" value="<?php echo $VThumbv;?>" />
   <?php }
	  if($VThumbv != ""){?>
   &nbsp;<a href="<?php echo $Prod_Folder; ?>/<?php echo $VThumbv;?>" target="_blank">View</a>
   <?php } 
	  if($cont == "add" || $cont == "edit"){ ?>
   &nbsp;
   <input type="checkbox" name="Remove_VThumb" id="Remove_VThumb" value="true" />
   Remove Thumbnail
   <?php } ?>
   &nbsp;</td>
 </tr>
 <?php } ?>
</table>
