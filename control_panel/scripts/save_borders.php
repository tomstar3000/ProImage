<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
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
$HImage = "";
$HImagev = (isset($_POST['HImage_val'])) ? clean_variable($_POST['HImage_val'],true) : '';
$HThumb = "";
$HThumbv = (isset($_POST['HThumb_val'])) ? clean_variable($_POST['HThumb_val'],true) : '';
$VImage = "";
$VImagev = (isset($_POST['VImage_val'])) ? clean_variable($_POST['VImage_val'],true) : '';
$VThumb = "";
$VThumbv = (isset($_POST['VThumb_val'])) ? clean_variable($_POST['VThumb_val'],true) : '';
$Icon = "";
$Iconv = (isset($_POST['Icon_val'])) ? clean_variable($_POST['Icon_val'],true) : '';
$PHeight = (isset($_POST['Height'])) ? clean_variable($_POST['Height'],true) : '';
$PWidth = (isset($_POST['Width'])) ? clean_variable($_POST['Width'],true) : '';
$PLength = (isset($_POST['Length'])) ? clean_variable($_POST['Length'],true) : '';
$PWeight = (isset($_POST['Weight'])) ? clean_variable($_POST['Weight'],true) : '';
$PRecWidth = (isset($_POST['Recomended_Width'])) ? clean_variable($_POST['Recomended_Width'],true) : '';
$PRecHeight = (isset($_POST['Recomended_Height'])) ? clean_variable($_POST['Recomended_Height'],true) : '';
$PRecRes = (isset($_POST['Recomended_Resolution'])) ? clean_variable($_POST['Recomended_Resolution'],true) : '';
$PQty = (isset($_POST['Quantity'])) ? clean_variable($_POST['Quantity'],true) : '';
$PBorder = (isset($_POST['Allow_Border'])) ? clean_variable($_POST['Allow_Border'],true) : 'n';
$PDiscon = (isset($_POST['Discontinue'])) ? clean_variable($_POST['Discontinue'],true) : '';
$PUseQty = (isset($_POST['Use_Quantity'])) ? clean_variable($_POST['Use_Quantity'],true) : 'y';
$PUseAtt = (isset($_POST['Use_Attribute_Quantity'])) ? clean_variable($_POST['Use_Attribute_Quantity'],true) : 'n';
$PFeatures = (isset($_POST['Featured_Item'])) ? clean_variable($_POST['Featured_Item'],true) : 'n';
$PRecure = 'n';
$PRecYear = 'n';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($use_ftp === true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	} 
	if (is_uploaded_file($_FILES['HImage']['tmp_name'])){
		$Fname = $_FILES['HImage']['name'];
		$Iname = format_file_name($Fname,"BHi");
		$ISize = $_FILES['HImage']['size'];
		$ITemp = $_FILES['HImage']['tmp_name'];
		$IType = $_FILES['HImage']['type'];
		
		$HImage = array();
		$HImage[0] = true;
		$HImage[1] = $Iname;
		if($use_ftp === true){
			ftp_put($conn_id, realpath($Prod_Folder)."/".$Iname, $ITemp, FTP_BINARY);
		} else {
			copy($ITemp, $Prod_Folder."/".$Iname);
		}
	} else {
		$HImage = array();
		if($Border_HIReq){
			$HImage[0] = false;
			$HImage[1] = "Horizontal Print is Required";
		} else {
			$HImage[0] = true;
			$HImage[1] = $HImagev;
		}
	}
	if (is_uploaded_file($_FILES['HThumb']['tmp_name'])){
		$Fname = $_FILES['HThumb']['name'];
		$Iname = format_file_name($Fname,"BHt");
		$ISize = $_FILES['HThumb']['size'];
		$ITemp = $_FILES['HThumb']['tmp_name'];
		$IType = $_FILES['HThumb']['type'];
		
		$HThumb = array();
		$HThumb[0] = true;
		$HThumb[1] = $Iname;
		if($use_ftp === true){
			ftp_put($conn_id, realpath($Prod_Folder)."/".$Iname, $ITemp, FTP_BINARY);
		} else {
			copy($ITemp, $Prod_Folder."/".$Iname);
		}
	} else {
		$HThumb = array();
		if($Border_HTReq){
			$HThumb[0] = false;
			$HThumb[1] = "Horizontal Thumbnail is Required";
		} else {
			$HThumb[0] = true;
			$HThumb[1] = $HThumbv;
		}
	}
	if (is_uploaded_file($_FILES['VImage']['tmp_name'])){
		$Fname = $_FILES['VImage']['name'];
		$Iname = format_file_name($Fname,"BVi");
		$ISize = $_FILES['VImage']['size'];
		$ITemp = $_FILES['VImage']['tmp_name'];
		$IType = $_FILES['VImage']['type'];
		
		$VImage = array();
		$VImage[0] = true;
		$VImage[1] = $Iname;
		if($use_ftp === true){
			ftp_put($conn_id, realpath($Prod_Folder)."/".$Iname, $ITemp, FTP_BINARY);
		} else {
			copy($ITemp, $Prod_Folder."/".$Iname);
		}
	} else {
		$VImage = array();
		if($Border_VIReq){
			$VImage[0] = false;
			$VImage[1] = "Vertical Print is Required";
		} else {
			$VImage[0] = true;
			$VImage[1] = $VImagev;
		}
	}
	if (is_uploaded_file($_FILES['VThumb']['tmp_name'])){
		$Fname = $_FILES['VThumb']['name'];
		$Iname = format_file_name($Fname,"BHt");
		$ISize = $_FILES['VThumb']['size'];
		$ITemp = $_FILES['VThumb']['tmp_name'];
		$IType = $_FILES['VThumb']['type'];
		
		$VThumb = array();
		$VThumb[0] = true;
		$VThumb[1] = $Iname;
		if($use_ftp === true){
			ftp_put($conn_id, realpath($Prod_Folder)."/".$Iname, $ITemp, FTP_BINARY);
		} else {
			copy($ITemp, $Prod_Folder."/".$Iname);
		}
	} else {
		$VThumb = array();
		if($Border_VTReq){
			$VThumb[0] = false;
			$VThumb[1] = "Vertical Thumbnail is Required";
		} else {
			$VThumb[0] = true;
			$VThumb[1] = $VThumbv;
		}
	}
	if (is_uploaded_file($_FILES['Icon']['tmp_name'])){
		$Fname = $_FILES['Icon']['name'];
		$Iname = format_file_name($Fname,"Bic");
		$ISize = $_FILES['Icon']['size'];
		$ITemp = $_FILES['Icon']['tmp_name'];
		$IType = $_FILES['Icon']['type'];
		
		$Icon = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, realpath($Prod_Folder), $Border_IcWidth, $Border_IcHeight, $Border_IcCrop, $Border_IcResize, true, $conn_id);
	} else {
		$Icon = array();
		if($Prod_IcReq){
			$Icon[0] = false;
			$Icon[1] = "Icon Image is Required";
		} else {
			$Icon[0] = true;
			$Icon[1] = $Iconv;
		}
	}
	if($HImage[0] && $HThumb[0] && $VImage[0] && $VThumb[0] && $Icon[0]){
		$HImage = $HImage[1];
		$HThumb = $HThumb[1];
		$VImage = $VImage[1];
		$VThumb = $VThumb[1];
		$Icon = $Icon[1];
		if($_POST['Remove_HImage'] == "true"){
			$HImage = "";
		}
		if($_POST['Remove_HThumb'] == "true"){
			$HThumb = "";
		}
		if($_POST['Remove_VImage'] == "true"){
			$VImage = "";
		}
		if($_POST['Remove_VThumb'] == "true"){
			$VThumb = "";
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
			$add = "INSERT INTO `prod_products` (`prod_number`,`prod_serial`,`prod_name`,`prod_desc`,`prod_short`,`availability_id`,`ships_in_id`,`cat_id`,`cat_2_id`,`cat_3_id`,`sell_id`,`loc_id`,`bank_id`,`prod_whole`,`prod_price`,`prod_sale`,`prod_sale_exp`,`prod_fee`,`prod_freight`,`prod_use_freight`,`spec_del_id`,`prod_image`,`prod_thumb`,`prod_image2`,`prod_thumb2`,`prod_tiny`,`prod_height`,`prod_width`,`prod_length`,`prod_weight`,`prod_rec_width`,`prod_rec_height`,`prod_rec_res`,`prod_qty`,`prod_border`,`prod_added`,`prod_discontinue`,`prod_use_qty`,`prod_ues_att_qty`,`prod_featured_item`,`prod_recur`,`prod_year`, `prod_updated`,`prod_service`) VALUES ('$PNumber','$PSerial','$PName','$text','$text2','$PAvail','$PShips','$PCats_db','$PCats_2_db','$PCats_3_db','$PSel','$PLoc','$PBank','$PWhole','$PPrice','$PSale','$PSaleExp','$PFee','$PFreight','$PUFreight','$PSpecDel','$HImage','$HThumb','$VImage','$VThumb','$Icon','$PHeight','$PWidth','$PLength','$PWeight','$PRecWidth','$PRecHeight','$PRecRes','$PQty','$PBorder',NOW(),'$PDiscon','$PUseQty','$PUseAtt','$PFeatures','$PRecure','$PRecYear', NOW(), 'b');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_products` SET `prod_number` = '$PNumber',`prod_serial` = '$PSerial',`prod_name` = '$PName',`prod_desc` = '$text',`prod_short` = '$text2',`availability_id` = '$PAvail',`ships_in_id` = '$PShips',`cat_id` = '$PCats_db',`cat_2_id` = '$PCats_2_db',`cat_3_id` = '$PCats_3_db',`sell_id` = '$PSel',`loc_id` = '$PLoc',`bank_id` = '$PBank',`prod_whole` = '$PWhole',`prod_price` = '$PPrice',`prod_sale` = '$PSale',`prod_sale_exp` = '$PSaleExp',`prod_fee` = '$PFee',`prod_freight` = '$PFreight',`prod_use_freight` = '$PUFreight',`spec_del_id` = '$PSpecDel',`prod_image` = '$HImage',`prod_thumb` = '$HThumb',`prod_image2` = '$VImage',`prod_thumb2` = '$VThumb',`prod_tiny` = '$Icon',`prod_height` = '$PHeight',`prod_width` = '$PWidth',`prod_length` = '$PLength',`prod_weight` = '$PWeight',`prod_rec_width` = '$PRecWidth',`prod_rec_height` = '$PRecHeight',`prod_rec_res` = '$PRecRes',`prod_qty` = '$PQty',`prod_border` = '$PBorder',`prod_discontinue` = '$PDiscon',`prod_use_qty` = '$PUseQty',`prod_ues_att_qty` = '$PUseAtt',`prod_featured_item` = '$PFeatures',`prod_recur` = '$PRecure',`prod_year` = '$PRecYear', `prod_updated` = NOW(), `prod_service` = 'b' WHERE `prod_id` = '$PId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());		
		}
		$cont = "view";
	} else {
		$message = "";
		if($HImage[0]){
			$message += $HImage[1]+"\n";
		}
		if($HThumb[0]){
			$message += $HThumb[1]+"\n";
		}
		if($VImage[0]){
			$message += $VImage[1]+"\n";
		}
		if($VThumb[0]){
			$message += $VThumb[1]+"\n";
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
		$HImage = "";
		$HImagev = $row_get_info['prod_image'];
		$HThumb = "";
		$HThumbv = $row_get_info['prod_thumb'];
		$VImage = "";
		$VImagev = $row_get_info['prod_image2'];
		$VThumb = "";
		$VThumbv = $row_get_info['prod_thumb2'];
		$Icon = "";
		$Iconv = $row_get_info['prod_tiny'];
		$PHeight = $row_get_info['prod_height'];
		$PWidth = $row_get_info['prod_width'];
		$PLength = $row_get_info['prod_length'];
		$PWeight = $row_get_info['prod_weight'];
		$PRecWidth = $row_get_info['prod_rec_width'];
		$PRecHeight = $row_get_info['prod_rec_height'];
		$PRecRes = $row_get_info['prod_rec_res'];
		$PQty = $row_get_info['prod_qty'];
		$PBorder = $row_get_info['prod_border'];
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