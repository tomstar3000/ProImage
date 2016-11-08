<?
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
$r_path = "";
for ($n = 0; $n < $count; $n++) {
    $r_path .= "../";
}
define('Allow Scripts', true);
require_once($r_path . 'scripts/cart/ssl_paths.php');
require_once($r_path . 'scripts/fnct_clean_entry.php');
require_once($r_path . 'scripts/fnct_format_phone.php');
require_once($r_path . 'Connections/cp_connection.php');
$handle = "photoexpress";
$code = "sample";
mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_desc`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb` FROM `cust_customers` WHERE `cust_handle` = '$handle' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background:url(/images/bg_viewer.jpg)">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
            <link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
                <meta name="robots" content="index,follow" />
                <meta name="keywords" content=""/>
                <meta name="language" content="english"/>
                <meta name="author" content="Pro Image Software" />
                <meta name="copyright" content="2007" />
                <meta name="reply-to" content="info@proimagesoftware.com" />
                <meta name="document-rights" content="Copyrighted Work" />
                <meta name="document-type" content="Web Page" />
                <meta name="document-rating" content="General" />
                <meta name="document-distribution" content="Global" />
                <meta http-equiv="expires" content="0" />
                <meta http-equiv="Pragma" content="no-cache" />
                <title>ProImageSoftware</title>
                <link href="/stylesheets/photoexpress.css" rel="stylesheet" type="text/css" />
                <script type="text/javascript" src="/javascript/GoogleTracker.js"></script>
                <script type="text/javascript" src="/javascript/GoogleTracker2.js"></script>
                <script type="text/javascript" src="/javascript/AC_OETags.js"></script>
                <script type="text/javascript">
                    function Support(VAL) {
                        if (VAL == true) {
                            document.getElementById('SupportBackgroud').style.display = "block";
                            document.getElementById('Support').style.display = "block";
                        } else {
                            document.getElementById('SupportBackgroud').style.display = "none";
                            document.getElementById('Support').style.display = "none";
                        }
                    }
                </script>
                <script language="JavaScript" type="text/javascript">
                    var requiredMajorVersion = 9;
                    var requiredMinorVersion = 0;
                    var requiredRevision = 124;
                </script>
                </head>
                <body style="background:url(/images/bg_viewer.jpg)">
                    <div id="SupportBackgroud"></div>
                    <div id="Support">
                        <div>
                            <iframe src="/support.php"></iframe>
                        </div>
                    </div>
                    <div id="btnSupport" align="right"><a href="javascript:Support(true);">Support</a></div>
                    <div id="Container" style="border:solid 1px #2a2a2a; background:#000000">
                        <script type="text/javascript">
                            width = screen.width;
                            height = screen.height;
                            if (width <= 800 || height <= 600) {
                                width = 558;
                                height = 400;
                            } else if (width <= 1040 || height <= 768) {
                                width = 801;
                                height = 574;
                            } else {
                                width = 1000;
                                height = 717;
                            }
                            document.getElementById('btnSupport').style.paddingLeft = (width - 87) + "px";
                            document.getElementById('Container').style.width = width + "px";
                            document.getElementById('Container').style.height = height + "px";

                            AC_FL_RunContent('codebase', 'http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0', 'name', 'PhotoExpress', 'width', '100%', 'height', '100%', 'align', 'middle', 'id', 'PhotoExpress', 'src', '/flash/PhotoExpress_Demo_7_1_5?code=<? echo $code; ?>&photographer=<? echo $handle; ?>&email=demo@proimagesoftware.com', 'quality', 'high', 'bgcolor', '#000000', 'allowscriptaccess', 'sameDomain', 'pluginspage', 'http://www.macromedia.com/go/getflashplayer', 'movie', '/flash/PhotoExpress_Demo_7_1_5?code=<? echo $code; ?>&photographer=<? echo $handle; ?>&email=demo@proimagesoftware.com'); //end AC code</script>
                        <noscript>
                            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" name="PhotoExpress" width="100%" height="100%" align="middle" id="PhotoExpress">
                                <param name="allowScriptAccess" value="sameDomain" />
                                <param name="movie" value="/flash/PhotoExpress_Demo_7_1_5.swf?code=<? echo $code; ?>&amp;photographer=<? echo $handle; ?>&amp;email=demo@proimagesoftware.com" />
                                <param name="quality" value="high" />
                                <param name="bgcolor" value="#000000" />
                                <embed src="/flash/PhotoExpress_Demo_7_1_5.swf?code=<? echo $code; ?>&amp;photographer=<? echo $handle; ?>&amp;email=demo@proimagesoftware.com" quality="high" bgcolor="#000000" width="100%" height="100%" name="PhotoExpress" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                            </object>
                        </noscript>
                        <script type="text/javascript">
                            var hasReqestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
                            if (hasReqestedVersion == false) {
                                var CrnFPly = GetSwfVer();
                                CrnFPly = CrnFPly.replace(",", ".");
                                var HTML = '<p>You are using Flash Player version ' + CrnFPly + '. We require that you use Flash Player version ' + requiredMajorVersion + '.' + requiredMinorVersion + '.' + requiredRevision + '.0</p><p style="font-size:16px;color:#C00000;"><strong>Did you install the flash player and you still cannot see the event images? If so please read the instructions below and follow them completely.</strong></p>'
                                        + '<p>Due to recent enhancements to the Adobe Flash Player installers, you can now remove the player only by using the Adobe Flash Player uninstaller. To remove Flash Player, simply download and run the appropriate uninstaller for your system using the steps below.</p>'
                                        + '<ol style="font-size:12px; color:#FFFFFF;"><li>Download the Adobe Flash Player uninstaller:</li><ul type=circle>'
                                        + '<li><strong>Windows:</strong> <a href="http://download.macromedia.com/pub/flashplayer/current/uninstall_flash_player.exe">uninstall_flash_player.exe</a> (204 KB) (updated 10/15/08)</li>'
                                        + '<li><strong>Mac OS X, version 10.3 and above:</strong> <a href="http://fpdownload.macromedia.com/get/flashplayer/current/uninstall_flash_player_osx.dmg">uninstall_flash_player_osx.dmg</a> (243 KB) (updated 11/05/08)</li>'
                                        + '<li><strong>Mac OS X, version 10.2 and below:</strong><a href="http://download.macromedia.com/pub/flash/ts/uninstall_flash_player_osx_10.2.dmg">uninstall_flash_player_osx.dmg</a> (1.3 MB) (updated 05/27/08)</li>'
                                        + '<li><strong>Mac OS 8.x, 9.x:</strong> <a href="http://download.macromedia.com/pub/flash/ts/uninstall_flash_player.hqx">uninstall_flash_player.hqx</a> (33 KB) </li></ul>'
                                        + '<li>Save the file to your system, choosing a location where you can find it (for example, your desktop). Macintosh users may need to open or unstuff the .hqx file.</li>'
                                        + '<li>Quit ALL running applications, including all Internet Explorer or other browser windows, AOL Instant Messenger, Yahoo Messenger, MSN Messenger, or other Messengers. Check the Windows system tray carefully to make certain no applications are still in memory which might possibly use Flash Player.</li>'
                                        + '<li>Run the uninstaller. This will remove Adobe Flash Player from all browsers on the system. </li></ol>'
                                        + '<p>If you have followed these instructions completely and you still cannot access the event images please email us at <a  href="mailto:support@proimagesoftware.com">support@proimagesoftware.com</a> . Send us your name and phone number and we will call you as soon as possible yo help fix your unique situation. Please understand this is a problem with Adobe Flash Player and we will be using Adobe Solutions to fix your computer in order to display the event images properly. </p>';
                                document.getElementById('Container').innerHTML = HTML;
                                document.getElementById('Container').style.paddingTop = "10px";
                                document.getElementById('Container').style.height = "500px";
                            }
                        </script>
                    </div>
                    <div id="Footer" style="border:solid 1px #2a2a2a">
                        <div style="float:left">
                            <p> Photo Express ProImageSoftware<br />
                                <a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
                        </div>
                    </div>
                </body>
                </html>
