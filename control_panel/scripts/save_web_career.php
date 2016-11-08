<?
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'scripts/fnct_image_resize.php';
require_once $r_path.'scripts/fnct_format_file_name.php';
$CId = $path[2];
$Name = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Number = (isset($_POST['Number'])) ? clean_variable($_POST['Number'],true) : '';
$Date = (isset($_POST['Start_Date'])) ? clean_variable($_POST['Start_Date'],true) : '';
$Desc = (isset($_POST['Description'])) ? clean_variable($_POST['Description']) : '';
$Image = "";
$Imagev = (isset($_POST['Image_val'])) ? clean_variable($_POST['Image_val'],true) : '';
$White = "";
$Whitev = (isset($_POST['Whitepaper_val'])) ? clean_variable($_POST['Whitepaper_val'],true) : '';
$Whiten = (isset($_POST['Whitepaper_name'])) ? clean_variable($_POST['Whitepaper_name'],true) : '';
$Active = (isset($_POST['Active'])) ? clean_variable($_POST['Active'],true) : 'y';
$CRId = (isset($_POST['Requirment_Ids'])) ? $_POST['Requirment_Ids'] : array("");
$CRType = (isset($_POST['Requirment_Type'])) ? $_POST['Requirment_Type'] : array("");
$CRDesc = (isset($_POST['Requirment_Description'])) ? $_POST['Requirment_Description'] : array("");
$CRReq = array();
if(count($CRId)>0){
	foreach($CRId as $k => $v){
		$CRDesc[$k] = clean_variable($CRDesc[$k]);
		array_push($CRReq, clean_variable($_POST['Requirment_Required_'.($k+1)]));
	}
} else {
	$CRReq = (isset($_POST['Requirment_Required_1'])) ? array($_POST['Requirment_Required_1']) : array("");
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save"){
	if (is_uploaded_file($_FILES['Image']['tmp_name'])){
		$Fname = $_FILES['Image']['name'];
		$Iname = format_file_name($Fname,"i");
		$ISize = $_FILES['Image']['size'];
		$ITemp = $_FILES['Image']['tmp_name'];
		$IType = $_FILES['Image']['type'];
		
		$Image = loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Car_Folder, $Car_IWidth, $Car_IHeight, $Car_ICrop, $Car_IResize);
	} else {
		$Image = array();
		if($Car_IReq){
			$Image[0] = false;
			$Image[1] = "Image is Required";
		} else {
			$Image[0] = true;
			$Image[1] = $Imagev;
		}
	}
	if (is_uploaded_file($_FILES['Whitepaper']['tmp_name'])){
		$White = array();
		$White[0] = true;
		$White[1] = format_file_name($_FILES['Whitepaper']['name'],"w");
		$White[2] = $_FILES['Whitepaper']['name'];
		if(isset($ftp_cp_connection) && $ftp_cp_connection !== false){
			$conn_id = ftp_connect($ftp_cp_connection);
			$result = ftp_login ($conn_id, $ftp_cp_username, $ftp_cp_password) or die("Couldn't connect to $ftp_cp_connection");
			
			if(is_bool($ftp_cp_root) && $ftp_cp_root == true){
				$Prod_White_Folder = realpath($Prod_White_Folder);
			} else if($ftp_cp_root !== false){
				$Prod_White_Folder = $ftp_cp_root.substr($Prod_White_Folder,3);
			}
			if(!is_dir($Prod_White_Folder)){
				ftp_mkdir($conn_id, $Prod_White_Folder);
			}
			ftp_put($conn_id, $Prod_White_Folder."/".$White[1], $_FILES['Whitepaper']['tmp_name'], FTP_BINARY);
			ftp_close($conn_id);
		} else {
			copy($_FILES['Whitepaper']['tmp_name'], $Prod_White_Folder."/".$White[1]);
		}
	} else {
		$White = array();
		$White[0] = true;
		$White[1] = $Whitev;
		$White[2] = $Whiten;
	}
	$text = clean_variable($Desc,'Store');
	if($Image[0] && $White[0]){
		$Imagev = $Image[1];
		$Whiten = $White[2];
		$Whitev = $White[1];
		if($_POST['Remove_Image'] == "true")$Imagev = "";
		if($_POST['Remove_Whitepaper'] == "true")$Whitev = "";
		if($cont == "add"){	
			if($_POST['Time'] != $_SESSION['Time']){
				$_SESSION['Time'] = $_POST['Time'];		
				$add = "INSERT INTO `web_careers` (`career_name`,`career_number`,`career_text`,`career_image`, `career_white`, `career_while_name`, `career_date`, `career_active`) VALUES ('$Name','$Number','$text','$Imagev','$Whitev','$Whiten','$Date','$Active');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
				
				$query_get_last = "SELECT `career_id` FROM `web_careers` ORDER BY `career_id` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);
				
				$path[2] = $row_get_last['career_id'];
				
				mysql_free_result($get_last);
				
				foreach($CRId as $k => $v){
					if($CRDesc[$k] != ""){
					} else {
						$text = clean_variable($CRDesc[$k],'Store');
						$add = "INSERT INTO `web_career_req` (`career_id`,`career_req_type_id`,`career_req_text`,`career_req_required`) VALUES ('$path[2]','$CRType[$k]','$text','$CRReq[$k]');";
						$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
					}
				}
			}
		} else if($cont == "edit"){	
			$upd = "UPDATE `web_careers` SET `career_name` = '$Name',`career_number` = '$Number',`career_text` = '$text', `career_image` = '$Imagev', `career_white` = '$Whitev', `career_while_name` = '$Whiten', `career_date` = '$Date', `career_active` = '$Active' WHERE `career_id` = '$CId'";
			$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			
			$query_check_info = "SELECT `career_req_id` FROM `web_career_req` WHERE `career_id` = '$CId'";
			$check_info = mysql_query($query_check_info, $cp_connection) or die(mysql_error());
			
			while($row_check_info = mysql_fetch_assoc($check_info)){
				$Tempid = $row_check_info['career_req_id'];
				foreach($CRId as $k => $v){
					switch($v){
						case $Tempid:
							$action = "Update";
							$key = $k;
							break 2;
						default:
							$action = "Delete";
							$key = false;
							break;
					}
				}
				if($action == "Update"){
					$text = clean_variable($CRDesc[$key],true);
					$upd = "UPDATE `web_career_req` SET `career_req_text` = '$text', `career_req_required` = '$CRReq[$key]' WHERE `career_req_id` = '$Tempid'";
					$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
				} else if($action == "Delete"){
					$del= "DELETE FROM `web_career_req` WHERE `career_req_id` = '$Tempid'";
					$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
					
					$optimize = "OPTIMIZE TABLE `web_career_req`";
					$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
				}
			} while($row_check_info = mysql_fetch_assoc($check_info));
			
			mysql_free_result($check_info);
			
			foreach($CRId as $k => $v){
				if($CRDesc[$k] != ""){
					if($v == "-1"){
						$text = clean_variable($CRDesc[$k],true);
						$add = "INSERT INTO `web_career_req` (`career_id`,`career_req_type_id`,`career_req_text`,`career_req_required`) VALUES ('$CId','$CRType[$k]','$text','$CRReq[$k]');";
						$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
					}
				}
			}
		}
		$cont = "view";
	}
} else {
	if($cont != "add"){
		$query_get_info = "SELECT * FROM `web_careers` WHERE `career_id` = '$CId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Name = $row_get_info['career_name'];
		$Number = $row_get_info['career_number'];
		$Date = $row_get_info['career_date'];
		$Desc = $row_get_info['career_text'];
		$Image = "";
		$Imagev = $row_get_info['career_image'];
		$White = "";
		$Whitev = $row_get_info['career_white'];
		$Whiten = $row_get_info['career_while_name'];
		$Active = $row_get_info['career_active'];
		
		if(!isset($_POST['Requirment_Ids'])){
			$query_get_req_info = "SELECT * FROM `web_career_req` WHERE `career_id` = '$CId'";
			$get_req_info = mysql_query($query_get_req_info, $cp_connection) or die(mysql_error());
			
			$CRId = array();
			$CRType = array();
			$CRDesc = array();
			$CRReq = array();
			
			while($row_get_req_info = mysql_fetch_assoc($get_req_info)){
				array_push($CRId,$row_get_req_info['career_req_id']);
				array_push($CRType,$row_get_req_info['career_req_type_id']);
				array_push($CRDesc,$row_get_req_info['career_req_text']);
				array_push($CRReq,$row_get_req_info['career_req_required']);
			}
			mysql_free_result($get_req_info);
		}
		
		mysql_free_result($get_info);
	}
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "new_block"){
	array_push($CRId,"-1");
	array_push($CRType,$_POST['new_type']);
	array_push($CRDesc,"");
	array_push($CRReq,"y");
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "remove_block"){
	array_splice($CRId, $_POST['Rmv_Id'], 1);
	array_splice($CRType, $_POST['Rmv_Id'], 1);
	array_splice($CRDesc, $_POST['Rmv_Id'], 1);
	array_splice($CRReq, $_POST['Rmv_Id'], 1);
}
?>