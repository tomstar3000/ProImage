<?
if(isset($r_path)===false){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
if($cont != "add" && $cont != "edit"){
	$is_enabled = false;
} else {
	$is_enabled = true;
}
if($cont == "add" || $cont == "edit" && count($path) >= 5){
	$pathcount = count($path);
} else if(count($path) > 5){
	$pathcount = count($path)-1;
} else {
	$pathcount = 3;
}
if($is_enabled){  ?>

<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Group Information </h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,$pathcount)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<? } else { ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
 <h2>Group Information</h2>
 <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,$pathcount)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
 <? if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),"msie")!==false && count($path)<8){ ?>
 <p id="Mass_Upload"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','massupload','<? echo $sort; ?>','<? echo $rcrd; ?>');">Mass
   Upload</a></p>
 <? } ?>
 <p id="Edit"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",$path); ?>','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');">Edit</a></p>
</div>
<? } ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 <tr>
  <td>Group Name:</td>
  <td><? if(!$is_enabled){
		echo $GName;
	} else { ?>
   <input type="text" name="Group Name" id="Group_Name" value="<? echo $GName; ?>">
   &nbsp;
   <input type="button" name="btnSave" id="btnSave" value="Save Group without Loading Images" alt="Save Information" onmouseup="document.getElementById('Controller').value = 'Save'; this.disabled=true; this.form.submit();" />
   <? } ?>
   &nbsp;</td>
 </tr>
 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>
 <? if($cont == "add"){ ?>
 <tr>
  <td colspan="2"><script src="/Aurigma/v_5.1/iuembed.js" type="text/javascript"></script>
   <script src="/Aurigma/v_5.1/script.js" type="text/javascript"></script>
   <script type="text/javascript">
//<![CDATA[	
function formatFileSize(value){
	if (value < 1024){
		return value + " b";
	} else if (value < 1048576){
		return Math.round(value / 1024) + " Kb";
	} else if (value < 1073741824){
		return Math.round(value / 10485.76) / 100 + " Mb";
	} else {
		return Math.round(value / 10737418.24) / 100 + " Gb";
	}
}
//]]>
</script>
   <script type="text/javascript">
function setWarningAction(){
	var n=0;
	while(document.getElementsByTagName('a')[n]){
		//document.getElementsByTagName('a')[n].href = templink;
		var element = document.getElementsByTagName('a')[n];
		var tempClick = "";             
		for(var x=0; x<element.attributes.length; x++) {
			if(element.attributes[x].nodeName.toLowerCase() == 'onclick') {
				tempClick = element.attributes[x].nodeValue;
				break;
			}
		}
		if(tempClick != null){
			tempClick = tempClick.replace("javascript:","");
			element.onclick = new Function("javascript: return false; "+tempClick);
		} else {
			element.onclick = new Function("javascript: return false;");
		}
		element.onmouseup = new Function("javascript: openWarningWindow(this)");
		n++;
	}
}
function openWarningWindow(element){
	document.getElementById('WarningWindow').style.display='block';
	document.getElementById('LoaderWindow').style.marginLeft='-20000px';
	var tempClick = "";
	if(element.href.toString() == window.location.href.toString() || element.href.toString() == (window.location.href.toString()+"#")){
		for( var x=0; x<element.attributes.length; x++) {
			if(element.attributes[x].nodeName.toLowerCase() == 'onclick') {
				tempClick = element.attributes[x].nodeValue;
				if(tempClick == null)	{ 
					tempClick = element.onclick.toString(); 
					tempClick = tempClick.replace("function","");
					tempClick = tempClick.replace("{","");
					tempClick = tempClick.replace("}","");
					tempClick = tempClick.replace("anonymous()",""); }
				break;
			}
		}
		tempClick = tempClick.replace("javascript:","");
		tempClick = tempClick.replace("return false;","");
	} else {
		tempClick = "document.location.href='"+element.href+"';";
	}
	tempClick2 = "window.open('"+window.location.href+"','"+window.title+"');"; 
	//document.getElementById('Cancel_Upload').onclick = new Function("javascript: imageUploader1.Stop();");
	document.getElementById('Cancel_Upload').onclick = new Function("javascript: "+tempClick);
	document.getElementById('Launch_New_Window').onclick = new Function("javascript: "+tempClick2+" continueWarningWindow();");
}
function continueWarningWindow(){
	document.getElementById('WarningWindow').style.display='none';
	document.getElementById('LoaderWindow').style.marginLeft='0px';
}
</script>
   <style type="text/css">
#WarningWindow {
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	z-index:1;
	overflow: hidden;
	display:none;
}
#WarningWindow #WarningBackground {
	background-color: #000000;
	filter:alpha(opacity=50);
	-moz-opacity:0.5;
	opacity: 0.5;
	width:100%;
	height:100%;
	margin:0px;
	padding:0px;
	z-index: 0;
}
#WarningWindow #WarningMessage {
	position: absolute;
	left: 0px;
	top: 0px;
	z-index: 1;
	height: 100%;
	width: 100%;
}
#WarningWindow #WarningMessage #WarningText {
	width:450px;
	height:125px;
	margin-left:auto;
	margin-right:auto;
	overflow: hidden;
	margin-top: 200px;
	background-color: #EEEEEE;
	border: 1px solid #666666;
}
#WarningWindow #WarningMessage #WarningText h4 {
	background-color: #990000;
	margin: 0px;
	font-size: 15px;
	color: #FFFFFF;
	padding: 2px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
