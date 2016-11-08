<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Software", true);
define("Business", true); ?>
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
            <h1 class="HdrBusnManager"><span>Business Manager</span></h1>
            <p>Knowledge is power. With our business manager, you&rsquo;ll always  know which events are making you money, and which ones are lacking. </p>
            <p> With easy reporting and sales tracking, you&rsquo;ll always know  what to expect, and this is important. With the ProImage Software Business  Manager software, you have easy access to complete orders, fill special  requests, create gift certificates and discounts, manage coupons and more. </p>
            <p> With customizable price lists, you have ultimate control  over what your customers are charged. Create a new price list for every single  event, we don&rsquo;t care. It&rsquo;s all about what&rsquo;s best and easy for you. With our  business manager, you can create infinite price lists, and better yet, you can  edit those price lists directly from your events manager, so no cumbersome  clicking back and forth. </p>
            <p> See orders, complete orders, and get it done fast. The last  thing you need is to be sitting at your computer for hours a day completing  orders. With our direct lab integration, you don&rsquo;t even have to be there if you  don&rsquo;t want to. However, if you want to be involved, the ProImage Software  Business Manager allows for simple order processing by you, quickly, so you can  get out there and do what you do best. </p>
            <p> Capture and Grow<br clear="all" />
            </p>
          </div>
          <div class="Column2">
            <blockquote>
              <h2><span class="IcnReporting"></span>Powerful Reporting Features</h2>
              <p>Monthly  and yearly event reports<br />
                Complete  event breakdowns</p>
              <h2><span class="IcnOrder"></span>Stay In Control</h2>
              <p>Allow  custom orders &ndash; or don&rsquo;t<br />
                Check  order status<br />
                Easy  approval and editing</p>
              <h2><span class="IcnGift"></span>Show the Love</h2>
              <p>Facilitate  sales and spark interest in events<br />
                Create  and manage gift certificates </p>
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
