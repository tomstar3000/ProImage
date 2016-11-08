<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$RId = $path[4];
$ProdId = $path[2];
$RName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Rating = (isset($_POST['Rating'])) ? clean_variable($_POST['Rating'],true) : '';
$RDesc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$text = clean_variable($RDesc,'Store');
	if($cont == "add"){
		$add = "INSERT INTO `prod_ratings` (`prod_id`,`rating_name`,`rating`,`rating_desc`) VALUES ('$ProdId','$RName','$Rating','$text');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
	} else {
		$upd = "UPDATE `prod_ratings` SET `prod_id` = '$ProdId',`rating_name` = '$RName',`rating` = '$Rating',`rating_desc` = $text' WHERE `rating_id` = '$RId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	$cont = "view";
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