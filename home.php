<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define('Allow Scripts',true);
define('PAGE',"Home");
require_once($r_path.'scripts/cart/ssl_paths.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');

require_once($r_path.'includes/_PhotoInfo.php');
require_once($r_path.'includes/_Guestbook.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<? if($launch_full === true){ ?>
<script type="text/JavaScript">
AEV_new_window("<? echo $GoTo; ?>","ProImageSoftware",null,null,null,null,null,null,null,null,true);
</script>
<? } ?>
</head>
<body>
<div id="Container">
  <div id="Logo">
    <? if($getInfo[0]['cust_image'] !="" ){
		$data = array(
			'imagetype' => 'logo',
			'image' => $handle.'/'.$getInfo[0]['cust_image'],
			'width' => 998,
			'height' => 387,
			'salt' => time(),
		);
		require_once $r_path.'scripts/cart/encrypt.php';
		$data = base64_encode(encrypt_data(serialize($data)));
		echo '<img src="/images/image.php?data='.$data.'"  />'; } else { ?>
    <img src="/images/spacer.gif" width="998" height="387" />
    <? } ?>
  </div>
  <div id="Content">
    <? include $r_path.'includes/_PhotoNavigation.php'; ?>
    <div id="Text">
      <h1><? echo $getInfo[0]['cust_cname']; ?></h1>
      <p><? echo $getInfo[0]['cust_desc']; ?></p>
    </div>
    <div id="Event">
      <h2>Enter Event Code</h2>
      <form method="post" name="Event_Code_Form" id="Event_Code_Form" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" enctype="multipart/form-data">
        <div id="CstmFld">
          <input name="Event Code" id="Event_Code" type="text" value="enter code" title="Event Code" onclick="javascript:this.value=''" />
        </div>
        <div id="CstmSend">
          <input type="submit" name="Submit" id="Submit" title="Submit" onclick="MM_validateForm('Event_Code','','R'); return document.MM_returnValue;" />
        </div>
      </form>
    </div>
    <br clear="all" />
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
