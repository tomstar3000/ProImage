<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";
$HotMenu = "Web,Home:query"; $Key = array_search($HotMenu, $StrArray); ?>

<h1 id="HdrType2" class="WebHome">
  <div>Home Page Information</div>
</h1>
<div id="HdrLinks"> <a href="#" onclick="javascript:<? echo $onclick; ?> return false;" onmouseover="window.status='Save Home Page Information'; return true;" onmouseout="window.status=''; return true;" title="Save Home Page Information" class="BtnSave2">Save</a><a href="javascript:HotMenu('<? echo $HotMenu; ?>',<? echo ($Key!==false)?'2':'1'; ?>);" title="Add to Hot Menu" onmouseover="window.status='Add to Hot Menu'; return true;" onmouseout="window.status=''; return true;" class="BtnHotMenu<? echo ($Key!==false)?'Added':''; ?>" id="BtnHotMenu">Add to Hot Menu</a></div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>Home Page Image:
      <input type="file" name="Image" id="Image">
      <input type="hidden" name="Image_val" id="Image_val" value="<? echo $Imagev;?>">
      <? if($Imagev != ""){?>
      &nbsp;<a href="<? echo "/photographers/".$CHandle; ?>/<? echo $Imagev;?>" target="_blank">View</a>
      <? } ?>
      998 x 387 Pixel Dimensions</p>
  </div>
</div>
<div id="TinyMCE">
  <div class="TinyMCE">
    <!-- TinyMCE -->
    <script type="text/javascript" src="/PhotoCP/TinyMCE/3.5.8/tiny_mce.js"></script>
    <script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		elements : "Page_Text",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "black",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,|,cut,copy,paste,pastetext,pasteword,|,search,replace",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true
	});
</script>
    <!-- /TinyMCE -->
    <textarea id="Page_Text" name="Page_Text" style="width:700px; height:500px;"><? echo $Desc; ?></textarea>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
