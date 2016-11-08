<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once($r_path.'../Connections/cp_connection.php');
require_once $r_path.'scripts/fnct_clean_entry.php';

$ID = (isset($_GET['id'])) ? clean_variable($_GET['id'],true) : '';
$CustID = (isset($_GET['user'])) ? clean_variable($_GET['user'],true) : '';
$Action = (isset($_GET['action'])) ? clean_variable($_GET['action'],true) : '';
$DATAInfo = (isset($_GET['data'])) ? unserialize(rawurldecode($_GET['data'])) : array();
foreach($DATAInfo as $k => $v) $DATAInfo[$k] = clean_variable($v,true);

if(intval($Action) == 1){
	$getInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
	$getInfo->mysql("SELECT * FROM `photo_photographers` WHERE `photo_id` = '$ID';");
	$getInfo = $getInfo->Rows();
	$DATA = '<photo fname="'.$getInfo[0]['photo_fname'].'" lname="'.$getInfo[0]['photo_lname'].'" add="'.$getInfo[0]['photo_add'].'" add2="'.$getInfo[0]['photo_add2'].'" sapt="'.$getInfo[0]['photo_suiteapt'].'" city="'.$getInfo[0]['photo_city'].'" state="'.$getInfo[0]['photo_state'].'" zip="'.$getInfo[0]['photo_zip'].'" country="'.$getInfo[0]['photo_country'].'" phone="'.$getInfo[0]['photo_phone'].'" p1="'.substr($getInfo[0]['photo_phone'],0,3).'" p2="'.substr($getInfo[0]['photo_phone'],3,3).'" p3="'.substr($getInfo[0]['photo_phone'],6,4).'" email="'.$getInfo[0]['photo_email'].'"></photo>';
} else if(intval($Action) == 2){
	if(intval($ID) == 0){
		$addInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$addInfo->mysql("INSERT INTO `photo_photographers` (`cust_id`,`photo_fname`,`photo_lname`,`photo_add`,`photo_add2`,`photo_suiteapt`,`photo_city`,`photo_state`,`photo_zip`,`photo_country`,`photo_phone`,`photo_email`,`photo_use`) VALUES ('$CustID','".$DATAInfo['Photographer_First_Name']."','".$DATAInfo['Photographer_Last_Name']."','".$DATAInfo['Photographer_Address']."','".$DATAInfo['Photographer_Address_2']."','".$DATAInfo['Photographer_Suite_Apt']."','".$DATAInfo['Photographer_City']."','".$DATAInfo['Photographer_State']."','".$DATAInfo['Photographer_Zip']."','".$DATAInfo['Photographer_Country']."','".$DATAInfo['Photographer_Phone_Number']."','".$DATAInfo['Photographer_Email']."','y');");
		
		$getLast = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$getLast->mysql("SELECT `photo_id` FROM `photo_photographers` WHERE `cust_id` = '$CustID' ORDER BY `photo_id` DESC LIMIT 0,1;");
		$getLast = $getLast->Rows();
		
		$DATA = "<action>Added</action><data id=\"".$getLast[0]['photo_id']."\">".$DATAInfo['Photographer_Last_Name'].", ".$DATAInfo['Photographer_First_Name']."</data>";
	} else {
		$upd = "UPDATE `photo_photographers` SET `photo_fname` = '".$DATAInfo['Photographer_First_Name']."',`photo_lname` = '".$DATAInfo['Photographer_Last_Name']."',`photo_add` = '".$DATAInfo['Photographer_Address']."',`photo_add2` = '".$DATAInfo['Photographer_Address_2']."',`photo_suiteapt` = '".$DATAInfo['Photographer_Suite_Apt']."',`photo_city` = '".$DATAInfo['Photographer_City']."',`photo_state` = '".$DATAInfo['Photographer_State']."',`photo_zip` = '".$DATAInfo['Photographer_Zip']."',`photo_country` = '".$DATAInfo['Photographer_Country']."',`photo_phone` = '".$DATAInfo['Photographer_Phone_Number']."',`photo_email` = '".$DATAInfo['Photographer_Email']."' WHERE `photo_id` = '$ID'";
		$updInfo = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
		$updInfo->mysql($upd);
		$DATA = "<action>Updated</action>";
	} 
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<info>';
echo $DATA;
echo '</info>'; ?>