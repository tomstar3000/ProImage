<? if(isset($r_path)===false){ $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../"; }
include $r_path.'security.php';
$EId = $path[2]; ?>
<?  if(isset($Error) && strlen(trim($Error)) > 0){ ?>

<h1 id="HdrType2" class="EvntInfo2">
  <div>Notice</div>
</h1>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p class="Error"><? echo $Error; ?></p>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<? } ?>
<h1 id="HdrType2" class="<? switch($path[0]){
	case "Evnt": echo "GstBkEvnt"; break;
	case "Clnt": echo "GstBkClnt"; break;
} ?>">
  <div>Guestbook</div>
</h1>
<div id="HdrLinks"><a href="javascript:document.getElementById('Controller').value='Send'; document.getElementById('form_action_form').submit();" onmouseover="window.status='Send E-mail to current guestbook'; return true;" onmouseout="window.status=''; return true;" title="Send E-mail to current guestbook" class="BtnSendEmail">Send E-mail</a></div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>Image:
      <input type="file" name="Image" id="Image" />
      Image will display 150 pixels wide in the top left corner</p>
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
		elements : "Email_Text",
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
    <textarea id="Email_Text" name="Email_Text" style="width:700px; height:500px;"></textarea>
    <br clear="all" />
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
