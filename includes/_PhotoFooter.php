<div id="Footer">
  <? $Company = ($getInfo[0]['cust_fcname']!="")?$getInfo[0]['cust_fcname']:$getInfo[0]['cust_cname'];
	$Email = ($getInfo[0]['cust_femail']!="")?$getInfo[0]['cust_femail']:$getInfo[0]['cust_email'];
	$Work = ($getInfo[0]['cust_fwork']!="0")?$getInfo[0]['cust_fwork']:$getInfo[0]['cust_work'];
	$Ext = ($getInfo[0]['cust_fext']!="0")?$getInfo[0]['cust_fext']:$getInfo[0]['cust_ext'];
	$Icon = $getInfo[0]['cust_icon']; ?>
  <p><strong><? echo $Company;?></strong><br />
    <a href="mailto:<? echo $Email;?>" class="Footer_Nav"><? echo $Email;?></a><br />
    <? if($Work!="0"){echo phone_number($Work); if($Ext!="0")echo " Ext. ".$Ext; }?>
    <br />
    <a href="mailto:support@proimagesoftware.com" class="Footer_Nav">support@proimagesoftware.com</a></p>
  <? if($Icon != "" && is_file($photographerFolder."photographers/".$handle.'/'.$Icon)){ 
  	list($width) = getimagesize($photographerFolder."photographers/".$handle.'/'.$Icon);
	$width = ($width<750)?$width:750;
	$data = array(
		'imagetype' => 'footer',
		'image' => $handle.'/'.$Icon,
		'width' => $width,
		'height' => 50,
		'salt' => time(),
	);
	require_once $r_path.'scripts/cart/encrypt.php';
	$data = base64_encode(encrypt_data(serialize($data)));
  ?>
  <img src="/images/image.php?data=<? echo $data; ?>" height="50" width="<? echo $width; ?>"/>
  <? } ?>
</div>
