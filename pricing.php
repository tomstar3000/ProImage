<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("Pricing", true); ?>
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
      <h1 class="HdrProImagePlan"><span>Pro Image Plans</span></h1>
      <ul class="ProSubNav">
        <!-- <li><a href="/pricing/" title="Plan Details &amp; Full Features">Plan Details &amp; Full Features</a> </li> -->
        <li><a href="/pricing/lab_pricing.php" title="Lab Pricing">Lab Pricing</a> </li>
        <li><a href="/pricing/faq.php" title="FAQ">FAQ</a></li>
      </ul>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="ColumnYellow">
            <h2 class="HdrProImage"><span>Pro Image</span></h2>
            <div class="BtnPurchase"><a href="/enroll.php?Service_Level=344">Purchase</a></div>
            <br clear="all" />
            <h2>Casual Plan</h2>
            <ul>
              <li>Monthly Fee: $19.00</li>
              <li>17% Revenue Share</li>
	      	  <li>3% Service Fee</li>
              <li>Complete use of ProImage Software Tools</li>
              <li>Up to 1 year of Event Hosting</li>
              <li>Unlimited Event Image Storage</li>
              <li>Unlimited email and phone Support</li>
            </ul>
            <p>Every plan begins with 45 days of free evaluation.  You may use the full set of features and upload as much as you desire.  After 45 days, you will be required to input a credit card to continue using the system.  Your card will be charged on that day, and your monthly or yearly membership will begin.  Your card will be charged each following month/year until you contact us and request to end your contract.</p>
          </div>
          <div class="ColumnRed">
            <h2 class="HdrProImage"><span>Pro Image</span></h2>
            <div class="BtnPurchase"><a href="/enroll.php?Service_Level=345">Purchase</a></div>
            <br clear="all" />
            <h2>Basic Plan</h2>            
            <ul>
              <li>Monthly Fee: $29.00</li>
              <li>12% Total Revenue Share</li>
	      <li>3% Service Fee</li>
              <li>Complete use of ProImage Software Tools</li>
              <li>Up to 1 year of Event Hosting</li>
              <li>Unlimited Event Image Storage</li>
              <li>Unlimited email and phone support</li>
            </ul>
            <p>Every plan begins with 45 days of free evaluation.  You may use the full set of features and upload as much as you desire.  After 45 days, you will be required to input a credit card to continue using the system.  Your card will be charged on that day, and your monthly or yearly membership will begin.  Your card will be charged each following month/year until you contact us and request to end your contract.</p>
          </div>
          <br clear="all" />
          <div class="ColumnBlue">
            <h2 class="HdrProImage"><span>Pro Image</span></h2>
            <div class="BtnPurchase"><a href="/enroll.php?Service_Level=348">Purchase</a></div>
            <br clear="all" />
            <h2>Pro Plan</h2>
            <ul>
              <li>Monthly Fee: $59.00</li>
              <li>9% Revenue Share</li>
	      	  <li>3% Service Fee</li>
              <li>Complete use of ProImage Software Tools</li>
              <li>Up to 1 year of Event Hosting</li>
              <li>Unlimited Event Image Storage</li>
              <li>Unlimited email and phone Support</li>
            </ul>
            <p>Every plan begins with 45 days of free evaluation.  You may use the full set of features and upload as much as you desire.  After 45 days, you will be required to input a credit card to continue using the system.  Your card will be charged on that day, and your monthly or yearly membership will begin.  Your card will be charged each following month/year until you contact us and request to end your contract.</p>
          </div>
          <div class="ColumnGreen">
            <h2 class="HdrProImage"><span>Pro Image</span></h2>
            <div class="BtnPurchase"><a href="/enroll.php?Service_Level=347">Purchase</a></div>
            <br clear="all" />
            <h2>One Year Pre Purchase</h2>            
            <ul>
              <li>Yearly Fee: $348.00 ($29.00 / month)</li>
              <li>9% Total Revenue Share</li>
	      <li>3% Service Fee</li>
              <li>Complete use of ProImage Software Tools</li>
              <li>Up to 1 year of Event Hosting</li>
              <li>Unlimited Event Image Storage</li>
              <li>Unlimited email and phone support</li>
            </ul>
            <p>Every plan begins with 45 days of free evaluation.  You may use the full set of features and upload as much as you desire.  After 45 days, you will be required to input a credit card to continue using the system.  Your card will be charged on that day, and your monthly or yearly membership will begin.  Your card will be charged each following month/year until you contact us and request to end your contract.</p>
          </div>
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
