<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Software", true);
define("Client", true); ?>
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
            <h1 class="HdrClientManager"><span>Client Manager</span></h1>
            <p>Remember your clients? The ones who keep your business  running? We do, and we&rsquo;ll prove it. </p>
            <p> ProImage Software is your gateway to growth and freedom. By  integrating our event management software with client management features,  we&rsquo;re just a few steps away from fully integrating the entire business  management feature you need into one piece of software. <br />
              Managing your events can make you money, but managing your  clients can make your business thrive. Stay in touch, keep track of important  dates, and stay in contact with your clients. Easily remind your clients about  event notifications, and keep your business on track. </p>
            <p> Our client management software lets you see all of the  clients who are purchasing from you or using your system. It allows you to stay  in touch with those clients and keep contact via your control panel. Want to  import that data into your own system? Easily download your client lists and  guestbook lists to a simple to use CSV file, so you can import that data  wherever you need it. </p>
            <p> Send out coupons and specials &ndash; automatically. </p>
            <p> Watch as our client manager grows, when you want features,  just let us know. </p>
            <p> Capture and grow.<br clear="all" />
            </p>
          </div>
          <div class="Column2">
            <blockquote>
              <h2><span class="IcnCust"></span>Current and Future Customers</h2>
              <p>Manage  event contacts<strong></strong><br />
                Create  now, upload later<strong></strong><br />
                Edit  numerous options from one page<strong></strong></p>
              <h2><span class="IcnMail"></span>Integrated Online Solution</h2>
              <p>Built-in  Ecommerce<br />
                Professional,  personally branded interface<br />
                Integrated  slide show<br />
                Integrated  card creation software</p>
              <h2><span class="IcnMessage"></span>Printing, Shipping, Everything </h2>
              <p>Integrated  order handling<br />
                Fabulous  prints<br />
                Correction  services</p>
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
