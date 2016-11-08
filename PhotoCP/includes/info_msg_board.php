<?  if(!isset($r_path)){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>

<h1 id="HdrType2" class="<? switch($path[0]){
	case "Evnt": echo "MsgBrdEvnt"; break;
	case "Clnt": echo "MsgBrdClnt"; break;
} ?>">
  <div>Message Board Information</div>
</h1>
<div id="HdrLinks">
  <? if($is_enabled){ ?>
  <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Message'; return true;" onmouseout="window.status=''; return true;" title="Save Message" class="BtnSave2">Save</a><a href="#" onclick="javascript:set_form('form_','<? echo ($cont == "add")?implode(",",array_splice($path,0,3)):implode(",",$path); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Cancel'; return true;" onmouseout="window.status=''; return true;" title="Cancel" class="BtnCancel">Cancel</a>
  <? } else { ?>
  <a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status='Edit Message'; return true;" onmouseout="window.status=''; return true;" title="Edit Message" class="BtnEdit2">Edit</a>
  <? } ?>
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>
      <label for="Email" class="CstmFrmElmntLabel">E-mail</label>
      <? if(!$is_enabled)echo $Email; else { ?>
      <input type="text" name="Email" id="Email" class="CstmFrmElmntInput" title="E-mail" onfocus="javascript:this.className='CstmFrmElmntInputNavSel';" onblur="javascript:this.className='CstmFrmElmntInput';" onmouseover="window.status='E-mail'; return true;" onmouseout="window.status=''; return true;" value="<? echo $Email; ?>">
      <? } ?>
      <br />
      <label for="Event_Notes" class="CstmFrmElmntLabel">Message</label>
      <? if(!$is_enabled) echo urldecode($Message); else { ?>
    <div class="CstmFrmElmntTextField" style="margin-left:15px;">
      <textarea name="Message" id="Message" onfocus="javascript:this.parentNode.className='CstmFrmElmntTextFieldNavSel';" onblur="javascript:this.parentNode.className='CstmFrmElmntTextField';" onmouseover="window.status='Message'; return true;" onmouseout="window.status=''; return true;" title="Message"><? echo urldecode($Message); ?></textarea>
    </div>
    <? } ?>
    </p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
