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
</head>
<body>
<div id="Container">
  <? include $r_paht.'includes/_PhotoSlideShow.php'; ?>
  <div id="Content">
    <? include $r_path.'includes/_PhotoNavigation.php'; ?>
    <div id="TextLong" class="bgGuest">
      <h2>Welcome</h2>
      <h1><? echo $getCode[0]['event_name']; ?></h1>
      <p>Thank you for signing our guestbook.  A confirmation e-mail has been sent to you.  Please follow the link to view this event.</p>
      <br clear="all" />
    </div>
    <br clear="all" />
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
