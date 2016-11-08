<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_date_format_for_db.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$PId = $path[2];
$PNumber = (isset($_POST['Product_Number'])) ? clean_variable($_POST['Product_Number'],true) : '';
$PSerial = (isset($_POST['Serial_Number'])) ? clean_variable($_POST['Serial_Number'],true) : '';
$PName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$PDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$PSDesc = (isset($_POST['Short_Description'])) ? clean_variable($_POST['Short_Description']) : '';
$PAvail = (isset($_POST['Availability'])) ? $_POST['Availability'] : '2';
$PShips = (isset($_POST['Ships_In'])) ? $_POST['Ships_In'] : '1';
if(isset($_POST['Category'])){
	$PCats = $_POST['Category'];
	$Count = count($PCats);
	$PCat = $PCats[$Count-1];
} else {
	$PCat = '0';
}
$PSel_Cal = (isset($_POST['Sel_Cat'])) ? $_POST['Sel_Cat'] : '0';
if(isset($_POST['Category_2'])){
	$PCats_2 = $_POST['Category_2'];
	$Count = count($PCats_2);
	$PCat_2 = $PCats_2[$Count-1];
} else {
	$PCat_2 = '0';
}
$PSel_Cal_2 = (isset($_POST['Sel_Cat_2'])) ? $_POST['Sel_Cat_2'] : '0';
if(isset($_POST['Category_3'])){
	$PCats_3 = $_POST['Category_3'];
	$Count = count($PCats_3);
	$PCat_3 = $PCats_3[$Count-1];
} else {
	$PCat_3 = '0';
}
$PSel_Cal_3 = (isset($_POST['Sel_Cat_3'])) ? $_POST['Sel_Cat_3'] : '0';
$PSel = (isset($_POST['Seller'])) ? clean_variable($_POST['Seller'],true) : '0';
$PLoc = (isset($_POST['Location'])) ? clean_variable($_POST['Location'],true) : '0';
$PBank = (isset($_POST['Bank'])) ? clean_variable($_POST['Bank'],true) : '';
$PWhole = (isset($_POST['Whole_Price'])) ? clean_variable($_POST['Whole_Price'],true) : '';
$PPrice = (isset($_POST['Price'])) ? clean_variable($_POST['Price'],true) : '';
$PSale = (isset($_POST['Sale_Price'])) ? clean_variable($_POST['Sale_Price'],true) : '';
$PSaleExp = (isset($_POST['Sale_Experation'])) ? clean_variable($_POST['Sale_Experation'],true) : '';
$PFee = (isset($_POST['Fee'])) ? clean_variable($_POST['Fee'],true) : '';
$PFreight = (isset($_POST['Freight'])) ? clean_variable($_POST['Freight'],true) : '';
$PUFreight = (isset($_POST['Use_Freight'])) ? clean_variable($_POST['Use_Freight'],true) : 'n';
$PSpecDel = (isset($_POST['Special_Delivery'])) ? $_POST['Special_Delivery'] : '0';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$Thumb = "";
$Thumbv = (isset($_POST['Thumb_val'])) ? clean_variable($_POST['Thumb_val'],true) : '';
$Icon = "";
$Iconv = (isset($_POST['Icon_val'])) ? clean_variable($_POST['Icon_val'],true) : '';
$PHeight = (isset($_POST['Height'])) ? clean_variable($_POST['Height'],true) : '';
$PWidth = (isset($_POST['Width'])) ? clean_variable($_POST['Width'],true) : '';
$PLength = (isset($_POST['Length'])) ? clean_variable($_POST['Length'],true) : '';
$PWeight = (isset($_POST['Weight'])) ? clean_variable($_POST['Weight'],true) : '';
$PQty = (isset($_POST['Quantity'])) ? clean_variable($_POST['Quantity'],true) : '';
$PDiscon = (isset($_POST['Discontinue'])) ? clean_variable($_POST['Discontinue'],true) : '';
$PUseQty = (isset($_POST['Use_Quantity'])) ? clean_variable($_POST['Use_Quantity'],true) : 'y';
$PUseAtt = 'n';
$PFeatures = 'n';
$PRecure = (isset($_POST['Reoccurring_Billing'])) ? clean_variable($_POST['Reoccurring_Billing'],true) : 'y';
$PRecYear = (isset($_POST['Reoccurring_Year'])) ? clean_variable($_POST['Reoccurring_Year'],true) : 'n';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Folder, $Prod_IWidth, $Prod_IHeight, $Prod_ICrop, $Prod_IResize);
	} else {
		$Image = array();
		if($Prod_IReq){
			$Image[0] = false;
			$Image[1] = "Zoom Image is Required";
		} else {
			$Image[0] = true;
			$Image[1] = $Imagev;
		}
	}
	if (is_uploaded_file($_FILES['Thumb']['tmp_name'])){
		$Fname = $_FILES['Thumb']['name'];
		$Iname = format_file_name($Fname,"t");
		$ISize = $_FILES['Thumb']['size'];
		$ITemp = $_FILES['Thumb']['tmp_name'];
		$IType = $_FILES['Thumb']['type'];
		
		$Thumb = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Folder, $Prod_TWidth, $Prod_THeight, $Prod_TCrop, $Prod_TResize);
	} else {
		$Thumb = array();
		if($Prod_TReq){
			$Thumb[0] = false;
			$Thumb[1] = "Large Image is Required";
		} else {
			$Thumb[0] = true;
			$Thumb[1] = $Thumbv;
		}
	}
	if (is_uploaded_file($_FILES['Icon']['tmp_name'])){
		$Fname = $_FILES['Icon']['name'];
		$Iname = format_file_name($Fname,"ic");
		$ISize = $_FILES['Icon']['size'];
		$ITemp = $_FILES['Icon']['tmp_name'];
		$IType = $_FILES['Icon']['type'];
		
		$Icon = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Prod_Folder, $Prod_IcWidth, $Prod_IcHeight, $Prod_IcCrop, $Prod_IcResize);
	} else {
		$Icon = array();
		if($Prod_IcReq){
			$Icon[0] = false;
			$Icon[1] = "Icon Image is Required";;
		} else {
			$Icon[0] = true;
			$Icon[1] = $Iconv;
		}
	}
	if($Image[0] && $Thumb[0] && $Icon[0]){
		$Image = $Image[1];
		$Thumb = $Thumb[1];
		$Icon = $Icon[1];
		if($_POST['Remove_Image'] == "true"){
			$Image = "";
		}
		if($_POST['Remove_Thumb'] == "true"){
			$Thumb = "";
		}
		if($_POST['Remove_Icon'] == "true"){
			$Icon = "";
		}
		if($PCat <= 0){
			$n = count($PCats);
			do{	
				$n--;
				$PCats_db = $PCats[$n];
			} while($PCats[$n] <= 0 && $n>=0);
		} else {
			$PCats_db = $PCat;
		}
		if($PCat_2 <= 0){
			$n = count($PCats_2);
			do{	
				$n--;
				$PCats_2_db = $PCats_2[$n];
			} while($PCats_2[$n] <= 0 && $n>=0);
		} else {
			$PCats_2_db = $PCat_2;
		}
		if($PCat_3 <= 0){
			$n = count($PCats_3);
			do{	
				$n--;
				$PCats_3_db = $PCats_3[$n];
			} while($PCats_3[$n] <= 0 && $n>=0);
		} else {
			$PCats_3_db = $PCat_3;
		}
		$text = clean_variable($PDesc,'Store');
		$text2 = clean_variable($PSDesc,'Store');
		if($cont == "add"){
			$add = "INSERT INTO `prod_products` (`prod_number`,`prod_serial`,`prod_name`,`prod_desc`,`prod_short`,`availability_id`,`ships_in_id`,`cat_id`,`cat_2_id`,`cat_3_id`,`sell_id`,`loc_id`,`bank_id`,`prod_whole`,`prod_price`,`prod_sale`,`prod_sale_exp`,`prod_fee`,`prod_freight`,`prod_use_freight`,`spec_del_id`,`prod_image`,`prod_thumb`,`prod_tiny`,`prod_height`,`prod_width`,`prod_length`,`prod_weight`,`prod_qty`,`prod_added`,`prod_discontinue`,`prod_use_qty`,`prod_ues_att_qty`,`prod_featured_item`,`prod_service`,`prod_recur`,`prod_year`, `prod_updated`) VALUES ('$PNumber','$PSerial','$PName','$text','$text2','$PAvail','$PShips','$PCats_db','$PCats_2_db','$PCats_3_db','$PSel','$PLoc','$PBank','$PWhole','$PPrice','$PSale','$PSaleExp','$PFee','$PFreight','$PUFreight','$PSpecDel','$Image','$Thumb','$Icon','$PHeight','$PWidth','$PLength','$PWeight','$PQty',NOW(),'$PDiscon','$PUseQty','$PUseAtt','$PFeatures','$PRecure','y','$PRecYear', NOW());";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_products` SET `prod_number` = '$PNumber',`prod_serial` = '$PSerial',`prod_name` = '$PName',`prod_desc` = '$text',`prod_short` = '$text2',`availability_id` = '$PAvail',`ships_in_id` = '$PShips',`cat_id` = '$PCats_db',`cat_2_id` = '$PCats_2_db',`cat_3_id` = '$PCats_3_db',`sell_id` = '$PSel',`loc_id` = '$PLoc',`bank_id` = '$PBank',`prod_whole` = '$PWhole',`prod_price` = '$PPrice',`prod_sale` = '$PSale',`prod_sale_exp` = '$PSaleExp',`prod_fee` = '$PFee',`prod_freight` = '$PFreight',`prod_use_freight` = '$PUFreight',`spec_del_id` = '$PSpecDel',`prod_image` = '$Image',`prod_thumb` = '$Thumb',`prod_tiny` = '$Icon',`prod_height` = '$PHeight',`prod_width` = '$PWidth',`prod_length` = '$PLength',`prod_weight` = '$PWeight',`prod_qty` = '$PQty',`prod_discontinue` = '$PDiscon',`prod_use_qty` = '$PUseQty',`prod_ues_att_qty` = '$PUseAtt',`prod_featured_item` = '$PFeatures',`prod_recur` = '$PRecure',`prod_year` = '$PRecYear', `prod_updated` = NOW() WHERE `prod_id` = '$PId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
		}
		$cont = "view";
	} else {
		$message = "";
		if($Image[0]){
			$message += $Image[1]+"\n";
		}
		if($Thumb[0]){
			$message += $Thumb[1]+"\n";
		}
		if($Icon[0]){
			$message += $Icon[1];
		}
		$Image = $Image[1];
		$Thumb = $Thumb[1];
		$Icon = $Icon[1];
	}
} else {
	if($cont != "add"){		
		$query_get_info = "SELECT * FROM `prod_products` LEFT OUTER JOIN `prod_availability` ON `prod_availability`.`availability_id` = `prod_products`.`availability_id` WHERE `prod_id` = '$PId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$PId = $row_get_info['prod_id'];
		$PNumber = $row_get_info['prod_number'];
		$PSerial = $row_get_info['prod_serial'];
		$PName = $row_get_info['prod_name'];
		$PDate = $row_get_info['prod_updated'];
		$PDesc = $row_get_info['prod_desc'];
		$PSDesc = $row_get_info['prod_short'];
		$PAvail = $row_get_info['availability_id'];
		$PShips = $row_get_info['ships_in_id'];
		if(isset($_POST['Category'])){
			$PCats = $_POST['Category'];
			$Count = count($PCats);
			$PCat = $PCats[$Count-1];
		} else {
			$PCat = $row_get_info['cat_id'];
		}
		$PSel_Cal = (isset($_POST['Sel_Cat'])) ? $_POST['Sel_Cat'] : '0';
		if(isset($_POST['Category_2'])){
			$PCats_2 = $_POST['Category_2'];
			$Count = count($PCats_2);
			$PCat_2 = $PCats_2[$Count-1];
		} else {
			$PCat_2 = $row_get_info['cat_2_id'];
		}
		$PSel_Cal_2 = (isset($_POST['Sel_Cat_2'])) ? $_POST['Sel_Cat_2'] : '0';
		if(isset($_POST['Category_3'])){
			$PCats_3 = $_POST['Category_3'];
			$Count = count($PCats_3);
			$PCat_3 = $PCats_3[$Count-1];
		} else {
			$PCat_3 = $row_get_info['cat_3_id'];
		}
		$PSel_Cal_3 = (isset($_POST['Sel_Cat_3'])) ? $_POST['Sel_Cat_3'] : '0';
		$PSel = $row_get_info['sell_id'];
		$PLoc = $row_get_info['loc_id'];
		$PBank = $row_get_info['bank_id'];
		$PWhole = $row_get_info['prod_whole'];
		$PPrice = $row_get_info['prod_price'];
		$PSale = $row_get_info['prod_sale'];
		$PSaleExp = $row_get_info['prod_sale_exp'];
		if($PSaleExp == "0000-00-00 00:00:00"){
			$PSaleExp = "";
		} else {
			$PSaleExp = format_date($PSaleExp, "Short", "Standard", true, true);
		}
		$PFee = $row_get_info['prod_fee'];
		$PFreight = $row_get_info['prod_freight'];
		$PUFreight = $row_get_info['prod_use_freight'];
		$PSpecDel = $row_get_info['spec_del_id'];
		$Image = "";
		$Imagev = $row_get_info['prod_image'];
		$Thumb = "";
		$Thumbv = $row_get_info['prod_thumb'];
		$Icon = "";
		$Iconv = $row_get_info['prod_tiny'];
		$PHeight = $row_get_info['prod_height'];
		$PWidth = $row_get_info['prod_width'];
		$PLength = $row_get_info['prod_length'];
		$PWeight = $row_get_info['prod_weight'];
		$PQty = $row_get_info['prod_qty'];
		$PDiscon = $row_get_info['prod_discontinue'];
		if($PDiscon == "0000-00-00 00:00:00"){
			$PDiscon = "";
		} else {
			$PDiscon = format_date($PSaleExp, "Short", "Standard", true, true);
		}
		$PUseQty = $row_get_info['prod_use_qty'];
		$PUseAtt = $row_get_info['prod_ues_att_qty'];
		$PFeatures = $row_get_info['prod_featured_item'];
		$PRecure = $row_get_info['prod_recur'];
		$PRecYear = $row_get_info['prod_year'];
		
		mysql_free_result($get_info);
	}
}
?>