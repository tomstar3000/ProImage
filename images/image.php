<? 
header("Cache-Control: private");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: private"); 

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";
define('Allow Scripts',true);

require_once $r_path.'Connections/cp_connection.php';
require_once $r_path.'scripts/fnct_sql_processor.php';
require_once $r_path.'scripts/cart/encrypt.php';
require_once $r_path.'scripts/fnct_ImgeProcessor.php';

// $data = array("id" => 100118, "width" => 630, "height" => 630);
// $data = base64_encode(encrypt_data(serialize($data)));
// $_GET['data'] = $data;

$data = unserialize(decrypt_data(base64_decode(trim($_GET['data']))));

if(isset($data['imagetype'])){
	switch(	$data['imagetype']){
		case 'footer':
		case 'logo':
			$FilePath = $photographerFolder."photographers/".$data['image'];
			$Imager = new ImageProcessor();
			$Imager->SetMaxSize(67108864);
			$Imager->File($FilePath);
			$Imager->Crop($data['width'],$data['height'],'Center');
			$Imager->OutputBuffer();
			exit;
			break;
	}
}

$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getInfo->mysql("SELECT `image_folder`, `image_large`, `image_rotate`, `image_color_space` FROM `photo_event_images` WHERE `image_id` = '".$data['id']."';");
$getInfo = $getInfo->Rows();

$RootFolder = str_replace("&amp;","&",$getInfo[0]['image_folder']);

if(isset($data['Master']) && $data['Master'] == true) {
	$Folder = $RootFolder;
} else {
	$Folder = explode("/",$RootFolder); array_splice($Folder,-2,2,"Large"); $Folder = implode("/",$Folder);
}
$FilePath = $photographerFolder.str_replace("&","&amp;",$Folder)."/".mb_convert_encoding($getInfo[0]['image_large'],'UTF-8','HTML-ENTITIES');
//$FilePath = $r_path.$getInfo[0]['image_folder'].$getInfo[0]['image_large'];

$Imager = new ImageProcessor();
$Imager->SetMaxSize(67108864);
$Imager->File($FilePath);
$Bond = (isset($data['inbond']))?$data['inbond']:NULL;
$Imager->Resize($data['width'],$data['height'],(($Bond==NULL)?false:$Bond));
if(intval($getInfo[0]['image_rotate']) > 0) 			$Imager->Rotate((intval($getInfo[0]['image_rotate'])*-1));
if(isset($data['crop'])) 								$Imager->Crop($data['width'],$data['height'],$data['crop']);

if($getInfo[0]['image_color_space'] == 'b')				$Imager->Gray();
else if($getInfo[0]['image_color_space'] == 's')		$Imager->Sepia();
$Imager->OutputBuffer(); ?>
