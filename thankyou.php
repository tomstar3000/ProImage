<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Contact", true);
define('Allow Scripts',true);
require_once($r_path.'scripts/cart/ssl_paths.php'); 
require_once($r_path.'scripts/fnct_clean_entry.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrContactUs"><span>Contact Us</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom"><br clear="all" />
          <div class="Column3">
            <h2>Thank you</h2>
            <p>Thank you for your recent inquiry. We recieved your email and will respond to your question or suggestion within 24 hours. If you would like to reach us by phone please feel free to call us at 303.440.6008. We look forward to talking with you.  </p>
            <p>If you should need to contact us in the future our email address is <a href="mailto:info@proimagesoftware.com">info@proimagesoftware.com</a>. </p>
            <br clear="all" />
          </div>
        </div>
      </div>
      <br clear="all" />
    </div>
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
