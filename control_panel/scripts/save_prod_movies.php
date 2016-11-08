<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$RId = $path[4];
$ProdId = $path[2];
$Image_1 = "";
$Imagev_1 = (isset($_POST['File_val'])) ? clean_variable($_POST['File_val'],true) : '';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	$message = "";
	for($n = 1; $n<=5; $n++){
		$IName = (isset($_POST['Name_'.$n])) ? clean_variable($_POST['Name_'.$n],true) : '0';
		$IAlt = (isset($_POST['Alt_'.$n])) ? clean_variable($_POST['Alt_'.$n],true) : '0';
		if (is_uploaded_file($_FILES['File_'.$n]['tmp_name'])){
			$Fname = $_FILES['File_'.$n]['name'];
			$Iname = format_file_name($Fname,"i");
			$ISize = $_FILES['File_'.$n]['size'];
			$ITemp = $_FILES['File_'.$n]['tmp_name'];
			$IType = $_FILES['File_'.$n]['type'];
			copy($ITemp, $Prod_Folder."/".$Iname);
			$File = array();
			$File[0] = true;
			$File[1] = $Iname;
		} else {
			$File = array();
			$File[0] = false;
			$File[1] = "";
		}
		if($File[0]){
			$File = $File[1];
			if($_POST['Remove_File_'.$n] == "true")$File = "";
			if($cont == "add"){
				if($_POST['Time'] != $_SESSION['Time']){
					$_SESSION['Time'] = $_POST['Time'];	
					$add = "INSERT INTO `prod_media` (`prod_id`,`prod_media_type`,`prod_media_name`,`prod_media_file`,`prod_media_alt`,`prod_media_desc`) VALUES ('$ProdId','2','$Iname','$File','$IAlt','');";
					$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
				}
			} else {
				$upd = "UPDATE `prod_media` SET `prod_media_name` = '$Iname',`prod_media_file` = '$File',`prod_media_alt` = '$IAlt',`prod_media_desc` = '' WHERE `prod_media_id` = '$RId'";
				$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			}
		}
	}
	$cont = "query";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_media` WHERE `prod_media_id` = '$RId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
						
		mysql_free_result($get_info);
	}
}

?>