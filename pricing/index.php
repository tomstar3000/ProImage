<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Pricing", true); ?>
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
      <h1 class="HdrProImagePlan"><span>Pro Image Plans</span></h1>
      <ul class="ProSubNav">
        <li><a href="/pricing/" title="Plan Details &amp; Full Features" class="NavSel">Plan Details &amp; Full Features</a> </li>
        <li><a href="/whitepapers/Lab Print Pricing.pdf" target="_blank" title="Lab Pricing">Lab Pricing</a> </li>
        <li><a href="/pricing/faq.php" title="FAQ">FAQ</a></li>
      </ul>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="Column3">
            <h2>Plan Details &amp; Full Features</h2>
            <h2>ProImage Portrait  Plan</h2>
            <p> With our portrait plan, you&rsquo;ll enjoy access to all of the  features of our software. All of the customer service and of course, the world  class quality printing you need from your lab. </p>
            <p> We provide 25 Gigs of storage space for your events, which  for most of our photographers totals about 15 events. We only charge a 15%  revenue sharing fee. This means, if you complete $1,000 in revenue sales, we  only collect $150 for the unmatched support and service you and your customers  receive.</p>
            <p> The Portrait Plan is perfect for any photographer that wants  to provide the best customer service possible for their clients and enjoy the  ease of mind that all your prints will come out as great as your work is  supposed to. </p>
            <h2>ProImage Event Plan</h2>
            <p>Need a bit more room. The Event Plan is for serious pros,  who want to make serious money. Our Event Plan provides you with unlimited  storage and a reduced 10% revenue sharing fee.&nbsp;</p>
            <p> Most importantly, you and your clients will gain access to  our unbeatable customer service and our world class print quality. </p>
            <p>We want to hear your voice. It&rsquo;s about time the  photographers had a voice. Our software is mobile, it moves and molds to the  photographers desires. Not only do our photographer&rsquo;s clients get grade A  customer service, but ur  photographers get the best customer service themselves.It is a complete rarity  in software design to have a community drive the creation of the next feature  we add to the site, but we&rsquo;ve decided this is the only way to create our  software. </p>
            <p>The Event Plan is perfect for the serious photographer,  ready to grow their business. </p>
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
