<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$DId = $path[4];
$ProdId = $path[2];
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	for($n = 1; $n<=5; $n++){
		$DName = (isset($_POST['Name_'.$n])) ? clean_variable($_POST['Name_'.$n],true) : '0';
		
		if (is_uploaded_file($_FILES['File_'.$n]['tmp_name'])){
			$Fname = $_FILES['File_'.$n]['name'];
			$File = $Fname;
			//$File = format_file_name($Fname,"i");
			$ISize = $_FILES['File_'.$n]['size'];
			$ITemp = $_FILES['File_'.$n]['tmp_name'];
			$IType = $_FILES['File_'.$n]['type'];
			
			if(!is_dir($Prod_White_Folder)){
				mkdir($Prod_White_Folder);
			}
			copy($ITemp, $Prod_White_Folder."/".$File);
		} else {
			$File = array();
			$File = false;
		}
		if($DName == 0 || $DName == "" || $DName = " "){
			$DName = $File;
		}
		if($File){
			$File = $File;
			if($_POST['Remove_File_'.$n] == "true"){
				$File = "";
			}
			$DName = (isset($_POST['Name_'.$n])) ? clean_variable($_POST['Name_'.$n],true) : $DName;
			if($cont == "add"){
				$add = "INSERT INTO `prod_documentation` (`prod_id`,`prod_doc_name`,`prod_doc_file`) VALUES ('$ProdId','$DName','$File');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			} else {
				$upd = "UPDATE `prod_documentation` SET `prod_id` = '$ProdId',`prod_doc_name` = '$DName',`prod_doc_file` = '$File' WHERE `prod_doc_id` = '$DId'";
				$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			}
		} else {
			$message = "";
			if($File){
				$message += $File+"\n";
			}
		}
	}
	$cont = "query";
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `prod_info` WHERE `prod_doc_id` = '$DId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		$totalRows_get_info = mysql_num_rows($get_info);
						
		mysql_free_result($get_info);
	}
}

?>