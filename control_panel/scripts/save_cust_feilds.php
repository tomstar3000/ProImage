<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$FId = $path[2];
$FieldId = $path[4];
$FName = (isset($_POST['Name'])) ? clean_variable($_POST['Name'],true) : '';
$Tag = (isset($_POST['Tag'])) ? clean_variable($_POST['Tag'],true) : '';
$FField = (isset($_POST['Field_Type'])) ? clean_variable($_POST['Field_Type'],true) : '1';
$FMChar = (isset($_POST['Max_Characters'])) ? clean_variable($_POST['Max_Characters'],true) : '';
$FRows = (isset($_POST['Rows'])) ? clean_variable($_POST['Rows'],true) : '';
$FCol = (isset($_POST['Columns'])) ? clean_variable($_POST['Columns'],true) : '';
$FReq = (isset($_POST['Required'])) ? clean_variable($_POST['Required'],true) : 'n';
$FRest = (isset($_POST['Restrictions'])) ? clean_variable($_POST['Restrictions'],true) : 'n';
$FMin = (isset($_POST['Minimum_Value'])) ? clean_variable($_POST['Minimum_Value'],true) : '';
$FMax = (isset($_POST['Maximum_Number'])) ? clean_variable($_POST['Maximum_Number'],true) : '';
$OId = (isset($_POST['Option_Id'])) ? $_POST['Option_Id'] : array();
$OValue = (isset($_POST['Option_Value'])) ? $_POST['Option_Value'] : array();
$OText = (isset($_POST['Option_Text'])) ? $_POST['Option_Text'] : array();
$State = (isset($_POST['State'])) ? clean_variable($_POST['State'],true) : 'n';
$Country = (isset($_POST['Country'])) ? clean_variable($_POST['Country'],true) : 'n';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$add = "INSERT INTO `form_fields` (`form_id`,`feild_type_id`,`feild_name`,`field_tag`,`feild_max_chars`,`feild_rows`,`feild_cols`,`feild_req`,`feild_num`,`feild_min`,`feild_max`,`field_state`,`field_country`) VALUES ('$FId','$FField','$FName','$Tag','$FMChar','$FRows','$FCol','$FReq','$FRest','FMin','$FMax','$State','$Country');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());	
			
			$query_get_last = "SELECT `feild_id` FROM `form_fields` WHERE `form_id` = '$FId' ORDER BY `feild_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);
			$FieldId = $row_get_last['feild_id'];
			$path[4] = $FieldId;
		}
	} else {
		$upd = "UPDATE `form_fields` SET `feild_type_id` = '$FField',`feild_name` = '$FName',`field_tag` = '$Tag',`feild_max_chars` = '$FMChar',`feild_rows` = '$FRows',`feild_cols` = '$FCol',`feild_req` = '$FReq',`feild_num` = '$FRest',`feild_min` = 'FMin',`feild_max` = '$FMax',`field_state` = '$State',`field_country` = '$Country' WHERE `feild_id` = '$FieldId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	
	$query_get_options = "SELECT * FROM `form_feild_options` WHERE `feild_id` = '$FieldId'";
	$get_options = mysql_query($query_get_options, $cp_connection) or die(mysql_error());
	$Update_Ids = array();
	while($row_get_options = mysql_fetch_assoc($get_options)){
		$key = array_search($row_get_options['option_id'], $OId);
		if($key === false){
			$tempid = $row_get_options['option_id'];
			//$upd = "UPDATE `form_feild_options` SET `options_use` = 'n' WHERE `client_honor_id` = '$tempid';";
			//$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
			$del= "DELETE FROM `form_feild_options` WHERE `option_id` = '$tempid'";
			$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
			$optimize = "OPTIMIZE TABLE `form_feild_options`";
			$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
		} else {
			$count = count($Update_Ids);
			$Update_Ids[$count][0] = $OId[$key];
			$Update_Ids[$count][1] = $OValue[$key];
			$Update_Ids[$count][2] = $OText[$key];
			array_splice($OId, $key,1);
			array_splice($OValue, $key,1);
			array_splice($OText, $key,1);
		}
	}
	if(count($OId)>0){
		foreach($OId as $k => $v){
			if($v != 0){
				if($v[1] != "" && $v[1] != " "){
					$add = "INSERT INTO `form_feild_options` (`feild_id`,`option_value`,`option_name`) VALUES ('$FieldId','$OValue[$k]', '$OText[$k]');";
					$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
				}
			}
		}
	}
	if(count($Update_Ids>0)){
		foreach($Update_Ids as $k => $v){
			if($v[1] != "" && $v[1] != " "){
				$add = "UPDATE `form_feild_options` SET `option_value` = '$v[1]',`option_name` = '$v[2]' WHERE `option_id` = '$v[0]';";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			}
		}
	}
	$cont = "view";
} else {
	if($cont != "add"){	
		$query_get_info = "SELECT * FROM `form_fields` WHERE `feild_id` = '$FieldId'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$FName = $row_get_info['feild_name'];
		$Tag = $row_get_info['field_tag'];
		$FField = $row_get_info['feild_type_id'];
		$FMChar = $row_get_info['feild_max_chars'];
		$FRows = $row_get_info['feild_rows'];
		$FCol = $row_get_info['feild_cols'];
		$FReq = $row_get_info['feild_req'];
		$FRest = $row_get_info['feild_num'];
		$FMin = $row_get_info['feild_min'];
		$FMax = $row_get_info['feild_max'];
		$State = $row_get_info['field_state'];
		$Country = $row_get_info['field_country'];
		
		$query_get_info = "SELECT * FROM `form_feild_options` WHERE `feild_id` = '$FieldId' ORDER BY `feild_id` ASC";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$OId = array();
		$OValue = array();
		$OText = array();
		while($row_get_info = mysql_fetch_assoc($get_info)){
			array_push($OId,$row_get_info['option_id']);
			array_push($OValue,$row_get_info['option_value']);
			array_push($OText,$row_get_info['option_name']);
		}
		
		mysql_free_result($get_info);
	}
}
?>