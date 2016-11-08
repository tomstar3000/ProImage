<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/cart/encrypt.php');
require_once($r_path.'scripts/fnct_sql_processor.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/javascript/custom-form-elements.js"></script>
<script type="text/javascript" src="/javascript/AC_OETags.js"></script>
<script language="JavaScript" type="text/javascript">
var requiredMajorVersion = 9;
var requiredMinorVersion = 0;
var requiredRevision = 124;
</script>
</head>
<body>
<div id="Container">
  <? include $r_paht.'includes/_PhotoSlideShow.php'; ?>
  <div id="Content">
    <? include $r_path.'includes/_PhotoNavigation.php'; ?>
    <div id="TextLong" class="bgGuest">
      <h2>Welcome</h2>
      <h1><? echo $getCode[0]['event_name']; ?></h1>
      <? echo $getCode[0]['event_desc']; ?>
      <form method="post" name="Guestbook_Form" id="Guestbook_Form" enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>">
        <p class="Notification">A valid e-mail is required to choose favorites, take advantage of discounts, and experience full site functionality.</p>
        <div class="Colmn">
          <label for="Name">First Name</label>
          <div class="CstmInput">
            <input type="text" name="Name" id="Name" title="First Name" tabindex="<? $tab=1; echo $tab++; ?>" />
          </div>
        </div>
        <div class="Colmn">
          <label for="Email">E-mail</label>
          <div class="CstmInput">
            <input type="text" name="Email" id="Email" title="Email" tabindex="<? echo $tab++; ?>" />
          </div>
        </div>
        <br clear="all" />
        <br clear="all" />
        <div class="GuestMsg">
          <p> <span class="ChckHldr">
            <input name="Promotion" type="checkbox" class="CstmFrmElmnt" id="Promotion" value="y" checked="checked" />
            </span> <strong>We encourage you to leave this box check marked and supply a valid email address to take advantage of the functions of the site and specials that may be   offered such as discount codes, special pricing offers and event viewing expiration. We will not sell or share your email address with any other entity   and you will receive communication ONLY regarding this event. Thank you for your trust.</strong><br clear="all" />
          </p>
          <div id="CstmEnterLink">
            <input type="submit" name="Submit" id="Submit" value="Submit" onclick="MM_validateForm('Email','','RisEmail'); return document.MM_returnValue;" />
          </div>
          <br clear="all" />
        </div>
        <br clear="all" />
        <input name="Fullscreen" id="Fullscreen" type="hidden" value="<? echo $_POST['Fullscreen']; ?>"  />
        <input name="Event_Code" id="Event_Code" type="hidden" value="<? echo $code; ?>" />
      </form>
      <p id="Incs" class="IcnEvent">View Event Photos</p>
      <p id="Incs" class="IcnPurchase">Purchase Prints <br />
        &amp; Create Cards</p>
      <p id="Incs" class="IcnShare">Save &amp; Share<br />
        Your Favorite Images</p>
      <br clear="all" />
    </div>
    <br clear="all" />
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
<script type="text/javascript">
	var hasReqestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
	if (hasReqestedVersion == false) {
		var CrnFPly = GetSwfVer(); if(CrnFPly != -1) CrnFPly = CrnFPly.replace(",","."); else CrnFPly = "0.0.0.0";
		var HTML = '<p>You are using Flash Player version '+CrnFPly+'. We require that you use Flash Player version '+requiredMajorVersion+'.'+requiredMinorVersion+'.'+requiredRevision+'.0</p><p style="font-size:16px;color:#C00000;"><strong>Did you install the flash player and you still cannot see the event images? If so please read the instructions below and follow them completely.</strong></p><br clear="all" />'
				+'<div style="float:left; padding:5px; width:485px;">'
  			+'<h1>Step #1</h1><p>First time seeing this page?</p>'
				+'<p>Go to <a href="http://get.adobe.com/flashplayer/" target="_blank" title="Flash Player">http://get.adobe.com/flashplayer/</a> to install the new Flash Player</p>'
				+'<p>If you cannot see the photos after Step #1 please follow Step #2</p></div>'
				+'<div style="float:left; padding:5px; width:485px;"><h1>Step #2</h1>'
				+'<p>I have been here already!</p>'
				+'<p>Go to <a href="http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_14157&sliceId=1" target="_blank" title="Flash Player">http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_14157&amp;sliceId=1</a> to uninstall your current Flash Player<br />'
				+'Follow all instructions on this page and repeat Step #1</p><p>or use one of the uninstallers below</p>'
				+'<ul>'
				+'<li>Windows: <a href="http://download.macromedia.com/pub/flashplayer/current/uninstall_flash_player.exe">uninstall_flash_player.exe</a> (204 KB)</li>'
				+'<li>Mac OS X, version 10.3 and above: <a href="http://fpdownload.macromedia.com/get/flashplayer/current/uninstall_flash_player_osx.dmg">uninstall_flash_player_osx.dmg</a> (243 KB)</li>'
				+'<li>Mac OS X, version 10.2 and below: <a href="http://download.macromedia.com/pub/flash/ts/uninstall_flash_player_osx_10.2.dmg">uninstall_flash_player_osx_10.2.dmg</a> (1.3 MB)</li>'
				+'<li>Mac OS 8.x, 9.x: <a href="http://download.macromedia.com/pub/flash/ts/uninstall_flash_player.hqx">uninstall_flash_player.hqx</a> (33 KB)</li></ul></div>'
				+'<br clear="all" /><p>If you are still having difficulties after following both steps please contact our <a href="javascript:Support(true);">technical support</a></p>';
		document.getElementById('Container').innerHTML = HTML;
	}
</script>
</body>
</html>
