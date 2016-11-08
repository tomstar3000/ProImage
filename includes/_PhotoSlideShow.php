<? if($HasFavs==true){
	if(!isset($CvrClass)) $CvrClass=''; ?>

<div id="FlashImg<? echo $CvrClass; ?>">
  <script type="text/javascript">
	AC_FL_RunContent( 'codebase','http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0','name','Photographer_SlideShow','width','520','height','350','align','middle','id','Photographer_SlideShow','src','/flash/Photo_SlideShow_1_2?code=<? echo rawurlencode($code); ?>&photographer=<? echo $handle; ?>&email=<? echo $Email; ?>&eventId=<? echo $Event_id; ?>','quality','high','bgcolor','#000000','allowscriptaccess','sameDomain','pluginspage','http://www.macromedia.com/go/getflashplayer','wmode','transparent','movie','/flash/Photo_SlideShow_1_2?code=<? echo rawurlencode($code); ?>&photographer=<? echo $handle; ?>&email=<? echo $Email; ?>&eventId=<? echo $Event_id; ?>' ); //end AC code
	</script>
  <noscript>
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" name="Photographer_SlideShow" width="520" height="350" align="middle" id="Photographer_SlideShow">
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="movie" value="/flash/Photo_SlideShow_1_2.swf?code=<? echo rawurlencode($code); ?>&photographer=<? echo $handle; ?>&email=<? echo $Email; ?>&eventId=<? echo $Event_id; ?>" />
    <param name="quality" value="high" />
    <param name="bgcolor" value="#000000" />
    <param name="wmode" value="transparent" />
    <embed src="/flash/Photo_SlideShow_1_2.swf?code=<? echo rawurlencode($code); ?>&photographer=<? echo $handle; ?>&email=<? echo $Email; ?>&eventId=<? echo $Event_id; ?>" width="520" height="350" align="middle" quality="high" bgcolor="#000000" name="Photographer_SlideShow" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="transparent" />
  </object>
  </noscript>
</div>
<? } else if(intval($getCode[0]['image_id']) != 0){
		$data1 = array("id" => $getCode[0]['image_id'], "width" => 500, "height" => 330, "Master"=>true); ?>
<div id="CoverImg<? echo $CvrClass; ?>"><img src="/images/image.php?data=<? echo base64_encode(encrypt_data(serialize($data1))).'&t='.time(); ?>" height="350" /></div>
<? } else {
	$getOne = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getOne->mysql("SELECT `image_id`, `image_folder`
									FROM `photo_event`
									INNER JOIN `cust_customers` 
											ON `cust_customers`.`cust_id` = `photo_event`.`cust_id` 
									INNER JOIN `photo_event_group`
										ON `photo_event_group`.`event_id` = `photo_event`.`event_id`
									INNER JOIN `photo_event_images`
										ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
									WHERE `event_num` LIKE '%$code%' 
										AND `cust_handle` = '$handle' 
										AND `event_use` = 'y' LIMIT 0,1;");
	if($getOne->TotalRows() > 0){
			$getOne = $getOne->Rows();
			$data1 = array("id" => $getOne[0]['image_id'], "width" => 500, "height" => 330, "Master"=>true); ?>
<div id="CoverImg<? echo $CvrClass; ?>"><img src="/images/image.php?data=<? echo base64_encode(encrypt_data(serialize($data1))).'&t='.time(); ?>" height="350" /></div>
<? } else { ?>
<div id="CoverImg<? echo $CvrClass; ?>"><img src="/images/spacer.gif" width="500" height="330" /></div>
<? } } ?>
