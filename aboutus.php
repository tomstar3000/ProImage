<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("About", true); ?>
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
      <h1 class="HdrAboutUs"><span>About Us</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="Column">
            <h2 class="HdrWhoIsPro"><span>Who is Pro Image?</span></h2>
            <p>ProImage Software is 100% about the photographer. We don&rsquo;t  exist without you, so we&rsquo;ve decided to build our software based on your  requests, your desires.</p>
            <p>              We listen, and we listen a lot. Everything you see in our  software is built upon the requests of our pros. ProImage Software is born from  a tradition of excellent customer service. In a market saturated with options  and competitors, our unique identifiable personality comes from our fundamental  roots in lab printing for the nation&rsquo;s best pros. Quality matters; it matters  more than anything, including our profit margin. </p>
            <p>              We also believe that we are your extension to the customer.  Consider this an interview, and you are hiring your customer service manager.  Our customer service is our most valuable asset, and the best product we can  offer to your clients. Unbelievable quality printing aside, if your clients  don&rsquo;t feel like we were helpful from the first moment, then the whole process  is lost. We are dedicated to you and to your clients, you can count on that. </p>
            <p>              This is who we are, and this is what we believe. Born from  photographer input, and grown from lab print roots, we&rsquo;re here to take you to  the next level. <br />
              Join us&hellip;</p>
            <p>Capture and Grow<br clear="all" />
            </p>
          </div>
          <div class="Column">
            <p>&nbsp;</p>
          </div>
          <br clear="all" />
          <br clear="all" />
        </div>
      </div>
      <br clear="all" />
    </div>
  </div>
  <? include $r_path.'includes/_footer.php'; ?>
</div>
</body>
</html>
