<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../"; ?>
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
      <h1 class="HdrTermsConditions"><span>Terms &amp; Conditions</span></h1>
      <br clear="all" />
      <div class="BgText">
        <div class="BgTextBottom">
          <div class="Column3">
            <p>By clicking you agree to the terms and conditions set forth  you the photographer agree to provide high quality files with sufficient  resolution to provide a satisfactory print at the size your client order, at  least a resolution of 200 dpi at the site being printed. You also agree to host  your client&rsquo;s images for the duration set forth between you and your client.  Images uploaded will be within limits of normal taste and not be of  pornographic nature. The photographer nor the photographer&rsquo;s clients nor any  persons viewing images posted by any photographer will have the right to hold  Pro Image Software liable for any content posted to your site that may not be  within good taste or that may offend others from viewing said images. I also agree  to not post said content that may offend any persons viewing images hosted by  Pro Image Software and understand that all images are the ownership and  responsibility of the company that agreed to these terms and conditions and  contracted with Pro Image Software. I understand that images will be hosted for  up to one year of the time of release of event but will keep a back up copy of  images other than with Pro Image Software and do not hold Pro Image Software  liable for any loss of any images for any reason. I also agree to pay my  monthly fees in a timely manner and when due, should my account go into  delinquent status for any period Pro Image Software reserves the right to  suspend my account until my account dues are current. Should my account become not  current in a timely manner I understand Pro Image Software reserves the right  to cancel my account and delete my events, images, reports and any database  related items relating to my account. I understand my account may have storage  capacity limits and should I go over my storage capacity Pro Image Software  reserves the right to charge me for said overages unless I agree to upgrade my  account to an account with larger storage limits. These overages will not  exceed $2.00 per gigabyte of storage used and will be in increments of $2.00  per gigabyte of used storage for a months period. </p>
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
