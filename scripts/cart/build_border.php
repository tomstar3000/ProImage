<?

require_once $r_path.'scripts/fnct_ImgeProcessor.php';

mysql_select_db($database_cp_connection, $cp_connection);
$query_get_info = "SELECT * FROM `photo_event_images` WHERE `image_id` = '$ImageId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$LImage =  mb_convert_encoding($row_get_info['image_large'],"UTF-8","HTML-ENTITIES");
$ORotate = $row_get_info['imgage_org_rotate'];
$Rotate = $row_get_info['image_rotate'];
$Schema = $row_get_info['image_color_space'];
if(defined("BorderPreview") && BorderPreview === true){
	$folder = mb_convert_encoding($row_get_info['image_folder'],"UTF-8","HTML-ENTITIES");
	$folder = substr($folder,0,-11);
	
	$OImage = $photographerFolder.$folder."/Large/".$LImage;
	$query_get_info = "SELECT `prod_thumb` AS `prod_image`, `prod_thumb2` AS `prod_image2`, `prod_name` FROM `prod_products` WHERE `prod_id` = '$BorderId'";
} else {
	$folder = mb_convert_encoding($row_get_info['image_folder'],"UTF-8","HTML-ENTITIES");
	
	$OImage = $photographerFolder.$folder.$LImage;
	$query_get_info = "SELECT `prod_image`, `prod_image2`, `prod_name` FROM `prod_products` WHERE `prod_id` = '$BorderId'";
}
$query_get_info = "SELECT `prod_image`, `prod_image2`, `prod_name` FROM `prod_products` WHERE `prod_id` = '$BorderId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);

if($Horz == "true"){
	$Border = $r_path.'images/products/'.$row_get_info['prod_image'];
} else {
	$Border = $r_path.'images/products/'.$row_get_info['prod_image2'];
}
// Start a new image
$Imager = new ImageProcessor();
if(defined("PERFORMANCE")) $Imager->SetPerformance(true);

$Imager->SetMaxSize(67108864);

$Imager->File($OImage);

// Load the border into another tempfile
$Imager->File($Border,false,$Imager->nextIndex(),"image/x-png");
$BoderIndx = $Imager->Indx;
// Save the height and width of our border

$BWidth = $Imager->OrigWidth[$BoderIndx];
$BHeight = $Imager->OrigHeight[$BoderIndx];
// Ratio for text based off loaded border size
$Ratio = $BWidth/$PBWidth;

// Height and width of the image based off the ratio from above
$NWidth = $PIWidth*$Ratio;
$NHeight = $PIHeight*$Ratio;

$Imager->ChangeIndex(0);

// Rotate Image based off the stored value in the database
if(intval($ORotate) > 0) $Imager->Rotate((intval($ORotate)*-1));
if(intval($Rotate) > 0) $Imager->Rotate((intval($Rotate)*-1));
else if($PIWidth > $PIHeight && $Imager->OrigHeight[0] > $Imager->OrigWidth[0])	$Imager->Rotate(-90);
else if($PIHeight > $PIWidth && $Imager->OrigWidth[0] > $Imager->OrigHeight[0])	$Imager->Rotate(90);

// Resize our image
$Imager->Resize($NWidth,$NHeight);

// Start x and y of were to crop the image to the overlay
$NX = ($PX*-1)*$Ratio;
$NY = ($PY*-1)*$Ratio;
// Crop our image
$Imager->Crop($BWidth,$BHeight,false,$NX,$NY);

// Set color space based off what the user ordered
if(isset($Filter) && $Filter == "BlackWhite") 	$Imager->Gray();
else if (isset($Filter) && $Filter == "Sepia") 	$Imager->Sepia();
// Set the color schema of the image based off the database
else if($Schema == 'b')	$Imager->Gray();
else if($Schema == 's')	$Imager->Sepia();

$Imager->Overlay(1,0);

if (@($feed = fopen($Fonts, "r"))){
	$data = fread($feed, filesize($Fonts));
	fclose($feed);
}
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
foreach($tree['FONTS']['FONT'] as $v){
	if($v['ATTRIBUTES']['ID'] == $Font){
		if($Bold == "true" && $Italic == "true"){
			$Font = $r_path.'fonts/'.$v['ATTRIBUTES']['BOLDITALIC'];
		} else if ($Bold == "true"){
			$Font = $r_path.'fonts/'.$v['ATTRIBUTES']['BOLD'];
		} else if ($Italic == "true"){
			$Font = $r_path.'fonts/'.$v['ATTRIBUTES']['ITALIC'];
		} else {
			$Font = $r_path.'fonts/'.$v['ATTRIBUTES']['FILE'];
		}
		break;
	}
}
$BorderText = explode("%0D",urlencode(urldecode($Text)));
$FSize = $OFSize*$Ratio*.77;
$FX = $TX*$Ratio;
$FY = $TY*$Ratio;

$Imager->TextBox($Color,$FSize,$Font,$BorderText,$FX,$FY);

if(defined("BORDERTYPE") && constant("BORDERTYPE") == "Review"){
	$Color2 = "FFFFFF";
	$Alpha2 = 50;
	$FAng2 = 45;
	$Font2 = $r_path.'fonts/arial.ttf';
	$Text2 = "SAMPLE";
	$FSize2 = 500;
	
	$Size = $Imager->calcText($FSize2, $Font2, $Text2);
	$FWidth = abs($Size[2]-$Size[0]);
	$FHeight = abs($Size[5]-$Size[3]);
	$FW1 = $FHeight*sin($FAng2);
	$FH1 = sqrt(pow($FHeight,2)-pow($FW1,2));
	$FH2 = $FWidth*sin($FAng2);
	$FW2 = sqrt(pow($FWidth,2)-pow($FH2,2));
	$FWidth = $FW1+$FW2;
	$FHeight = $FH1-$FH2;

	$CrntWidth = $Imager->OrigWidth[$Imager->Indx];
	$CrntHeight = $Imager->OrigHeight[$Imager->Indx];
	
	$FX2 = ($CrntWidth/2)-($FWidth/2);
	$FY2 = ($CrntHeight/2)-($FHeight/2);
	
	$Imager->TextBox($Color2,$FSize2,$Font2,array($Text2),$FX2,$FY2,$FAng2,$Alpha2);
	
	// Resize our image
	$Imager->Resize(765,620);
} else if(defined("BORDERTYPE") && constant("BORDERTYPE") == "Icon"){
	// Resize our image
	$Imager->Resize(195,195);
}
if(!defined("PROCESS")){
	$Imager->OutputBuffer();
	exit(0);
} ?>