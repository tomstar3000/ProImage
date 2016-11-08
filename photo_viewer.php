<?php
$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 2;
$r_path = "";
for ($n = 0; $n < $count; $n++)
  $r_path .= "../";
define('Allow Scripts', true);
require_once($r_path . 'scripts/cart/ssl_paths.php');
require_once($r_path . 'scripts/fnct_clean_entry.php');
require_once($r_path . 'scripts/fnct_format_phone.php');
require_once($r_path . 'Connections/cp_connection.php');
if (isset($_GET['Photographer'])) {
  $handle = clean_variable($_GET['Photographer'], true);
  $handle = str_replace(" ", "_", $handle);
} else if ($_SERVER['QUERY_STRING'] != "") {
  $handle = clean_variable($_SERVER['QUERY_STRING'], true);
  $handle = str_replace(" ", "_", $handle);
} else {
  $handle = false;
}
$code = clean_variable($_GET['code'], true);
$code = str_replace("&amp;", "&", $code);
if (!isset($_GET['email'])) {
  $path = pathinfo($_SERVER['PHP_SELF']);
  $GoTo = $path_parts['dirname'] . '/' . $_GET['Photographer'] . '/public.php?Photographer=' . $_GET['Photographer'] . '&code=' . $_GET['code'];
  header(sprintf("Location: %s", $GoTo));
}
$Email = clean_variable($_GET['email'], true);

mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_desc_2`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb`, `cust_image`, `cust_active`,`cust_paid`, `cust_fcname`, `cust_fwork`, `cust_fext`, `cust_femail`, `cust_icon`, `cust_canceled`, `cust_del` FROM `cust_customers` WHERE `cust_handle` = '$handle' LIMIT 0,1";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

// check if there are images to display
$query_check = "SELECT `event_id` FROM `photo_event` INNER JOIN `cust_customers` ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` WHERE `event_num` = '$code' AND `cust_handle` = '$handle' AND `event_use` = 'y' ORDER BY `event_id`";
$check = mysql_query($query_check, $cp_connection) or die(mysql_error());
$total_check = mysql_num_rows($check);
$row_check = mysql_fetch_assoc($check);
$Event_id = $row_check['event_id'];

$codehandle = $code . $handle;

