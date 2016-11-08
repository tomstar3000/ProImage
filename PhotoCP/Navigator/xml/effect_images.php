<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$data = unserialize($_GET['data']);
foreach($data as $k => $v) $data[$k] = trim(clean_variable($v,true));
$master = trim(clean_variable(decrypt_data(base64_decode($_GET['master'])),true));

$OEff = unserialize($_GET['eff']);
$Eff = array();
$AEff = array();
foreach($OEff as $v){ $keys = explode(".",$v); $Eff[$keys[0]] = trim(clean_variable($keys[1],true)); }
unset($OEff);
foreach($data as $v){
	if(isset($Eff['Rotate'])){
		$getImgEff = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getImgEff->mysql("SELECT `image_rotate` FROM `photo_event_images` WHERE `image_id` = '$v' AND `cust_id` = '$master';");
		$getImgEff = $getImgEff->Rows();
		
		$Rotate = intval($getImgEff[0]['image_rotate'])+intval($Eff['Rotate']);
		while($Rotate >= 360) $Rotate -= 360; while($Rotate <= -360) $Rotate += 360;
		if	 ($Rotate < 0) $Rotate = 360+$Rotate;
		$AEff[$v][] = " `image_rotate` = '".$Rotate."' ";	
	} else if(isset($Eff['ImgColor'])){	
		$AEff[$v][] = " `image_color_space` = '".$Eff['ImgColor']."' ";	
	}
}
unset($data);
unset($Eff);

foreach($AEff as $k => $values){
	$getRcrds = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getRcrds->mysql("UPDATE `photo_event_images` SET ".implode(",",$values)." WHERE `image_id` = '$k' AND `cust_id` = '$master';");
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<finished>true</finished>';
?>
