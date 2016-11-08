<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_project.php';
if($path[3] == "Proj"){
	$is_enabled = ((count($path)<=5 && $cont == "view") || (count($path)>5)) ? false : true;
	$crumb_count = 5;
} else {
	$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
	$crumb_count = 3;
}
$is_back = ($cont == "edit") ? "view" : "query"; 
if(!$is_enabled){ ?>
<div id="Tri_Nav">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)); ?>','view','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Overview</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)).",Time"; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Timesheet</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)).",Asset"; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Assets</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)).",Supp"; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Supplies</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)).",Img"; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Images</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)).",Mov"; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Movies</p></td>
      <td align="center" class="Tabs" onclick="javascript:set_form('form_','<?php echo implode(',',array_slice($path,0,$crumb_count)).",Sound"; ?>','query','<?php echo $sort; ?>','<?php echo $rcrd; ?>');"><p>Sounds</p></td>
    </tr>
  </table>
</div>
<?php } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <?php if($is_enabled){ ?>
	<tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Project Information</p></th>
  </tr>
	<?php } else { ?>
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<?php echo $AevNet_Path; ?>/images/edit.jpg" width="72" height="33" alt="edit" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,$crumb_count)); ?>','edit','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" />&nbsp; <img src="/<?php echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<?php echo implode(",",array_slice($path,0,$crumb_count-1)); ?>','<?php echo $is_back; ?>','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
        <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
        <p>Project Information</p></th>
  </tr>
  <?php } ?>
  <tr>
    <td width="20%"><strong>Project Name: </strong></td>
    <td width="80%"><?php if(!$is_enabled){	echo $PName; } else { ?>
        <input type="text" name="Name" id="Name" maxlength="75" value="<?php echo $PName; ?>" />
        <input type="hidden" name="Prod Id" id="Prod_Id" value="<?php echo $PId; ?>" />
        <?php } ?>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Project Number: </strong></td>
    <td><?php if(!$is_enabled){	echo $PNumber; } else { ?>
        <input type="text" name="Project Number" id="Project_Number" maxlength="50" value="<?php echo $PNumber; ?>" />
        <?php } ?>
    &nbsp;</td>
  </tr>
  <?php 
  if($path[3] == "Proj"){
  	$form_count = 6;
  } else {
	  $form_count = 4;
  }
  if(count($path) < $form_count){ ?>
  <tr>
    <td><strong>Customer: </strong></td>
    <td><?php
	$query_get_customer = "SELECT `cust_id`, `cust_fname`, `cust_lname`, `cust_cname` FROM `cust_customers` ORDER BY `cust_lname`, `cust_fname`, `cust_cname` ASC";
	$get_customer = mysql_query($query_get_customer, $cp_connection) or die(mysql_error());
	$row_get_customer = mysql_fetch_assoc($get_customer);
	$totalRows_get_customer = mysql_num_rows($get_customer);
	
	if(!$is_enabled){
		do {
			if($row_get_customer['cust_id'] == $PCust){
				echo ($row_get_customer['cust_lname'] != "") ? $row_get_customer['cust_lname'].", ".$row_get_customer['cust_fname'] : '';
				echo ($row_get_customer['cust_fname'] != "") ? " - ".$row_get_customer['cust_cname'] : $row_get_customer['cust_cname'];
			}
		} while ($row_get_customer = mysql_fetch_assoc($get_customer));
	} else { ?>
        <select name="Customer" id="Customer" onchange="document.getElementById('controller').value='false'; document.getElementById('Project_Information_Form').submit();">
          <?php do { ?>
          <option value="<?php echo $row_get_customer['cust_id']; ?>"<?php if ($row_get_customer['cust_id'] == $PCust){print(" selected=\"selected\""); } ?>>
          <?php
				echo ($row_get_customer['cust_lname'] != "") ? $row_get_customer['cust_lname'].", ".$row_get_customer['cust_fname'] : '';
				echo ($row_get_customer['cust_fname'] != "") ? " - ".$row_get_customer['cust_cname'] : $row_get_customer['cust_cname'];
				?>
          </option>
          <?php } while ($row_get_customer = mysql_fetch_assoc($get_customer)); ?>
        </select>
        <?php mysql_free_result($get_customer); } ?>
    &nbsp;</td>
  </tr>
  <?php if($PCust != ""){ ?>
  <tr>
    <td><strong>Customer Contact:</strong></td>
    <td><?php
	$query_get_contact = "SELECT `cust_cont_id`, `cust_cont_fname`, `cust_cont_mint`, `cust_cont_lname` FROM `cust_contacts` ORDER BY `cust_cont_lname` ASC";
	$get_contact = mysql_query($query_get_contact, $cp_connection) or die(mysql_error());
	$row_get_contact = mysql_fetch_assoc($get_contact);
	$totalRows_get_contact = mysql_num_rows($get_contact);
	if(!$is_enabled){
		do {
			if($row_get_contact['cust_cont_id'] == $PCont){
				echo $row_get_contact['cust_cont_fname'];
				echo ($row_get_contact['cust_cont_mint'] != "") ? " ".$row_get_contact['cust_cont_mint'].". " : "";
				echo $row_get_contact['cust_cont_lname'];
			}
		} while ($row_get_contact = mysql_fetch_assoc($get_contact));
	} else { ?>
        <select name="Contact" id="Contact">
          <option value="0"<?php if ($PCont == 0){print(" selected=\"selected\""); } ?>> -- None -- </option>
          <?php do { ?>
          <option value="<?php echo $row_get_contact['cust_cont_id']; ?>"<?php if ($row_get_contact['cust_cont_id'] == $PCont){print(" selected=\"selected\""); } ?>>
          <?php 
				echo $row_get_contact['cust_cont_fname'];
				echo ($row_get_contact['cust_cont_mint'] != "") ? " ".$row_get_contact['cust_cont_mint'].". " : "";
				echo $row_get_contact['cust_cont_lname']; ?>
          </option>
          <?php } while ($row_get_contact = mysql_fetch_assoc($get_contact)); ?>
        </select>
        <?php 
	}
	mysql_free_result($get_contact);
	?>
    &nbsp;</td>
  </tr>
  <?php	}
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
	find_parents($PCat,$n,'proj_categories','cat_parent_id','cat_id');
	
	for($n=count($parents)-1;$n>=0;$n--){ 
		$Temp_id = $parents[$n][0];
		$Cur_id = $parents[$n][1];

		$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `proj_categories` WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
		$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
		$row_get_categories = mysql_fetch_assoc($get_categories);
		$totalRows_get_categories = mysql_num_rows($get_categories);
	?>
  <tr>
    <td><strong>Category:</strong></td>
    <td><?php if(!$is_enabled){
		do {
			if($row_get_categories['cat_id'] == $Cur_id) echo $row_get_categories['cat_name'];
		} while ($row_get_categories = mysql_fetch_assoc($get_categories));
	} else { ?>
        <select name="Category[]" id="Category[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Project_Information_Form').submit();">
          <option value="0"<?php if($Cur_id == 0){print(" selected=\"selected\"");} ?>>-- Select Category --</option>
          <?php do { ?>
          <option value="<?php echo $row_get_categories['cat_id']; ?>"<?php if($row_get_categories['cat_id'] == $Cur_id){print(" selected=\"selected\"");} ?>><?php echo $row_get_categories['cat_name']; ?></option>
          <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
        </select>
        <input type="hidden" name="Sel_Cat[]" id="Sel_Cat[]" value="<?php echo $Cur_id; ?>" />
        <?php } ?>
    &nbsp;</td>
  </tr>
  <?php 
  	mysql_free_result($get_categories);
  } 
  if($is_enabled){
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `proj_categories` WHERE `cat_parent_id` = '$PCat'";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $Cur_id != 0){ ?>
  <tr>
    <td><strong>Category:</strong></td>
    <td><select name="Category[]" id="Category[]" onchange="document.getElementById('controller').value='false'; document.getElementById('Product_Information_Form').submit();">
      <option value="0">-- Select Category --</option>
      <?php do { ?>
      <option value="<?php echo $row_get_categories['cat_id']; ?>"><?php echo $row_get_categories['cat_name']; ?></option>
      <?php } while ($row_get_categories = mysql_fetch_assoc($get_categories)); ?>
    </select>
        <input type="hidden" name="Sel_Cat[]" id="Sel_Cat[]" value="-1" />
    &nbsp;</td>
  </tr>
  <?php } mysql_free_result($get_categories); } ?>
  <tr>
    <td><strong>Website:</strong></td>
    <td><?php if(!$is_enabled){	echo $PWeb; } else { ?>
        <input type="text" name="Website" id="Website" maxlength="50" value="<?php echo $PWeb; ?>" />
        <?php } ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Bill Rate: </strong></td>
    <td><?php if(!$is_enabled){	echo "$".number_format($PRate,2,".",","); } else { ?>
        <input type="text" name="Bill Rate" id="Bill_Rate" maxlength="50" value="<?php echo $PRate; ?>" />
        <?php } ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Price:</strong></td>
    <td><?php if(!$is_enabled){	echo "$".number_format($PPrice,2,".",","); } else { ?>
        <input type="text" name="Price" id="Price" maxlength="50" value="<?php echo $PPrice; ?>" />
        <?php } ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Project Start </strong></td>
    <td><?php if(!$is_enabled){ if($PStart != "" && $PStart != "0000-00-00 00:00:00" && $PStart !=  "00000000000000"){
			echo format_date($PStart,"DayShort","Standard",true,true);
		} else { echo "&nbsp;"; } } else { ?>
        <input type="text" name="Project Start" id="Project_Start" maxlength="75" value="<?php echo $PStart; ?>" />
        <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Project_Start','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
          Date</a> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Project End </strong></td>
    <td><?php if(!$is_enabled){	if($PEnd != "" && $PEnd != "0000-00-00 00:00:00" && $PEnd !=  "00000000000000"){
			echo format_date($PEnd,"DayShort","Standard",true,true);
		} else { echo "&nbsp;"; } } else { ?>
        <input type="text" name="Project End" id="Project_End" maxlength="75" value="<?php echo $PEnd; ?>" />
        <a href="#" onclick="newwindow=window.open('scripts/calendar.php?future=true&time=true&field=Project_End','','scrollbars=no,menubar=no,height=270,width=184,resizable=no,toolbar=no,location=no,status=no'); newwindow.opener=self;"> Select
          Date</a> (yyyy-mm-dd hh:mm:ss)
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Description:</strong></td>
    <td><input type="hidden" name="Proj_Id" id="Proj_Id" value="<?php echo $PId;?>" />
    &nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="padding:10px;<?php if($is_enabled){ ?> height:300px;<?php } ?>" valign="top">
	<?php if(!$is_enabled){ echo $PDesc; } else {
		$oFCKeditor = new FCKeditor('Project_Description');
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
    <td><strong>Image:</strong></td>
    <td><?php if($is_enabled){ ?>
        <input type="file" name="Image" id="Image" />
        <input type="hidden" name="Image_val" id="Image_val" value="<?php echo $Imagev;?>" />
        <?php } if($Imagev != ""){?>
      &nbsp;<a href="<?php echo $Proj_Folder; ?>/<?php echo $Imagev;?>" target="_blank">View</a>
      <?php } if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Image" id="Remove_Image" value="true" />
      Remove Image
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Thumb:</strong></td>
    <td><?php if($is_enabled){ ?>
        <input type="file" name="Thumb" id="Thumb" />
        <input type="hidden" name="Thumb_val" id="Thumb_val" value="<?php echo $Thumbv;?>" />
        <?php } if($Thumbv != ""){?>
      &nbsp;<a href="<?php echo $Proj_Folder; ?>/<?php echo $Thumbv;?>" target="_blank">View</a>
      <?php } if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove Thumb" id="Remove_Thumb" value="true" />
      Remove Thumbnail
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Portfolio:</strong></td>
    <td><?php if(!$is_enabled){ echo ($PPort=="y")?"Yes":"No"; } else { ?>
        <input type="radio" name="Portfolio" id="Portfolio" value="y"<?php if($PPort=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Portfolio" id="Portfolio" value="n"<?php if($PPort=="n"){print(" checked=\"checked\"");} ?> />
      No
      <?php } ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Completed:</strong></td>
    <td><?php if(!$is_enabled){ echo ($PComp=="y")?"Yes":"No"; } else { ?>
        <input type="radio" name="Complete" id="Complete" value="y"<?php if($PComp=="y"){print(" checked=\"checked\"");} ?> />
      Yes
      <input type="radio" name="Complete" id="Complete" value="n"<?php if($PComp=="n"){print(" checked=\"checked\"");} ?> />
      No
      <?php } ?>
      &nbsp;</td>
  </tr>
  <?php } ?>
</table>
