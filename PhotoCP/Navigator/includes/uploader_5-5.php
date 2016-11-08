<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../";}
$MainPath = $r_path;
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
if(isset($_GET['data'])){
	$data = unserialize(base64_decode(urldecode($_GET['data'])));
	foreach($data as $k => $v) $$k = $v;
}
$fid = trim(clean_variable($_GET['fid'],true)); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Navigator</title>
<link href="<? echo $NavFolder; ?>/css/stylesheet.css" rel="stylesheet" type="text/css" media="screen" />
<script src="/Aurigma/v_5.5/iuembed.js" type="text/javascript"></script>
<script src="/Aurigma/v_5.5/script.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[	
function formatFileSize(value){
	if (value < 1024){ return value + " b";
	} else if (value < 1048576){ return Math.round(value / 1024) + " Kb";
	} else if (value < 1073741824){ return Math.round(value / 10485.76) / 100 + " Mb";
	} else { return Math.round(value / 10737418.24) / 100 + " Gb";
	} }
function send_Msg(VAL,DIS){
	switch(VAL){
		case "Active": var URL = '<? echo $NavFolder; ?>/includes/clear_activex.html'; break;
		case "Cache": var URL = '<? echo $NavFolder; ?>/includes/clear_cache.html'; break;
		case "CacheMac": var URL = '<? echo $NavFolder; ?>/includes/clear_cacheMac.html'; break;
	}	
	try { Gateway=new ActiveXObject("Microsoft.XMLHTTP"); Gateway.async="false"; } // Internet Explorer
	catch (e) { try{ Gateway = new XMLHttpRequest(); } // Firefox, Opera 8.0+, Safari
							catch(e){ Gateway=new ActiveXObject('MSXML2.XMLHTTP.3.0');} } // Internet Explorer 5.5 and 6
	if (Gateway != null) {
		Gateway.onreadystatechange = function() {sending_Msg(DIS);};
		Gateway.open("GET", URL, true); Gateway.send("");
	} }
function sending_Msg(DIS){ var TArry = new Array('close_Msg');
	if (Gateway.readyState == 4) { parent.send_Msg(Gateway.responseText,DIS,'Navigator',TArry);
		document.getElementById('LoaderWindow').style.display = "none"; } }
function close_Msg(){ document.getElementById('LoaderWindow').style.display = "block"; }
//]]>
</script>
</head>
<body>
<div id="WarningWindow">
  <div id="WarningBackground"></div>
  <div id="WarningMessage">
    <div id="WarningText">
      <h4>Notice!</h4>
      <p>You are currently uploading images to the server.<br />
        Would you like to:</p>
      <br clear="all" />
      <p align="center">
        <input type="button" name="Cancel Upload" id="Cancel_Upload" value="Cancel Upload" />
        <input type="button" name="Continue Upload" id="Continue_Upload" value="Continue Upload" onClick="continueWarningWindow();" />
        <input type="button" name="Launch New Window" id="Launch_New_Window" value="Launch New Window" />
      </p>
    </div>
  </div>
</div>
<div id="RecordTable" class="White">
  <div id="Top"></div>
  <div id="Records">
    <p>
      <? if(intval($fid) > 0){ 
				$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
				$getInfo->mysql("SELECT `group_name` FROM `photo_event_group` WHERE `group_id` = '$fid';");
				$getInfo = $getInfo->Rows();
				echo 'You are uploading to group "'.$getInfo[0]['group_name'].'"';
			} else { echo 'You are uploading to the Main Group';	}
			echo '<br clear="all" />';
			if(eregi('MSIE',strtoupper($_SERVER['HTTP_USER_AGENT']))){ ?>
      Notice that we are using Aurigma 5.5.6.0<br />
      If you are having problems <a href="javascript:send_Msg('Active',true);" title="Learn how to clean out your Active X controllers">Click
      Here to learn how to</a> clean your Active X Controllers.
      <? } else {	?>
      Notice that we are using Aurigma 2.5.35.0<br />
      If you are having problems <a href="javascript:send_Msg('<?
			if (eregi('WIN',strtoupper($_SERVER['HTTP_USER_AGENT']))) echo 'Cache';
			else if (eregi('MAC',strtoupper($_SERVER['HTTP_USER_AGENT']))) echo 'CacheMac';
			else echo 'Cache' ?>',true);" title="Learn how to clean out your broswers cache">Click
      Here to learn how to</a> clean your browser cache.
      <? } ?>
    </p>
    <p><strong><span id="spanUploadFileCount">0</span></strong>&nbsp;images selected</p>
    <p style="display:none"><span id="spanMaxFileCount">x</span></p>
    <img id="imgUploadFileCount" src="/images/spacer.gif" style="display:none" />
    <p style="display:none"><span id="spanMaxTotalFileSize">x</span></p>
    <p style="display:none"><span id="spanTotalFileSize">0</span></p>
    <img id="imgTotalFileSize" src="/images/spacer.gif" style="display:none;" /></div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
