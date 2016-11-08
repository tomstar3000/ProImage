<?
if (!isset($r_path)) {
    $count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
    $r_path = "";
    for ($n = 0; $n < $count; $n++)
        $r_path .= "../";
}
$MainPath = $r_path;
$r_path = '../';
require_once $r_path . 'includes/cp_connection.php';
require_once $r_path . 'scripts/encrypt.php';
require_once $r_path . 'scripts/fnct_clean_entry.php';
if (isset($_GET['data'])) {
    $data = unserialize(base64_decode(urldecode($_GET['data'])));
    foreach ($data as $k => $v)
        $$k = $v;
}
$fid = trim(clean_variable($_GET['fid'], true));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Navigator</title>
        <link href="<? echo $NavFolder; ?>/css/stylesheet.css" rel="stylesheet" type="text/css" media="screen" />
        <script src="/javascript/jquery-1.5.2.min.js" type="text/javascript"></script>
        <script src="/Aurigma/v_7.0/aurigma.uploader.js" type="text/javascript"></script>
        <script src="/Aurigma/v_7.0/aurigma.uploader.installationprogress.js" type="text/javascript"></script>
        <script src="/Aurigma/v_7.0/script.js" type="text/javascript"></script>
        <script type="text/javascript">
            //<![CDATA[	
            function formatFileSize(value){
            if (value < 1024){ return value + " b";
            } else if (value < 1048576){ return Math.round(value / 1024) + " Kb";
            } else if (value < 1073741824){ return Math.round(value / 10485.76) / 100 + " Mb";
            } else { return Math.round(value / 10737418.24) / 100 + " Gb";
            } }
            function send_Msg(VAL, DIS){
            switch (VAL){
            case "Active": var URL = '<? echo $NavFolder; ?>/includes/clear_activex.html'; break;
                    case "Cache": var URL = '<? echo $NavFolder; ?>/includes/clear_cache.html'; break;
                    case "CacheMac": var URL = '<? echo $NavFolder; ?>/includes/clear_cacheMac.html'; break;
            }
            try { Gateway = new ActiveXObject("Microsoft.XMLHTTP"); Gateway.async = "false"; } // Internet Explorer
            catch (e) { try{ Gateway = new XMLHttpRequest(); } // Firefox, Opera 8.0+, Safari
            catch (e){ Gateway = new ActiveXObject('MSXML2.XMLHTTP.3.0'); } } // Internet Explorer 5.5 and 6
            if (Gateway != null) {
            Gateway.onreadystatechange = function() {sending_Msg(DIS); };
                    Gateway.open("GET", URL, true); Gateway.send("");
            } }
            function sending_Msg(DIS){ var TArry = new Array('close_Msg');
                    if (Gateway.readyState == 4) { parent.send_Msg(Gateway.responseText, DIS, 'Navigator', TArry);
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
<?
if (intval($fid) > 0) {
    $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
    $getInfo->mysql("SELECT `group_name` FROM `photo_event_group` WHERE `group_id` = '$fid';");
    $getInfo = $getInfo->Rows();
    echo 'You are uploading to group "' . $getInfo[0]['group_name'] . '"';
} else {
    echo 'You are uploading to the Main Group';
}
echo '<br clear="all" />';
if (eregi('MSIE', strtoupper($_SERVER['HTTP_USER_AGENT']))) {
    ?>
                        Notice that we are using Aurigma 7.0<br />
                        If you are having problems <a href="javascript:send_Msg('Active',true);" title="Learn how to clean out your Active X controllers">Click
                            Here to learn how to</a> clean your Active X Controllers.
<? } else { ?>
                        Notice that we are using Aurigma 7.0<br />
                        If you are having problems <a href="javascript:send_Msg('<?
    if (eregi('WIN', strtoupper($_SERVER['HTTP_USER_AGENT'])))
        echo 'Cache';
    else if (eregi('MAC', strtoupper($_SERVER['HTTP_USER_AGENT'])))
        echo 'CacheMac';
    else
        echo 'Cache'
        ?>',true);" title="Learn how to clean out your broswers cache">Click
                            Here to learn how to</a> clean your browser cache.
<? } ?>
                </p>
                <p>Having issues, use the previous version of the uploader <a href="<? echo $NavFolder; ?>/includes/uploader_5-5.php?data=<? echo $_GET['data']; ?>&fid=<? echo $_GET['fid']; ?>" title="Use the previous version of the uploader">here</a></p>
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
                <option value="Thumbnails"<? if ($Group === true) echo ' selected="selected"'; ?> title="Thumbnails">Thumbnails</option>
                <option value="Tiles" title="Tiles">Tiles</option>
                <option value="List"<? if ($Group === false) echo ' selected="selected"'; ?> title="List">List</option>
                <option value="Details" title="Details">Details</option>
            </select>
            <!-- <a href="#" onClick="imageUploader1.Refresh();return false;" class="BtnLoadRefresh">Refresh</a> --><a href="#" onClick="$au.uploader('ImageUploader1').upload(); return false;" class="BtnLoadUpload" title="Upload images to the server">Upload</a><a href="#" onClick="$au.uploader('ImageUploader1').folderPane().deselectAll(); return false;" class="BtnLoadDeselAll" title="Deselect all images">Deselect All</a><a href="#" onClick="$au.uploader('ImageUploader1').folderPane().selectAll(); return false;" class="BtnLoadSelAll" title="Select all images">Select All</a><a href="<? echo $NavFolder; ?>/includes/index.php?data=<? echo $_GET['data']; ?>" class="BtnLoadBack" title="Back to organizer">Back to organizer</a></div>

        <div id="LoaderWindow">
            <script type="text/javascript">

                    var uploader = $au.uploader({
            id: 'ImageUploader1',
                    width: '<? echo $Width; ?>px',
                    height: '<? echo $Height - 200; ?>px',
                    //licenseKey: '76FF1-00119-73D80-000AA-0DBA7-9BB691', // Active
                    licenseKey: '76FF4-004F0-B251A-98EA9-8E0F2-EC124A', // Trial
                    enableDescriptionEditor: false,
                    enableRotation: true,
                    uploadButtonText: "",
<? if (intval($fid) == 0) { //Lets load Groups of images  ?>
                folderProcessingMode: 'Upload',
<? } ?>
            paneLayout: "ThreePanes",
<? if (intval($fid) == 0) { //Lets load Groups of images  ?>
                treePane: {
                width: 0
                },
<? } else { // Lets load to a single folder  ?>
                treePane: {
                width: 205
                },
<? } ?>
            activeXControl: {
            codeBase: '/Aurigma/v_7.0/ImageUploader7.cab',
                    codeBase64: '/Aurigma/v_7.0/ImageUploader7_x64.cab'
            },
                    javaControl: {
            codeBase: '/Aurigma/v_7.0/ImageUploader7.jar'
            },
                    uploadSettings: {
            actionUrl: '<? echo str_replace("//", "/", $MainPath . $NavFolder); ?>/xml/save_image.php?fid=<? echo $fid; ?>&event=<? echo base64_encode($EvntID); ?>&time=<? $time = time();
echo $time; ?>',
                    redirectUrl: '<? echo $NavFolder; ?>/includes/index.php?data=<? echo $_GET['data']; ?>&fid=<? echo $fid; ?>&event=<? echo base64_encode($EvntID); ?>&time=<? echo $time; ?>',
                    filesPerPackage: 1,
                    autoRecoveryMaxAttemptsCount: 5,
                    autoRecoveryTimeout: 5000,
                    showNonemptyResponse: "off",
            },
                    restrictions: {
            fileMask: '*.jpg;*.jpeg;*.jpe;*.bmp;*.gif;*.png'
<? if (intval($fid) > 0) { ?>
                ,
                        maxFileSize: 20971520
<? } ?>

<? if ($MBLeft <= 750) {
    $MaxTotalFileSize = $MBLeft * 1024; ?>
                ,
                        maxTotalFileSize: <? echo $MaxTotalFileSize; ?>
<? } ?>
            },
                    messages: {
            maxFileSizeExceeded: "Larger than 20 Mb",
                    maxTotalFileSizeExceeded: "You are reaching your space limitation please upgrade your account.",
            },
                    converters: [
            { 	mode: '*.*=SourceFile' },
            { 	mode: '*.*=Thumbnail',
                    thumbnailWidth: 960,
                    thumbnailHeight: 640,
                    //thumbnailCopyExif: true,
                    thumbnailFitMode: "Fit",
                    thumbnailJpegQuality: "90",
                    thumbnailResizeQuality: "high"
            }
            ],
                    folderPane: {
            height: 250,
<? if (intval($fid) == 0) { //Lets load Groups of images  ?>
                viewMode: 'List',
<? } else { // Lets load to a single folder  ?>
                viewMode: 'Thumbnails',
<? } ?>
            previewSize: 75
            },
                    uploadPane: {
            viewMode: 'List'
            },
                    detailsViewColumns: {
            infoText: ''
            },
                    paneItem: {
            showFileNameInThumbnailsView: true,
            },
                    events: {
            uploadFileCountChange: [
                    ImageUploader_UploadFileCountChange
            ],
                    viewChange:[
                    ImageUploader_ViewChange
            ]
            },
                    uploadProgressDialog: {
            hoursText: "Hour",
                    minutesText: "Min",
                    secondsText: "Sec"
            }
            });
                    //$au.debug().level(3);
                    //$au.debug().mode(['popup','console']);

                    var ip = $au.installationProgress(uploader);
                    //ip.progressImageUrl('../Images/installation_progress.gif');
                    ip.progressCssClass('ip-progress');
                    ip.instructionsCssClass('ip-instructions');
                    uploader.writeHtml();
            </script>
        </div>
    </body>
</html>
