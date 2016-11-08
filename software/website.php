<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Software", true);
define("Website", true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/ProImageSoftware_09.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="Container">
  <? include $r_path.'includes/_navigation.php'; ?>
  <div id="Content2" class="Grey">
    <div id="Text">
      <h1 class="HdrTheSoftware"><span>The Software</span></h1>
      <? include $r_path.'software/_navigation.php'; ?>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="Column">
            <h1 class="HdrWebsiteManager"><span>Website Manager</span></h1>
            <p>ProImage Software introduces web management tools. Our web  manager is just the beginning of customized brand connection with your clients.  Why use ours? We aren&rsquo;t your business; we&rsquo;re just helping you with yours. </p>
            <p> Our web feature allows you to update a simple photographer  bio page, branded as your own. This landing page is the launching pad for all  of your events. Why use ours? Instead of confusing your clients, send them to  your website to launch your events. You won&rsquo;t find our name anywhere on there.  It&rsquo;s all yours. </p>
            <p> Easily edit the header and footer images. Dynamically change  the copy on the site, and send a link to all of your clients to directly access  their event. With this process, your clients will think all this software is  completely custom and unique, provided by you&hellip; the photographer. </p>
            <p> Finally, we&rsquo;ve merged your event management software with  your own branding capabilities. A personalized web presence, easy to maintain,  easy to update; all managed by the same control panel.</p>
            <p> Capture and Grow<br clear="all" />
              </p>
          </div>
          <div class="Column2">
            <blockquote>
              <h2><span class="IcnBestFoot"></span>Your Best Foot Forward</h2>
              <p>Present  public events to new customers<br />
Easily  show new and updated work</p>
              <h2><span class="IcnSimpleContent"></span>Simple Content Management </h2>
              <p>Change  your content easily<br />
Control  the look your customers see</p>
            </blockquote>
            <br clear="all" />
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
