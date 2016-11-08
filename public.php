<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
define('Allow Scripts',true);
define('PAGE',"Public");
require_once($r_path.'scripts/cart/ssl_paths.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_format_phone.php');
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_sql_processor.php');
require_once $r_path.'scripts/cart/encrypt.php';
require_once($r_path.'scripts/fnct_ImgeProcessor.php');

function FindCSS($CSSLink){
	global $CSS, $Template, $r_path;
	$path_parts = pathinfo($CSSLink);
	$path = $path_parts['basename'];
	$path_parts = pathinfo($Template);
	$path = $path_parts['dirname']."/".$path;
	$handle = fopen($r_path.$path, "r") or die("Failed Opening ".$r_path.$path);
	while (!feof($handle)) $CSS .= fread($handle, 8192);
	fclose($handle);
	return "";
}
function FindCSS2($StyleSheet){
	global $CSS;
	$CSS .= $StyleSheet;
	return "";
}
function cleanUpHTML($text) {
	$text = ereg_replace(" style=[^>]*","", $text);
	return ($text);
}

require_once($r_path.'includes/_PhotoInfo.php');
require_once($r_path.'includes/_Guestbook.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include $r_path.'includes/_metadata.php'; ?>
<link href="/css/Photographer.css" rel="stylesheet" type="text/css" />
<? if($newWindow == true){ ?>
<script type="text/javascript">
AEV_new_window("<? echo $GoTo; ?>","ProImageSoftware",null,null,null,null,null,null,null,null,true);
</script>
<? }?>
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
    <div id="TextLong">
      <h1>Public Events</h1>
      <div>
        <? 
			$custid = $getInfo[0]['cust_id'];
			$query_get_prot = "SELECT `event_id`, `event_date`, `event_name`, `event_num`, `image_tiny`, `image_id`, `image_folder`, `image_rotate`, `event_short` 
				FROM `photo_event`
				LEFT OUTER JOIN `photo_event_images`
					ON `photo_event_images`.`image_id` = `photo_event`.`event_image`
				WHERE `photo_event`.`cust_id` = '$custid'
					AND `event_use` = 'y'
					AND `event_public` = 'y'
				ORDER BY `event_date` DESC , `event_name` ASC";
			$get_prot = mysql_query($query_get_prot, $cp_connection) or die(mysql_error());
			$n=0; $a=0;
			while($row_get_prot = mysql_fetch_assoc($get_prot)){
				if($row_get_prot['image_folder'] == ""){
					$Event_id = $row_get_prot['event_id'];
					$query_get_image = "SELECT `photo_event_group`.*, `photo_event_images`.`image_id`, `photo_event_images`.`image_folder`, `photo_event_images`.`image_tiny`, `photo_event_images`.`image_rotate` 
							FROM `photo_event_group` 
							INNER JOIN `photo_event_images` 
							ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
							WHERE `photo_event_group`.`event_id` = '$Event_id' AND `group_use` = 'y' AND `image_active` = 'y'
							GROUP BY `photo_event_images`.`image_id`  LIMIT 0,1";
					$get_image = mysql_query($query_get_image, $cp_connection) or die(mysql_error());
					$row_get_image = mysql_fetch_assoc($get_image);
					$index = strrpos($row_get_image['image_folder'], "/");
					$Folder = substr($row_get_image['image_folder'],0,$index);
					$image = $row_get_image['image_tiny'];
				} else {
					$index = strrpos($row_get_prot['image_folder'], "/");
					$Folder = substr($row_get_prot['image_folder'],0,$index);	
					$image = $row_get_prot['image_tiny'];
				}
				$index = strrpos($Folder, "/");
				$Folder = substr($Folder,0,$index);
				
				$Imager = new ImageProcessor();
				$Imager->SetMaxSize(67108864);
				$Imager->File($photographerFolder.$Folder."/Large/".$image);
				if($Imager->ERROR != false){
					$ExtImage = false;
				} else {
					$ExtImage = true;
					$Imager->Kill();
					$Imager->CalcResize(120,120);
					$Imager->CalcRotate($row_get_prot['image_rotate']);
					$width = $Imager->CalcWidth[0];
					$height = $Imager->CalcHeight[0];
					$vspace = 0;
					if($height<120) $vspace = floor(120-$height)/2;
				} ?>
        <div id="EventList"><a href="#" onclick="javascript:document.getElementById('Launch_Form_<? echo $n; ?>').submit();">
          <? if($ExtImage){
				$data1 = array("id" => $row_get_prot['image_id'], "width" => 120, "height" => 120, "salt" => time() ); ?>
          <img src="/images/image.php?data=<? echo base64_encode(encrypt_data(serialize($data1))); ?>" width="<? echo $width; ?>" height="<? echo $height; ?>" <? if($vspace!=0) echo ' vspace="'.$vspace.'"';?> />
          <? } else { ?>
          <img src="/images/spacer.gif" width="50" height="50" <? echo ' vspace="'.floor((120-50)/2).'"';?> />
          <? } ?>
          </a>
          <form action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="Launch_Form_<? echo $n; ?>" id="Launch_Form_<? echo $n; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="Event_Code" id="Event_Code" value="<? echo $row_get_prot['event_num']; ?>" />
            <input type="hidden" name="Fullscreen" id="Fullscreen_<? echo $n; ?>" value="n" />
          </form>
          <h3><? echo substr($row_get_prot['event_date'],5,2)."/".substr($row_get_prot['event_date'],8,2)."/".substr($row_get_prot['event_date'],0,4)." - ".$row_get_prot['event_name'];?></h3>
          <p><? echo $row_get_prot['event_short']; ?></p>
          <p><a href="#" onclick="javascript:document.getElementById('Launch_Form_<? echo $n; ?>').submit();">Launch
            Event</a> </p>
        </div>
        <? $n++; $a++; if($a>=5){echo '<br clear="all" />'; $a=0; } } ?>
        <br clear="all"/>
      </div>
    </div>
    <br clear="all"/>
  </div>
</div>
<? include $r_path.'includes/_PhotoFooter.php'; ?>
</body>
</html>
