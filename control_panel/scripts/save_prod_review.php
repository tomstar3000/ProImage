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
require_once $r_path.'scripts/fnct_format_file_name.php';
$RId = $path[4];
$ProdId = $path[2];
$RName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Rating = (isset($_POST['Rating'])) ? clean_variable($_POST['Rating'],true) : '';
$RURL = (isset($_POST['URL'])) ? clean_variable($_POST['URL'],true) : '';
$RDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Review_Folder, $Review_IWidth, $Review_IHeight, $Review_ICrop, $Review_IResize);
	} else {
		$Image = array();
		if($Review_IReq){
			$Image[0] = false;
		} else {
			$Image[0] = true;
		}
		$Image[1] = $Imagev;
	}
	if($Image[0]){
		$Image = $Image[1];
		if($_POST['Remove_Image'] == "true"){
			$Image = "";
		}
		$text = clean_variable($RDesc,'Store');
		if($cont == "add"){
			$add = "INSERT INTO `prod_reviews` (`prod_id`,`review_name`,`review_image`,`review_desc`,`review_rating`,`review_url`) VALUES ('$ProdId','$RName','$Image','$text','$Rating','$RURL');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		} else {
			$upd = "UPDATE `prod_reviews` SET `prod_id` = '$ProdId',`review_name` = '$RName',`review_image` = '$Image',`review_desc` = '$text',`review_rating` = '$Rating',`review_url` = '$RURL' WHERE `review_id` = '$RId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		}
		$cont = "view";
	} else {
		$message = "";
		if($Image[0]){
			$message += $Image[1]+"\n";
		}
		$Image = $Image[1];
	}
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_reviews` WHERE `review_id` = '$RId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
		
		$RName = $row_get_info['review_name'];
		$Rating =  $row_get_info['review_rating'];
		$RDesc = $row_get_info['review_desc'];
		$RURL = $row_get_info['review_url'];
		$Imagev = $row_get_info['review_image'];
				
		mysql_free_result($get_info);
	}
}

?>