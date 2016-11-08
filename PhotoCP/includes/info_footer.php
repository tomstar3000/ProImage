<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
$HotMenu = "Web,Ftr:query"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="WebFoot">
  <div>Footer Page Information</div>
</h1>
<div id="HdrLinks"> <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Footer Information'; return true;" onmouseout="window.status=''; return true;" title="Save Footer Information" class="BtnSave2">Save</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>
      <label for="Image" class="CstmFrmElmntLabel">Footer Image</label>
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
      <? if($Imagev != ""){?>
      &nbsp;<a href="<? echo "/photographers/".$CHandle; ?>/<? echo $Imagev;?>" target="_blank">View</a>
      <? } ?>
      50 x 725 Pixel Dimensions<br />
      <label for="Company_Name" class="CstmFrmElmntLabel">Company Name</label>
      <input type="text" name="Company Name" id="Company_Name" class="CstmFrmElmntInput" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Company Name'; return true;" onmouseout="window.status=''; return true;" title="Company Name" value="<? echo $Company; ?>" />
      <br />
      <label for="Email" class="CstmFrmElmntLabel">E-mail</label>
      <input type="text" name="Email" id="Email" class="CstmFrmElmntInput" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='Email'; return true;" onmouseout="window.status=''; return true;" title="Email" value="<? echo $Email; ?>" />
      <br />
      <label for="W1" class="CstmFrmElmntLabel">Phone Number</label>
      <input name="W1" type="text" id="W1" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Area Code'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Area Code" value="<? echo $W1;?>" maxlength="3" onkeyup="AEV_set_tel_number('Work_Number','W');" />
      <input name="W2" type="text" id="W2" size="6" class="CstmFrmElmntInputi29" onfocus="javascript:this.className='CstmFrmElmntInputi29NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi29';" onmouseover="window.status='Phone Number: Prefix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Prefix" value="<? echo $W2;?>" maxlength="3" onkeyup="AEV_set_tel_number('Work_Number','W');" />
      <input name="W3" type="text" id="W3" size="8" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Phone Number: Suffix'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Suffix" value="<? echo $W3;?>" maxlength="4" onkeyup="AEV_set_tel_number('Work_Number','W');" />
      <strong class="CstmFrmElmntStrong">Ext.</strong>
      <input type="text" name="Extension" id="Extension" class="CstmFrmElmntInputi34" onfocus="javascript:this.className='CstmFrmElmntInputi34NavSel';" onblur="javascript:this.className='CstmFrmElmntInputi34';" onmouseover="window.status='Phone Number: Extension'; return true;" onmouseout="window.status=''; return true;" title="Phone Number: Extension" value="<? echo $Ext; ?>" size="6" maxlength="10" />
      <input type="hidden" name="Work Number" id="Work_Number" value="<? echo $Work;?>" />
      <br clear="all" />
    </p>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
