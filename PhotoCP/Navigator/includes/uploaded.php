<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
if(isset($_GET['data'])){
	$data = unserialize(base64_decode(urldecode($_GET['data'])));
	foreach($data as $k => $v) $$k = $v;
}
$fid = trim(clean_variable(decrypt_data(base64_decode($_GET['fid'])),true));
$eid = trim(clean_variable(decrypt_data(base64_decode($_GET['event'])),true));
$time = trim(clean_variable($_GET['time'],true)); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Navigator</title>
<link href="<? echo $NavFolder; ?>/css/stylesheet.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<h1 id="HdrType3" class="EvntInfo">
  <div>Recently Uploaded Images</div>
</h1>
<div id="HdrLinks4"> <a href="<? echo $NavFolder; ?>/includes/index.php?data=<? echo $_GET['data']; ?>" class="BtnLoadBack" title="Back to organizer">Back to organizer</a> </div>
<div id="RecordTable" class="Black">
  <div id="Top"></div>
  <div id="Records">
    <? $getNewImgs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
			$getNewImgs->mysql("SELECT `image_folder`,`image_tiny`,`image_name`
					FROM `photo_event_images`
					INNER JOIN `photo_event_group` 
						ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
					WHERE `photo_event_images`.`image_time` >=  '".$time."'
						AND `photo_event_group`.`event_id` = '".$eid."'
					ORDER BY `image_tiny` ASC;");
			$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; ?>
    <div id="NewImages">
      <? foreach($getNewImgs->Rows() as $r){ ?>
      <div id="FavImg">
        <p>
          <? $Name = $r['image_tiny']; 
						if(strlen($Name) > 20){
							$index = strpos($Name,".");
							$type = substr($Name,($index+1));
							$Name = substr($Name,0,$index);
							$Subtract = ceil(strlen($Name)/2)-ceil((strlen($Name)-$StrLen+3+(strlen($type)+1))/2);
							$Name = substr($Name,0,$Subtract)."...".substr($Name,(-1*$Subtract)).".".$type;
						} echo $Name;
						$Folder = explode("/",$r['image_folder']); array_splice($Folder,-2,2,"Icon"); $Folder = implode("/",$Folder);
						list($width,$height) = @getimagesize($r_path.$Folder."/".$r['image_tiny']);
						if($width > $height){ @$rat = 134/$width; $height = $height*$rat; $width = 134; }
						else { @$rat = 134/$height; $width = $width*$rat; $height = 134; }
						$MRGN = round((134-$height)/2)+10;  ?>
          <img src="/<? echo $Folder."/".$r['image_tiny']; ?>" width="<? echo round($width); ?>" height="<? echo round($height); ?>" vspace="<? echo $MRGN; ?>" /><br />
        </p>
      </div>
      <? } ?>
    </div>
  </div>
  <div id="Bottom"></div>
</div>
<br clear="all" />
</body>
</html>