<h1 id="HdrType3" class="EvntInfo">
  <div>Load Images </div>
</h1>
<div id="HdrLinks600">
  <select id="selectView" name="selectView" class="BtnLoadView" onChange="selectView_change();">
    <option value="0"<? if($Group === true) echo ' selected="selected"'; ?> title="Thumbnails">Thumbnails</option>
    <option value="1" title="Icons">Icons</option>
    <option value="2"<? if($Group === false) echo ' selected="selected"'; ?> title="List">List</option>
    <option value="3" title="Details">Details</option>
  </select>
  <a href="#" onClick="imageUploader1.Refresh();return false;" class="BtnLoadRefresh">Refresh</a><a href="#" onClick="imageUploader1.Send();return false;" class="BtnLoadUpload" title="Upload images to the server">Upload</a><a href="#" onClick="imageUploader1.DeselectAll();return false;" class="BtnLoadDeselAll" title="Deselect all images">Deselect All</a><a href="#" onClick="imageUploader1.SelectAll();return false;" class="BtnLoadSelAll" title="Select all images">Select All</a><a href="<? echo $NavFolder; ?>/includes/index.php?data=<? echo $_GET['data']; ?>" class="BtnLoadBack" title="Back to organizer">Back to organizer</a></div>

<div id="LoaderWindow">
<script type="text/javascript">
//Create JavaScript object that will embed Image Uploader to the page.
var iu = new ImageUploaderWriter("ImageUploader1", <? echo $Width; ?>, <? echo $Height-200; ?>);

//For ActiveX control full path to CAB file (including file name) should be specified.
iu.activeXControlCodeBase = "/Aurigma/v_5.5/ImageUploader5.cab";
iu.activeXControlVersion = "5,5,6,0";
/*
iu.javaAppletJarFileName = "ImageUploader5.jar";
iu.javaAppletCodeBase = "/Aurigma/v_5.5/";
iu.javaAppletCached = true;
iu.javaAppletVersion = "5.5.6.0";
*/

//For Java applet only path to directory with JAR files should be specified (without file name).
iu.javaAppletJarFileName = "ImageUploader2.jar";
iu.javaAppletCodeBase = "/Aurigma/v_4.5/";
iu.javaAppletCached = true;
iu.javaAppletVersion = "2.5.35.0";


iu.showNonemptyResponse = "off";
iu.addParam("RememberLastVisitedFolder", "true");
iu.addParam("UploadThumbnail1CopyExif", "true");
iu.addParam("BackgroundColor", "#E8E8E8");

