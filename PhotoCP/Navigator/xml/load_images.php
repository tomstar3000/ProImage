<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";
$r_path = '../';
require_once $r_path.'includes/cp_connection.php';
require_once $r_path.'scripts/encrypt.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_date.php';
require_once $r_path.'../../scripts/fnct_ImgeProcessor.php';

$data = trim(clean_variable($_GET['data'],true));
$master = trim(clean_variable(decrypt_data(base64_decode($_GET['master'])),true));
$handle = trim(clean_variable(urldecode(base64_decode($_GET['handle'])),true));
$email = trim(clean_variable(urldecode(base64_decode($_GET['email'])),true));

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';

$getRcrds = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getRcrds->mysql("SELECT * FROM `photo_event_images` WHERE `group_id` = '$data' AND `cust_id` = '$master' AND `image_active` = 'y' ORDER BY `image_tiny` ASC;");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";

$StrLen = 20;

$getFavs = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getFavs->mysql("SELECT `fav_xml` FROM `photo_cust_favories` WHERE `fav_code` = '".$handle."' AND `fav_email` = '".$email."' AND `fav_occurance` = '2' ORDER BY `fav_date` DESC;");
if($getFavs -> TotalRows() > 0){ $getFavs = $getFavs->Rows();
	$getFavs = explode(".",$getFavs[0]['fav_xml']);
} else {$getFavs = array(); }

echo '<images>';
foreach($getRcrds->Rows() as $r){
	$Name = $r['image_tiny'];
	if(strlen($Name) > $StrLen){
		$index = strpos($Name,".");
		$type = substr($Name,($index+1));
		$Name = substr($Name,0,$index);
		$Subtract = ceil(strlen($Name)/2)-ceil((strlen($Name)-$StrLen+3+(strlen($type)+1))/2);
		$Name = substr($Name,0,$Subtract)."...".substr($Name,(-1*$Subtract)).".".$type;
	}
	$RootFolder = str_replace("&amp;","&",$r['image_folder']);
		
	$Folder = explode("/",$RootFolder); array_splice($Folder,-2,2,"Large"); $Folder = implode("/",$Folder);
	$Imager = new ImageProcessor();
	$Imager->SetMaxSize(67108864);
	$Imager->File($photographerFolder.$Folder."/".$r['image_tiny']);
	$Imager->Kill();
	$Imager->CalcResize(134,134);
	$Imager->CalcRotate($r['image_rotate']);
	$width = $Imager->CalcWidth[0];
	$height = $Imager->CalcHeight[0]; 
	
	$Folder2 = explode("/",$RootFolder); array_splice($Folder2,-2,2,"Large"); $Folder2 = implode("/",$Folder2);
	$Imager = new ImageProcessor();
	$Imager->SetMaxSize(67108864);
	$Imager->File($photographerFolder.$Folder2."/".$r['image_tiny']);
	$Imager->Kill();
	$Imager->CalcResize(630,630);
	$Imager->CalcRotate($r['image_rotate']);
	$width2 = $Imager->CalcWidth[0];
	$height2 = $Imager->CalcHeight[0];
	
	$getCvr = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getCvr->mysql("SELECT COUNT(`event_id`) AS `cover` FROM `photo_event` WHERE `cust_id` = '$master' AND `event_image` = '".$r['image_id']."';");
	$getCvr = $getCvr->Rows();
	
	$data1 = array("id" => $r['image_id'], "width" => 134, "height" => 134, 'salt' => time());
	$data2 = array("id" => $r['image_id'], "width" => 630, "height" => 630, 'salt' => time());
	
	echo '<image id="'.$r['image_id'].'" shortname="'.$Name.'" name="'.$r['image_tiny'].'" size="'.$r['image_size'].'" date="'.format_date(date("Y-m-d H:i:s",$r['image_time']),"Short",false,true,false).'" path="/images/image.php?data='.base64_encode(encrypt_data(serialize($data1))).'" width="'.ceil($width).'" height="'.ceil($height).'" path2="/images/image.php?data='.base64_encode(encrypt_data(serialize($data2))).'" width2="'.ceil($width2).'" height2="'.ceil($height2).'" cover="'.((intval($getCvr[0]['cover'])>0)?'y':'n').'" fav="'.((in_array($r['image_id'],$getFavs))?'y':'n').'"></image>';
}
echo '
</images>';
?>
