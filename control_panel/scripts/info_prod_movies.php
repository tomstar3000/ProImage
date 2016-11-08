<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php'; 
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <th colspan="2" id="Form_Header"><div id="Add"> <img src="/<? echo $AevNet_Path; ?>/images/back.jpg" width="72" height="33" alt="back" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,4)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div><img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
      <p>Image Information</p></th>
  </tr>
  <tr>
    <td width="20%"><strong>Name:</strong></td>
    <td width="80%"><? if(!$is_enabled){
		echo $IName_1;
	} else { ?>
      <input type="text" name="Name_1" id="Name_1" maxlength="75" value="<? echo $IName_1; ?>">
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>Tag:</strong></td>
    <td><? if(!$is_enabled){
		echo $IAlt_1;
	} else { ?>
      <input type="text" name="Alt_1" id="Alt_1" maxlength="75" value="<? echo $IAlt_1; ?>" />
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>File:</strong></td>
    <td><? if($is_enabled){ ?>
      <input type="file" name="File_1" id="File_1">
      <input type="hidden" name="File_val_1" id="File_val_1" value="<? echo $Filev_1;?>">
      <? 
	  }
	  if($File_1 != ""){?>
      &nbsp;<a href="<? echo $Prod_Folder; ?>/<? echo $File_1;?>" target="_blank">View</a>
      <? } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove File_1" id="Remove_File_1" value="true" />
      Remove File
      <? } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><? if(!$is_enabled){
		echo $IName_2;
	} else { ?>
      <input type="text" name="Name_2" id="Name_2" maxlength="75" value="<? echo $IName_2; ?>">
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>Tag:</strong></td>
    <td><? if(!$is_enabled){
		echo $IAlt_2;
	} else { ?>
      <input type="text" name="Alt_2" id="Alt_2" maxlength="75" value="<? echo $IAlt_2; ?>" />
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>File:</strong></td>
    <td><? if($is_enabled){ ?>
      <input type="file" name="File_2" id="File_2">
      <input type="hidden" name="File_val_2" id="File_val_2" value="<? echo $Filev_2;?>">
      <? 
	  }
	  if($File_2 != ""){?>
      &nbsp;<a href="<? echo $Prod_Folder; ?>/<? echo $File_2;?>" target="_blank">View</a>
      <? } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove File_2" id="Remove_File_2" value="true" />
      Remove File
      <? } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><? if(!$is_enabled){
		echo $IName_3;
	} else { ?>
      <input type="text" name="Name_3" id="Name_3" maxlength="75" value="<? echo $IName_3; ?>">
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>Tag:</strong></td>
    <td><? if(!$is_enabled){
		echo $IAlt_3;
	} else { ?>
      <input type="text" name="Alt_3" id="Alt_3" maxlength="75" value="<? echo $IAlt_3; ?>" />
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>File:</strong></td>
    <td><? if($is_enabled){ ?>
      <input type="file" name="File_3" id="File_3">
      <input type="hidden" name="File_val_3" id="File_val_3" value="<? echo $Filev_1;?>">
      <? 
	  }
	  if($File_3 != ""){?>
      &nbsp;<a href="<? echo $Prod_Folder; ?>/<? echo $File_3;?>" target="_blank">View</a>
      <? } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove File_3" id="Remove_File_3" value="true" />
      Remove File
      <? } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><? if(!$is_enabled){
		echo $IName_4;
	} else { ?>
      <input type="text" name="Name_4" id="Name_4" maxlength="75" value="<? echo $IName_4; ?>">
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>Tag:</strong></td>
    <td><? if(!$is_enabled){
		echo $IAlt_4;
	} else { ?>
      <input type="text" name="Alt_4" id="Alt_4" maxlength="75" value="<? echo $IAlt_4; ?>" />
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>File:</strong></td>
    <td><? if($is_enabled){ ?>
      <input type="file" name="File_4" id="File_4">
      <input type="hidden" name="File_val_4" id="File_val_4" value="<? echo $Filev_4;?>">
      <? 
	  }
	  if($File_4 != ""){?>
      &nbsp;<a href="<? echo $Prod_Folder; ?>/<? echo $File_4;?>" target="_blank">View</a>
      <? } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove File_4" id="Remove_File_4" value="true" />
      Remove File
      <? } ?></td>
  </tr>
  <tr>
    <td colspan="2"><hr size="1" /></td>
  </tr>
  <tr>
    <td><strong>Name:</strong></td>
    <td><? if(!$is_enabled){
		echo $IName_5;
	} else { ?>
      <input type="text" name="Name_5" id="Name_5" maxlength="75" value="<? echo $IName_5; ?>">
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>Tag:</strong></td>
    <td><? if(!$is_enabled){
		echo $IAlt_5;
	} else { ?>
      <input type="text" name="Alt_5" id="Alt_5" maxlength="75" value="<? echo $IAlt_5; ?>" />
      <? } ?></td>
  </tr>
  <tr>
    <td><strong>File:</strong></td>
    <td><? if($is_enabled){ ?>
      <input type="file" name="File_5" id="File_5">
      <input type="hidden" name="File_val_5" id="File_val_5" value="<? echo $Filev_5;?>">
      <? 
	  }
	  if($File_5 != ""){?>
      &nbsp;<a href="<? echo $Prod_Folder; ?>/<? echo $File_5;?>" target="_blank">View</a>
      <? } 
	  if($is_enabled){ ?>
      &nbsp;
      <input type="checkbox" name="Remove File_5" id="Remove_File_5" value="true" />
      Remove File
      <? } ?></td>
  </tr>
</table>
