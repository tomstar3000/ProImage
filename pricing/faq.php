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
        <!-- <li><a href="/pricing/" title="Plan Details &amp; Full Features">Plan Details &amp; Full Features</a> </li> -->
        <li><a href="/pricing/lab_pricing.php" title="Lab Pricing">Lab Pricing</a> </li>
        <li><a href="/pricing/faq.php" title="FAQ" class="NavSel">FAQ</a></li>
      </ul>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="Column3">
            <h2>FAQ </h2>
            <blockquote>
              <p><strong>Q:</strong> How does Pro Image compare to similar solutions like Pictage and  Colleges Net?<br />
                <strong>A:</strong> Our system was designed around simplicity, direct access to IMAGES, and  sales!&nbsp; We believe our back end  photographer database is more robust and simple to use, while our client  interface is unparalleled. The client  should see images and be able to order them easily. Period. Our overall cost is lower than any other  system out there!</p>
              <p><strong>Q:</strong> How does Pro Image work?<br />
                <strong>A:</strong> The photographer creates an event, uploads images, and directs their  client to that event. The client browses the images, selects images to order  and places the order with a credit card.&nbsp;  Pro Images takes the payment from your client. Our lab (Reed Imaging in Denver, Colorado)  prints the order and sends it to the client. Pro Images mails you a check at the end of each month with your  profits. Simple, easy!</p>
              <p><strong>Q:</strong> How much storage will I need?<br />
                <strong>A:</strong> Currently  we are offering unlimited storage for all plans. This means you don&rsquo;t need to worry about how  big your files are, how many you upload, etc. We plan on keeping things this way. Storage is cheap, no need to charge you for something that we barely pay  for!</p>
              <p> <strong>Q:</strong> What plan is best for me?<br />
                <strong>A:</strong> The Casual Plan is suggested for photographers who expect to sell less  than $300 per month. If you average  sales between $300 and $1000 per month the Basic Plan is more economical. Once you exceed $1000 per month, the Pro Plan  is your best deal.</p>
              <p> <strong>Q:</strong> Can I &ldquo;turn off my service&rdquo; during my slow times?<br />
                <strong>A:</strong> You  receive 45 days of free service the first time you sign up with Pro Image, and  we have no registration fee. After that,  there is a $75 fee required to &ldquo;re-sign up&rdquo;. We suggest budgeting your membership fees over the year and realizing that  you will more than pay for usage in your busy months.&nbsp; Or, you can get the Pro Plan for $348 for the  entire year and save a huge amount!</p>
              <p><strong>Q:</strong> What are your support hours?<br />
                <strong>A:</strong> Our normal support hours are Monday through Friday 8 am to 8 pm.  However, we will often check email support questions outside of these hours and  on the weekends. </p>
              <p><strong>Q: </strong>Is my plan month to month or do I have to commit to a year membership?<br />
                <strong>A: </strong>All plans are month to month. We feel you will be completely satisfied  with our system but we do not want to trap you into something you are not happy  with should you feel this system is not for you. That being said, once you have experience the  amazing service and felt the impact it has on your business, we do offer a  yearly membership that saves you a huge amount of money!</p>
              <p><strong>Q:</strong> How are taxes taken care of?<br />
                <strong>A:</strong> Your customers will be billed for all sales taxes on orders shipped  within the state of Colorado so you don&rsquo;t have to pay any sales tax on your  orders. </p>
              <p><strong>Q:</strong> When can I expect a check for my income on sales generated?<br />
                <strong>A:</strong> Checks are sent on the 1st of each month, and should reach  you in less than a week. </p>
              <p><strong>Q: </strong>Do I have to pay revenue share fees on discounted orders.<br />
                <strong>A:</strong> Revenue  share fees are calculated by taking the total sales of prints (not including  sales taxes and shipping) minus the discount. Example if you make $100 on an  order and discount 10% you will make $90 and the revenue share is based on that  $90. This means that selling prints at a discount does not further reduce your  profit due to revenue share.</p>
              <p><strong>Q: </strong>Who pays for shipping?<br />
                <strong>A: </strong>Your clients pay for shipping according to the type of shipping they  select. </p>
            </blockquote>
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