if( isset( $_COOKIE['PhotoExpress_Guestbook_'.$codehandle] ) && $_COOKIE['PhotoExpress_Guestbook_'.$codehandle] == $Email ){
  $query_get_email = "SELECT `email` FROM `photo_quest_book` WHERE `email` = '$Email' AND `event_id` = '$Event_id' LIMIT 0,1;";
  $get_email = mysql_query($query_get_email, $cp_connection) or die(mysql_error());
  $total_email = mysql_num_rows($get_email);
  
  $addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);

  session_start();
  
  if($total_email > 0){
    $promo = isset($_SESSION['promo']) ? ", `promotion` = '" . $_SESSION['promo'] . "'" : '';
    $addInfo->mysql("UPDATE `photo_quest_book` SET `visits` = `visits` + 1, `last_login` = NOW()" . $promo . " WHERE `email` = '$Email' AND `event_id` = '$Event_id';");
  }
  else{
    $promo = isset($_SESSION['promo']) ? ", `promotion`" : '';
    $promo2 = isset($_SESSION['promo']) ? ",'" . $_SESSION['promo'] . "'" : '';
    $addInfo->mysql("INSERT INTO `photo_quest_book` (`event_id`,`email`,`visits`, `first_login`, `last_login`" . $promo . ") VALUES ('$Event_id','$Email','1', NOW(), NOW()" . $promo2 . ");");
  }

  if(isset($_SESSION['promo'])){
    unset($_SESSION['promo']);
  }
}
else{
  $path = pathinfo($_SERVER['PHP_SELF']);
  $GoTo = $path_parts['dirname'] . '/' . $_GET['Photographer'] . '/index.php?Photographer=' . $_GET['Photographer'] . '&code=' . $_GET['code'];
  header(sprintf("Location: %s", $GoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon" />
    <link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon" />
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
    <script type="text/javascript" src="/javascript/standard_functions.js"></script>
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
  <body>
    <div id="SupportBackgroud"></div>
    <div id="Support">
      <div>
        <iframe src="/support.php?Photographer=<?php echo $handle; ?>&code=<?php echo $code; ?>"></iframe>
      </div>
    </div>
    <div id="btnSupport" align="right"><a href="javascript:Support(true);">Support</a></div>
    <div id="Container" style="border:solid 1px #2a2a2a; background:#000000">
    <? if( $total_check != 1 || $row_get_info['cust_active'] == 'n' || ( $row_get_info['cust_canceled'] == 'y' || $row_get_info['cust_del'] == 'y' )){ ?>
      <div class="popover">
        <div class="message">
          Photographer Account Inactive
        </div>
      </div>

      <?
        $Email = null;
        $code = null;
     } ?>
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

        AC_FL_RunContent('codebase', 'http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0', 'name', 'PhotoExpress', 'width', '100%', 'height', '100%', 'align', 'middle', 'id', 'PhotoExpress', 'src', '/flash/PhotoExpress_7_2_2?email=<? echo $Email; ?>&code=<? echo rawurlencode($code); ?>&photographer=<?
echo $handle;
if (isset($_GET['full']) && $_GET['full'] == "true") {
  echo "&full=true";
} else {
  echo "&full=false";
}
?>', 'quality', 'high', 'bgcolor', '#000000', 'allowscriptaccess', 'sameDomain', 'pluginspage', 'http://www.macromedia.com/go/getflashplayer', 'wmode', 'transparent', 'movie', '/flash/PhotoExpress_7_2_2?email=<? echo $Email; ?>&code=<? echo rawurlencode($code); ?>&photographer=<?
echo $handle;
if (isset($_GET['full']) && $_GET['full'] == "true") {
  echo "&full=true";
} else {
  echo "&full=false";
}
?>'); //end AC code
      </script>
      <noscript>
        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" name="PhotoExpress" width="100%" height="100%" align="middle" id="PhotoExpress">
          <param name="allowScriptAccess" value="sameDomain" />
          <param name="movie" value="/flash/PhotoExpress_7_2_2.swf?email=<? echo $Email; ?>&amp;code=<? echo rawurlencode($code); ?>&amp;photographer=<?
          echo $handle;
          if (isset($_GET['full']) && $_GET['full'] == "true") {
            echo "&full=true";
          } else {
            echo "&full=false";
          }
          ?>" />
          <param name="quality" value="high" />
          <param name="bgcolor" value="#000000" />
          <param name="wmode" value="transparent" />
          <embed src="/flash/PhotoExpress_7_2_2.swf?email=<? echo $Email; ?>&amp;code=<? echo rawurlencode($code); ?>&amp;photographer=<?
          echo $handle;
          if (isset($_GET['full']) && $_GET['full'] == "true") {
            echo "&full=true";
          } else {
            echo "&full=false";
          }
          ?>" width="100%" height="100%" align="middle" quality="high" bgcolor="#000000" name="PhotoExpress" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" />
        </object>
      </noscript>
      <script type="text/javascript">
        var hasReqestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
        if (hasReqestedVersion == false) {
          var CrnFPly = GetSwfVer();
          if (CrnFPly != -1)
            CrnFPly = CrnFPly.replace(",", ".");
          else
            CrnFPly = "0.0.0.0";
          var HTML = '<p>You are using Flash Player version ' + CrnFPly + '. We require that you use Flash Player version ' + requiredMajorVersion + '.' + requiredMinorVersion + '.' + requiredRevision + '.0</p><p style="font-size:16px;color:#C00000;"><strong>Did you install the flash player and you still cannot see the event images? If so please read the instructions below and follow them completely.</strong></p><br clear="all" />'
                  + '<div style="float:left; padding:5px; width:485px;">'
                  + '<h1>Step #1</h1><p>First time seeing this page?</p>'
                  + '<p>Go to <a href="http://get.adobe.com/flashplayer/" target="_blank" title="Flash Player">http://get.adobe.com/flashplayer/</a> to install the new Flash Player</p>'
                  + '<p>If you cannot see the photos after Step #1 please follow Step #2</p></div>'
                  + '<div style="float:left; padding:5px; width:485px;"><h1>Step #2</h1>'
                  + '<p>I have been here already!</p>'
                  + '<p>Go to <a href="http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_14157&sliceId=1" target="_blank" title="Flash Player">http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_14157&amp;sliceId=1</a> to uninstall your current Flash Player<br />'
                  + 'Follow all instructions on this page and repeat Step #1</p><p>or use one of the uninstallers below</p>'
                  + '<ul style="font-size:12px; color:#FFFFFF;">'
                  + '<li>Windows: <a href="http://download.macromedia.com/pub/flashplayer/current/uninstall_flash_player.exe">uninstall_flash_player.exe</a> (204 KB)</li>'
                  + '<li>Mac OS X, version 10.3 and above: <a href="http://fpdownload.macromedia.com/get/flashplayer/current/uninstall_flash_player_osx.dmg">uninstall_flash_player_osx.dmg</a> (243 KB)</li>'
                  + '<li>Mac OS X, version 10.2 and below: <a href="http://download.macromedia.com/pub/flash/ts/uninstall_flash_player_osx_10.2.dmg">uninstall_flash_player_osx_10.2.dmg</a> (1.3 MB)</li>'
                  + '<li>Mac OS 8.x, 9.x: <a href="http://download.macromedia.com/pub/flash/ts/uninstall_flash_player.hqx">uninstall_flash_player.hqx</a> (33 KB)</li></ul></div>'
                  + '<br clear="all" /><p>If you are still having difficulties after following both steps please contact our <a href="javascript:Support(true);">technical support</a></p>';
          document.getElementById('Container').innerHTML = HTML;
          document.getElementById('Container').style.paddingTop = "10px";
          document.getElementById('Container').style.paddingLeft = "5px";
          document.getElementById('Container').style.paddingRight = "5px";
          document.getElementById('Container').style.width = "990px";
          document.getElementById('Container').style.height = "500px";
        }
      </script>
    </div>
    <div id="Footer" style="border:solid 1px #2a2a2a">
      <div style="float:left">
        <?
        $Company = ($row_get_info['cust_fcname'] != "") ? $row_get_info['cust_fcname'] : $row_get_info['cust_cname'];
        $Email = ($row_get_info['cust_femail'] != "") ? $row_get_info['cust_femail'] : $row_get_info['cust_email'];
        $Work = ($row_get_info['cust_fwork'] != "0") ? $row_get_info['cust_fwork'] : $row_get_info['cust_work'];
        $Ext = ($row_get_info['cust_fext'] != "0") ? $row_get_info['cust_fext'] : $row_get_info['cust_ext'];
        $Icon = $row_get_info['cust_icon'];
        ?>
        <p class="no_indent"><? echo $Company; ?><br />
          <a href="mailto:<? echo $Email; ?>" class="Footer_Nav"><? echo $Email; ?></a><br />
          <?
          if ($Work != "0") {
            echo phone_number($Work);
            if ($Ext != "0")
              echo " Ext. " . $Ext;
          }
          ?>
          <br />
          <a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
      </div>
      <?
      if ($Icon != "" && is_file($photographerFolder . "photographers/" . $handle . '/' . $Icon)) {
        list($width) = getimagesize($photographerFolder . "photographers/" . $handle . '/' . $Icon);
        $width = ($width < 750) ? $width : 750;
        $data = array(
            'imagetype' => 'footer',
            'image' => $handle . '/' . $Icon,
            'width' => $width,
            'height' => 50,
            'salt' => time(),
        );
        require_once $r_path . 'scripts/cart/encrypt.php';
        $data = base64_encode(encrypt_data(serialize($data)));
        ?>
        <div style="float:right;"> <img src="/images/image.php?data=<? echo $data; ?>" height="50" width="<? echo $width; ?>"/> </div>
      <? } ?>
    </div>
  </body>
</html>
