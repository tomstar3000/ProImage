<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Software", true);
define("Events", true); ?>
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
            <h1 class="HdrEventManager"><span>Events Manager</span></h1>
            <p>Events management and presentation has never been so easy. <br />
              ProImage Software is pulling out all of the stops. With our  ear to the forums and with photographers driving our product growth, we&rsquo;ve  created events management software that works to keep you in the field, doing  what you love to do, while we take care of your orders and prints. </p>
            <p> Work directly with our lab and experience world class  customer service for you and for your clients. Direct lab integration allows us  to handle your orders entirely if you choose, and we&rsquo;ll be the portal to your  clients needs. </p>
            <p> NO MORE FTP! Ftp is cumbersome, and you always have to have  your computer with you to use it. More importantly, your ability to control  your groups and folder organization is completely lost. With the ProImage  Software event management software, enjoy direct uploading to our server, with  no FTP. Additionally, with our images and groups manager, you can easily and  quickly update, change, add, rename and edit your groups and photos, right from  the control panel. </p>
            <p> Work from anywhere in the world. All you need is a computer  and the internet, and you can manipulate the software, upload pictures, and  manage all of your events. </p>
            <p> Capture and Grow<br clear="all" />
              </p>
          </div>
          <div class="Column2">
            <blockquote>
              <h2><span class="IcnCreate"></span>Create. Start Upload. Walk Away.</h2>
              
                <p>Save recurring data and setting you use most<br />
                Create now, upload anytime<br />
                Edit numerous options from one location</p>
              <h2><span class="IcnPrsnt"></span>Superior Presentation &amp; Ordering.</h2>
              <p>Built-in  Ecommerce<br />
Professional,  personally branded interface<br />
Integrated  Slide Show<br />
Integrated  card creation software</p>
              <h2><span class="IcnData"></span>Direct Lab Integration. </h2>
              <p>Integrated  order handling<br />
Fabulous  prints<br />
Correction  services <br />
In  house customer service </p>
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