<? if(intval($fid) == 0){ //Lets load Groups of images ?>
//Configure appearance and behaviour.
iu.addParam("PaneLayout", "TwoPanes");
iu.addParam("FolderView", "List");

iu.addParam("AllowFolderUpload", "true");
<? } else { // Lets load to a single folder ?>
iu.addParam("PaneLayout", "ThreePanes");
iu.addParam("FolderView", "Thumbnails");
iu.addParam("ShowButtons", "true");
iu.addParam("ButtonSendText", "Upload");

iu.addParam("AllowFolderUpload", "false");
<? } ?>
iu.addParam("TreePaneWidth", "205");
iu.addParam("ThumbnailHorizontalSpacing", "5");
iu.addParam("ThumbnailVerticalSpacing", "1");
iu.addParam("ShowUploadListButtons", "true");
iu.addParam("ShowButtons", "false");
<? if(intval($fid) > 0){ ?>
iu.addParam("MaxFileSize", "20971520");
iu.addParam("FileIsTooLargeText", "Larger than 20 Mb");
<? } ?>
iu.addParam("ShowSubfolders", "true");
iu.addParam("AllowMultipleRotate", "true");
iu.addParam("Action", "<? echo str_replace("//","/",$MainPath.$NavFolder); ?>/xml/save_image_5-5.php?fid=<? echo $fid; ?>&event=<? echo base64_encode($EvntID); ?>&time=<? $time = time(); echo $time; ?>");
iu.addParam("RedirectUrl","<? echo $NavFolder; ?>/includes/index.php?data=<? echo $_GET['data']; ?>&fid=<? echo $fid; ?>&event=<? echo base64_encode($EvntID); ?>&time=<? echo $time; ?>");
<? if($MBLeft <= 750){
	$MaxTotalFileSize = $MBLeft*1024;
	echo 'iu.addParam("MaxTotalFileSize", "'.$MaxTotalFileSize.'");';
	echo 'iu.addParam("MessageMaxTotalFileSizeExceededText", "You are reaching your space limitation please upgrade your account.");';
} ?>
//iu.addParam("ButtonRemoveFromUploadListText", "");
iu.addParam("ShowDescriptions", "false");
iu.addParam("ShowContextMenu", "true");
//Configure colors.
iu.addParam("BackgroundColor", "#ffffff");
iu.addParam("SplitterLineColor", "#0066cc");
//Configure border and splitter line style.
iu.addParam("FolderPaneHeight", "250");
iu.addParam("PreviewThumbnailSize", "75");
//Configure Progress Bar
iu.addParam("HoursText", "Hour");
iu.addParam("MinutesText", "Min");
iu.addParam("SecondsText", "Sec");
//Configure thumbnail settings.
/*
Removed with new resizing system.
iu.addParam("UploadThumbnail1FitMode", "Fit");
iu.addParam("UploadThumbnail1Width", "195");
iu.addParam("UploadThumbnail1Height", "195");
iu.addParam("UploadThumbnail1JpegQuality", "90");
iu.addParam("UploadThumbnail1CopyExif", "true");
iu.addParam("UploadThumbnail1ResizeQuality", "high"); 
//Configure thumbnail settings.
iu.addParam("UploadThumbnail2FitMode", "Fit");
iu.addParam("UploadThumbnail2Width", "630");
iu.addParam("UploadThumbnail2Height", "630");
iu.addParam("UploadThumbnail2JpegQuality", "90");
iu.addParam("UploadThumbnail2CopyExif", "true");
iu.addParam("UploadThumbnail2ResizeQuality", "high");

*/ 
//Configure thumbnail settings.
iu.addParam("UploadThumbnail3FitMode", "Fit");
iu.addParam("UploadThumbnail3Width", "960");
iu.addParam("UploadThumbnail3Height", "640");
iu.addParam("UploadThumbnail3JpegQuality", "90");
iu.addParam("UploadThumbnail3CopyExif", "true");
iu.addParam("UploadThumbnail3ResizeQuality", "high"); 
//Configure upload settings.
iu.addParam("FilesPerOnePackageCount", "1");
iu.addParam("AutoRecoverMaxTriesCount", "5");
iu.addParam("AutoRecoverTimeOut", "5000");
//iu.addParam("LicenseKey", "6375-7526-6373-9693;9264-9272-8784-8480"); // 4.5 keys
//iu.addParam("LicenseKey", "71050-10000-F5A08-3F4D5-54C20;72050-10000-933B2-32A02-A0396"); // 5.0 keys
iu.addParam("LicenseKey", "71050-10000-F5A08-3F4D5-54C20;9264-9272-8784-8480"); // 4.5 & 5.0 keys
//iu.addParam("LicenseKey", "71050-4830B-00000-00538-580A0;72050-4830B-00000-008C3-24FA5"); // Trial
iu.addParam("FileMask", "*.jpg;*.jpeg;*.jpe;*.bmp;*.gif;*.png");
iu.addParam("UploadSourceFile", "true");
iu.addParam("ShowDebugWindow", "true");
//Add event handlers.
//iu.addEventListener("BeforeUpload", "ImageUploader_BeforeUpload");
iu.addEventListener("UploadFileCountChange", "ImageUploader_UploadFileCountChange");
iu.addEventListener("ViewChange", "ImageUploader_ViewChange");
iu.fullPageLoadListenerName = "fullPageLoad";

//Tell Image Uploader writer object to generate all necessary HTML code to embed 
//Image Uploader to the page.
iu.writeHtml();
</script>
</div>
</body>
</html>