#WarningWindow #WarningMessage #WarningText p {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 0px;
	padding-left: 5px;
	margin: 0px;
	font-weight: bold;
}
</style>
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
       <input type="button" name="Continue Upload" id="Continue_Upload" value="Continue Upload" onclick="continueWarningWindow();" />
       <input type="button" name="Launch New Window" id="Launch_New_Window" value="Launch New Window" />
      </p>
     </div>
    </div>
   </div>
   <div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
    <p id="Select_All"><a href="#" onclick="imageUploader1.SelectAll();return false;">Select&nbsp;All</a></p>
    <p id="Deselect_All"><a href="#" onclick="imageUploader1.DeselectAll();return false;">Deselect&nbsp;All</a></p>
    <p id="Upload"><a href="#" onclick="MM_validateForm('Group_Name','','R'); if(document.MM_returnValue == true){setWarningAction();imageUploader1.Send();return false;}">Upload</a></p>
    <p id="Refresh"><a href="#" onclick="imageUploader1.Refresh();return false;">Refresh</a></p>
    <p id="View">
     <select id="selectView" onchange="selectView_change();">
      <option value="0" selected="selected">Thumbnails</option>
      <option value="1">Icons</option>
      <option value="2">List</option>
      <option value="3">Details</option>
     </select>
    </p>
   </div>
   <div>
    <div id="Info">
     <p><strong><span id="spanUploadFileCount">0</span></strong>&nbsp;images selected</p>
     <p>
      <?	if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']),"msie") !== false){ ?>
      Notice that we are using Aurigma 5.5.6.0<br />
      If you are having problems <a href="#" onclick="javascript:window.open('/control_panel/browser_activex.html','ProImageSoftware','width=600,height=300,scrollbars=no,menubar=no,resizable=yes,toolbar=no,location=no,status=no');">Click
      Here to learn how to</a> clean your Active X Controllers.
      <? } else { ?>
      Notice that we are using Aurigma 2.5.35.0<br />
      If you are having problems <a href="#" onclick="javascript:window.open('/control_panel/browser_cache.html','ProImageSoftware','width=600,height=300,scrollbars=yes,menubar=no,resizable=yes,toolbar=no,location=no,status=no');">Click
      Here to learn how to</a> clean your browser cache.
      <? } ?>
     </p>
     <p style="display:none"><span id="spanMaxFileCount">x</span></p>
     <img id="imgUploadFileCount" src="/images/spacer.gif" style="display:none" />
     <p style="display:none"><span id="spanMaxTotalFileSize">x</span></p>
     <p style="display:none"><span id="spanTotalFileSize">0</span></p>
     <img id="imgTotalFileSize" src="/images/spacer.gif" style="display:none;" /> </div>
   </div>
   <div id="LoaderWindow">
    <script type="text/javascript">
//Create JavaScript object that will embed Image Uploader to the page.
var iu = new ImageUploaderWriter("ImageUploader1", 800, 650);

//For ActiveX control full path to CAB file (including file name) should be specified.
iu.activeXControlCodeBase = "/Aurigma/v_5.5/ImageUploader5.cab";
iu.activeXControlVersion = "5,5,6,0";

//For Java applet only path to directory with JAR files should be specified (without file name).
iu.javaAppletJarFileName = "ImageUploader2.jar";
iu.javaAppletCodeBase = "/Aurigma/v_4.5/";
iu.javaAppletCached = true;
iu.javaAppletVersion = "2.5.35.0";

iu.showNonemptyResponse = "off";
iu.addParam("RememberLastVisitedFolder", "true");
//Configure appearance and behaviour.
iu.addParam("PaneLayout", "ThreePanes");
iu.addParam("FolderView", "Thumbnails");
iu.addParam("TreePaneWidth", "205");
iu.addParam("ThumbnailHorizontalSpacing", "5");
iu.addParam("ThumbnailVerticalSpacing", "1");
iu.addParam("ShowUploadListButtons", "true");
iu.addParam("ShowButtons", "false");
//iu.addParam("MaxFileCount", "100");
iu.addParam("MaxFileSize", "20971520");
iu.addParam("FileIsTooLargeText", "Larger than 20 Mb");
iu.addParam("ShowSubfolders", "false");
iu.addParam("AllowMultipleRotate", "true");
iu.addParam("AdditionalFormName", "form_action_form");
iu.addParam("Action", "<? echo $r_path; ?>scripts/save_file_uploader.php?folder=<? echo $Name; ?>&event=<? echo time().$path[2]; ?>&parnt_group_id=<? echo $PGroupId; ?>&token=<? echo session_id(); if(isset($_GET['admin'])) echo '&admin='.$_GET['admin'].'&adminid='.$_GET['adminid']; ?>");
//Configure URL where to redirect after upload.
iu.addParam("RedirectUrl", "<? echo $_SERVER['PHP_SELF']; ?>?updated=true&id=<? echo time().$path[2]; if($token!="")echo "&".$token; ?>");
<? 
if($MBLeft <= 750){
	$MaxTotalFileSize = $MBLeft*1024;
	echo 'iu.addParam("MaxTotalFileSize", "'.$MaxTotalFileSize.'");';
	echo 'iu.addParam("MessageMaxTotalFileSizeExceededText", "You are reaching your space limitation please upgrade your account.");';
} ?>
iu.addParam("AllowFolderUpload", "false");
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
// iu.addParam("LicenseKey", "6375-7526-6373-9693;9264-9272-8784-8480"); // 4.5 keys
//iu.addParam("LicenseKey", "71050-10000-F5A08-3F4D5-54C20;72050-10000-933B2-32A02-A0396"); // 5.0 keys
iu.addParam("LicenseKey", "71050-10000-F5A08-3F4D5-54C20;9264-9272-8784-8480"); // 4.5 & 5.0 keys
//iu.addParam("LicenseKey", "71050-4148A-00000-0C1AD-AD664;72050-4148A-00000-0A0A0-64492"); // Trial
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
   </div></td>
 </tr>
 <? } ?>
</table>
