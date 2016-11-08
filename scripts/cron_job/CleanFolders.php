<?
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; 
else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; 
else $eol="\n"; 
//$r_path .= "var/www/www.proimagesoftware.com/";
$r_path = "/srv/proimage/current/";
require_once($r_path.'scripts/cron_job/_cleanFolders.php');
exit;


$TotalFileSize = 0; // Returned Bytes
require_once($r_path.'scripts/cart/encrypt.php');
require_once($r_path.'scripts/fnct_send_email.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);
$thirtydate = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")-17,date("Y")));
$thirtydate1 = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")-15,date("Y")));

$thirtydate = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")-2,date("Y")-1));
$thirtydate1 = date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d"),date("Y")-1));
function removeFile($File){
	global $use_ftp, $conn_id, $r_path, $TotalFileSize;
	echo " - ".$File;
	$File = $r_path.$File;
	$File = realpath($File);
	if(is_file($File) && strstr($File, 'photographers')!==false){
		echo ": Removed Size: ".(round((filesize($File)/1024)*100)/100)."Kb";
		$TotalFileSize += filesize($File);
		if($use_ftp === true){
			@ftp_delete($conn_id, $File);
		} else {
			unlink($File);
		}
	} else {
		echo ": Not Found";
	}
	echo "<br />";
}
function removeFolder($Folder){
	global $use_ftp, $conn_id, $r_path;
	echo " - ".$Folder;
	$Folder = $r_path.$Folder;
	$Folder = realpath($Folder);
	if(is_dir($Folder) && strstr($Folder, 'photographers')!==false){
		$list = ftp_nlist($conn_id, $Folder);
		if (count($list) == 0){
			echo ": Removed";
			if($use_ftp === true){
				@ftp_rmdir($conn_id, $Folder);
			} else {
				rmdir($Folder);
			}
		} else {
			echo ": Not Empty";
		}
	} else {
		echo ": Not Found";
	}
	echo "<br />";
}
function checkFiles($list, $folder){
	global $cp_connection, $thirtydate1, $r_path;
	foreach($list as $v){
		$Folder = $r_path.$v;
		$Folder = realpath($Folder);
		if(!is_dir($Folder)){
			$index = strrpos($v, "/");
			$Image = substr($v,($index+1));
			if($Image == "ExtractDump.txt"){
				removeFile($v);
			} else {
				$query_check = "SELECT COUNT(`photo_event`.`event_id`) AS `count_files` 
				FROM `photo_event` 
				INNER JOIN `photo_event_group` 
					ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
				INNER JOIN `photo_event_images` 
					ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
				WHERE (`image_tiny` = '".clean_variable($Image,true)."' OR `image_tiny` = '".$Image."')
					AND (`image_folder` = '".clean_variable($folder,true)."' OR `image_folder` = '".$folder."')
					AND ((`event_updated` >= `event_end` AND `event_updated` > '$thirtydate1') 
						OR (`event_updated` < `event_end` AND `event_end` > '$thirtydate1'))";
				$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
				$row_get_check = mysql_fetch_assoc($get_check);
				if($row_get_check['count_files'] == 0){
					$query_check = "(
					SELECT COUNT(`orders_invoice`.`invoice_id`) AS `count_files` 
					FROM `orders_invoice` 
					INNER JOIN `orders_invoice_photo` 
						ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
					WHERE (`invoice_image_image` = '".clean_variable($Image,true)."' OR `invoice_image_image` = '".$Image."')
						AND (`orders_invoice`.`invoice_comp_date` = '0000-00-00 00:00:00'
							OR `orders_invoice`.`invoice_comp_date` > '$thirtydate1')
						) UNION (
				SELECT COUNT(`orders_invoice`.`invoice_id`) AS `count_files` 
				FROM `orders_invoice` 
				INNER JOIN `orders_invoice_photo` 
					ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
				INNER JOIN `photo_event_images` 
					ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
				WHERE (`image_tiny` = '".clean_variable($Image,true)."' OR `image_tiny` = '".$Image."')
					AND (`image_folder` = '".clean_variable($folder,true)."' OR `image_folder` = '".$folder."')
					AND (`orders_invoice`.`invoice_comp` = 'n'
						OR `orders_invoice`.`invoice_comp_date` = '0000-00-00 00:00:00'
						OR `orders_invoice`.`invoice_comp_date` > '$thirtydate1')
				)";
					$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
					$row_get_check = mysql_fetch_assoc($get_check);
					if($row_get_check['count_files'] == 0){
						removeFile($v);
					} else {
						echo "- ".$v." : Needed in ".$row_get_check['count_files']." Orders<br />";
					}
				} else {
					echo "- ".$v." : Needed in ".$row_get_check['count_files']." Events<br />";
				}
			}
		}
	}
}
function checkFiles2($list, $folder){
	global $cp_connection, $thirtydate1, $r_path;
	foreach($list as $v){
		$Folder = $r_path.$v;
		$Folder = realpath($Folder);
		if(!is_dir($Folder)){
			$index = strrpos($v, "/");
			$Image = substr($v,($index+1));
			if($Image == "ExtractDump.txt"){
				removeFile($v);
			} else {
				$query_check = "(
					SELECT COUNT(`orders_invoice`.`invoice_id`) AS `count_files` 
					FROM `orders_invoice` 
					INNER JOIN `orders_invoice_photo` 
						ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
					WHERE (`invoice_image_image` = '".clean_variable($Image,true)."' OR `invoice_image_image` = '".$Image."')
						AND (`orders_invoice`.`invoice_comp` = 'n'
							OR `orders_invoice`.`invoice_comp_date` = '0000-00-00 00:00:00'
							OR `orders_invoice`.`invoice_comp_date` > '$thirtydate1')
						) UNION (
				SELECT COUNT(`orders_invoice`.`invoice_id`) AS `count_files` 
				FROM `orders_invoice` 
				INNER JOIN `orders_invoice_photo` 
					ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
				INNER JOIN `photo_event_images` 
					ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id` 
				WHERE (`image_tiny` = '".clean_variable($Image,true)."' OR `image_tiny` = '".$Image."')
					AND (`image_folder` = '".clean_variable($folder,true)."' OR `image_folder` = '".$folder."')
					AND (`orders_invoice`.`invoice_comp` = 'n'
						OR `orders_invoice`.`invoice_comp_date` = '0000-00-00 00:00:00'
						OR `orders_invoice`.`invoice_comp_date` > '$thirtydate1')
				)";
				$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
				$row_get_check = mysql_fetch_assoc($get_check);
				if($row_get_check['count_files'] == 0){
					unset ($query_check);
					unset ($get_check);
					unset ($row_get_check);
					$query_check = "SELECT COUNT(`photo_event`.`event_id`) AS `count_files`  
					FROM `photo_event` 
					INNER JOIN `photo_event_group` 
						ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
					INNER JOIN `photo_event_images` 
						ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
					WHERE (`image_tiny` = '".clean_variable($Image,true)."' OR `image_tiny` = '".$Image."')
						AND (`image_folder` = '".clean_variable($folder,true)."' OR `image_folder` = '".$folder."')
						AND ((`event_updated` >= `event_end` AND `event_updated` > '$thirtydate1') 
							OR (`event_updated` < `event_end` AND `event_end` > '$thirtydate1'))";
					$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
					$row_get_check = mysql_fetch_assoc($get_check);
					if($row_get_check['count_files'] == 0){
						removeFile($v);
					} else {
						echo "- ".$v." : Needed in ".$row_get_check['count_files']." Events<br />";
					}
				} else {
					echo "- ".$v." : Needed in ".$row_get_check['count_files']." Orders<br />";
				}
			}
		}
	}
}
function checkFolders($Folder){
	global $conn_id, $use_ftp, $ftp_server, $ftp_user_name, $ftp_user_pass;
	if($use_ftp === true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	$TopFolder = $Folder;
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);

	$Dir = $Folder."/Icon/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0)	checkFiles($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $Folder."/Large/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $Folder."/Thumbnails/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $TopFolder;
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $Folder."/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	$Dir = $Folder."/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	if($use_ftp === true)	ftp_close($conn_id);
}
function checkFolders2($Folder){
	global $conn_id, $use_ftp, $ftp_server, $ftp_user_name, $ftp_user_pass;
	if($use_ftp === true){
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	}
	$TopFolder = $Folder;
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);

	$Dir = $Folder."/Icon/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0)	checkFiles2($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $Folder."/Large/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles2($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $Folder."/Thumbnails/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles2($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $TopFolder;
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles2($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$Dir = $Folder."/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles2($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	
	$index = strrpos($Folder, "/");
	$Folder = substr($Folder,0,$index);
	$Dir = $Folder."/";
	echo "Checking Folder: ".$Dir."<br />";
	$list = ftp_nlist($conn_id, $Dir);
	if (count($list) > 0) checkFiles2($list, $TopFolder);
	removeFolder($Dir);
	unset($Dir);
	if($use_ftp === true)	ftp_close($conn_id);
}

// Check Events
$query_get_exp = "SELECT `image_folder`, `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_use`, `photo_event_group`.`group_id`, `cust_customers`.`cust_handle` 
	FROM `photo_event` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	INNER JOIN `photo_event_group`
		ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
	WHERE ((`event_updated` >= `event_end` AND `event_updated` >= '$thirtydate' AND `event_updated` <= '$thirtydate1')
			OR (`event_updated` < `event_end` AND `event_end` >= '$thirtydate' AND `event_end` <= '$thirtydate1'))
		AND `event_use` = 'n'
	GROUP BY `photo_event_group`.`group_id`
	ORDER BY `event_id";
/*
$query_get_exp = "SELECT `image_folder`, `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_use`, `photo_event_group`.`group_id`, `cust_customers`.`cust_handle` 
	FROM `photo_event` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	INNER JOIN `photo_event_group`
		ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
	WHERE ((`event_updated` >= `event_end` AND `event_updated` <= '$thirtydate1') 
			OR (`event_updated` < `event_end` AND `event_end` <= '$thirtydate1'))
		AND `event_use` = 'n'
	GROUP BY `photo_event_group`.`group_id`
	ORDER BY `event_id`";
*/
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);

ob_start();
echo "Checking Events From ".$thirtydate." - ".$thirtydate1.":<br/>Number of Events: ".$total_get_exp;
echo "<br / >";
echo $query_get_exp;
echo "<br / >";
sleep(1);
if($total_get_exp>0){
	$oname = "";
	$oFolder = false;
	while($row_get_exp = mysql_fetch_assoc($get_exp)){
		$Folder = $row_get_exp['image_folder'];
		$index = strrpos($Folder, "/");
		$Folder = substr($Folder,0,$index);
		$index = strrpos($Folder, "/");
		$Folder = substr($Folder,0,$index);
		if(is_dir(realpath($r_path.$Folder))){
			$query_get_images = "SELECT `image_folder`, `image_tiny`, `image_id`
	FROM `photo_event_images`
	WHERE `group_id` = '".$row_get_exp['group_id']."'
	GROUP BY `image_id`
	ORDER BY `image_id`";
			$get_images = mysql_query($query_get_images, $cp_connection) or die(mysql_error());
			$total_get_images = mysql_num_rows($get_images);
			echo "Number of Images: ".$total_get_images; echo "<br / >";
			if($total_get_images > 0){
				$FilesNeeded = false;
				while($row_get_images = mysql_fetch_assoc($get_images)){
					$name = $row_get_exp['cust_handle']." - ".$row_get_exp['event_name']." (".$row_get_exp['event_id'].")";
					$Folder = $row_get_images['image_folder'];
					if($oname != $name){
						$oname = $name;
						echo $name;
						echo "<br />";
					}
					if(is_dir(realpath($r_path.$Folder))){
						$query_check = "SELECT COUNT(`photo_event`.`event_id`) AS `count_files` 
					FROM `photo_event` 
					INNER JOIN `photo_event_group` 
						ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
					INNER JOIN `photo_event_images` 
						ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
					WHERE (`image_tiny` = '".clean_variable($row_get_images['image_tiny'],true)."' OR `image_tiny` = '".$row_get_images['image_tiny']."')
						AND (`image_folder` = '".clean_variable($row_get_images['image_folder'],true)."' OR `image_folder` = '".$row_get_images['image_folder']."')
						AND ((`event_updated` >= `event_end` AND `event_updated` > '$thirtydate1') 
							OR (`event_updated` < `event_end` AND `event_end` > '$thirtydate1'))";
						$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
						$row_get_check = mysql_fetch_assoc($get_check);
						if($row_get_check['count_files'] == 0){
							unset ($query_check);
							unset ($get_check);
							unset ($row_get_check);
							$query_check = "SELECT COUNT(`orders_invoice`.`invoice_id`) AS `count_files`
								FROM `orders_invoice` 
								INNER JOIN `orders_invoice_photo` 
									ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
								WHERE `image_id` = '".$row_get_images['image_id']."'
									AND (`orders_invoice`.`invoice_comp_date` = '0000-00-00 00:00:00'
										OR `orders_invoice`.`invoice_comp_date` > '$thirtydate1')";
							$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
							$row_get_check = mysql_fetch_assoc($get_check);
							if($row_get_check['count_files'] == 0){
								if($use_ftp === true){
									$conn_id = ftp_connect($ftp_server);
									$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
								}
								
								$index = strrpos($Folder, "/");
								$Folder = substr($Folder,0,$index);
								$index = strrpos($Folder, "/");
								$Folder = substr($Folder,0,$index);
								
								$File = $Folder."/Icon/".$row_get_images['image_tiny'];
								removeFile($File);
								
								$File = $Folder."/Large/".$row_get_images['image_tiny'];
								removeFile($File);
								
								$File = $Folder."/Thumbnails/".$row_get_images['image_tiny'];
								removeFile($File);
								
								$File = $row_get_images['image_folder'].$row_get_images['image_tiny'];
								removeFile($File);
								
								if($use_ftp === true)	ftp_close($conn_id);
								echo "<br />";
							} else {
								echo " - ".$row_get_images['image_folder'].$row_get_images['image_tiny'].": Needed in ".$row_get_check['count_files']." Orders";
								echo "<br />";
								$FilesNeeded = true;
							}
						} else {
							echo " - ".$row_get_images['image_folder'].$row_get_images['image_tiny'].": Needed in ".$row_get_check['count_files']." Events";
							echo "<br />";
							$FilesNeeded = true;
						}
					} else {
						echo " - ".$row_get_images['image_folder'].$row_get_images['image_tiny'].": Not Found";
						echo "<br />";
					}
				}  if($FilesNeeded === false) checkFolders($row_get_exp['image_folder']);
			} else {
				echo " - ".$row_get_exp['image_folder'].": No Files";
				echo "<br />";
			}
		}	else {
			echo " - ".$row_get_exp['image_folder'].": Not Found";
			echo "<br />";
		}
		sleep(1);
	}
}
// Check Invoices

$query_get_exp = "SELECT `image_folder`, `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_use`, `photo_event_group`.`group_id`, `cust_customers`.`cust_handle` 
	FROM `photo_event` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	INNER JOIN `photo_event_group`
		ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id`
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id`
	WHERE `orders_invoice`.`invoice_comp_date` <= '$thirtydate1'
		AND `orders_invoice`.`invoice_comp_date` >= '$thirtydate'
		AND `orders_invoice`.`invoice_comp` = 'y'
		AND `photo_event`.`event_use` = 'n'
	GROUP BY `photo_event_group`.`group_id`
	ORDER BY `event_id`";
/*
$query_get_exp = "SELECT `image_folder`, `photo_event`.`event_id`, `photo_event`.`event_name`, `photo_event`.`event_use`, `photo_event_group`.`group_id`, `cust_customers`.`cust_handle` 
	FROM `photo_event` 
	INNER JOIN `cust_customers` 
		ON `cust_customers`.`cust_id` = `photo_event`.`cust_id`
	INNER JOIN `photo_event_group`
		ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
	INNER JOIN `photo_event_images` 
		ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
	INNER JOIN `orders_invoice_photo` 
		ON `orders_invoice_photo`.`image_id` = `photo_event_images`.`image_id`
	INNER JOIN `orders_invoice` 
		ON `orders_invoice`.`invoice_id` = `orders_invoice_photo`.`invoice_id`
	WHERE `orders_invoice`.`invoice_comp_date` <= '$thirtydate1'
		AND `orders_invoice`.`invoice_comp` = 'y'
		AND `photo_event`.`event_use` = 'n'
	GROUP BY `photo_event_group`.`group_id`
	ORDER BY `event_id`";
*/
$get_exp = mysql_query($query_get_exp, $cp_connection) or die(mysql_error());
$total_get_exp = mysql_num_rows($get_exp);
echo "Checking Orders From ".$thirtydate." - ".$thirtydate1.":<br/>Number of Events: ".$total_get_exp;
echo "<br / >";
echo $query_get_exp;
echo "<br / >";
sleep(1);

if($total_get_exp>0){
	$oname = "";
	$oFolder = false;
	while($row_get_exp = mysql_fetch_assoc($get_exp)){
		$Folder = $row_get_exp['image_folder'];
		$index = strrpos($Folder, "/");
		$Folder = substr($Folder,0,$index);
		$index = strrpos($Folder, "/");
		$Folder = substr($Folder,0,$index);
		
		if(is_dir(realpath($r_path.$Folder))){
			$query_get_images = "SELECT `image_folder`, `image_tiny`, `image_id`
				FROM `photo_event_images`
				WHERE `group_id` = '".$row_get_exp['group_id']."'
				GROUP BY `image_id`
				ORDER BY `image_id`";
			$get_images = mysql_query($query_get_images, $cp_connection) or die(mysql_error());
			$total_get_images = mysql_num_rows($get_images);
			echo "Number of Image: ".$total_get_images; echo "<br / >";
			if($total_get_images > 0){
				$FilesNeeded = false;
				while($row_get_images = mysql_fetch_assoc($get_images)){
					$name = $row_get_exp['cust_handle']." - ".$row_get_exp['event_name']." (".$row_get_exp['event_id'].")";
					$Folder = $row_get_images['image_folder'];
					if($oname != $name){
						$oname = $name;
						echo $name;
						echo "<br />";
					}
					if(is_dir(realpath($r_path.$Folder))){
						$query_check = "SELECT COUNT(`orders_invoice`.`invoice_id`) AS `count_files`
							FROM `orders_invoice` 
							INNER JOIN `orders_invoice_photo` 
								ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id` 
							WHERE `image_id` = '".$row_get_images['image_id']."'
								AND (`orders_invoice`.`invoice_comp` = 'n'
									OR `orders_invoice`.`invoice_comp_date` = '0000-00-00 00:00:00'
									OR `orders_invoice`.`invoice_comp_date` > '$thirtydate1')";
						$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
						$row_get_check = mysql_fetch_assoc($get_check);
						if($row_get_check['count_files'] == 0){
							unset ($query_check);
							unset ($get_check);
							unset ($row_get_check);
							$query_check = "SELECT COUNT(`photo_event`.`event_id`) AS `count_files` 
								FROM `photo_event` 
								INNER JOIN `photo_event_group` 
									ON `photo_event_group`.`event_id` = `photo_event`.`event_id` 
								INNER JOIN `photo_event_images` 
									ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id` 
								WHERE (`image_tiny` = '".clean_variable($row_get_images['image_tiny'],true)."' OR `image_tiny` = '".$row_get_images['image_tiny']."')
									AND (`image_folder` = '".clean_variable($row_get_images['image_folder'],true)."' OR `image_folder` = '".$row_get_images['image_folder']."')
									AND ((`event_updated` >= `event_end` AND `event_updated` > '$thirtydate1') 
										OR (`event_updated` < `event_end` AND `event_end` > '$thirtydate1'))";
							$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
							$row_get_check = mysql_fetch_assoc($get_check);
							if($row_get_check['count_files'] == 0){
								if($use_ftp === true){
									$conn_id = ftp_connect($ftp_server);
									$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
								}
								
								$index = strrpos($Folder, "/");
								$Folder = substr($Folder,0,$index);
								$index = strrpos($Folder, "/");
								$Folder = substr($Folder,0,$index);
								
								$File = $Folder."/Icon/".$row_get_images['image_tiny'];
								removeFile($File);
								
								$File = $Folder."/Large/".$row_get_images['image_tiny'];
								removeFile($File);
								
								$File = $Folder."/Thumbnails/".$row_get_images['image_tiny'];
								removeFile($File);
								
								$File = $row_get_images['image_folder'].$row_get_images['image_tiny'];
								removeFile($File);
								
								if($use_ftp === true)	ftp_close($conn_id);
								echo "<br />";
							} else {
								echo " - ".$row_get_images['image_folder'].$row_get_images['image_tiny'].": Needed in ".$row_get_check['count_files']." Events";
								echo "<br />";
								$FilesNeeded = true;
							}
						} else {
							echo " - ".$row_get_images['image_folder'].$row_get_images['image_tiny'].": Needed in ".$row_get_check['count_files']." Orders";
							echo "<br />";
							$FilesNeeded = true;
						}
					} else {
						echo " - ".$row_get_images['image_folder'].$row_get_images['image_tiny'].": Not Found";
						echo "<br />";
					}
				} if($FilesNeeded === false) checkFolders2($row_get_exp['image_folder']);
			} else {
					echo " - ".$row_get_exp['image_folder'].": No Files";
					echo "<br />";
			}
		}	else {
			echo " - ".$row_get_exp['image_folder'].": Not Found";
			echo "<br />";
		}
		sleep(1);
	}
}
echo "Total Removed: ".(round(($TotalFileSize/1073741824)*100)/100)."Gb";
$page = ob_get_contents();
ob_end_clean();

send_email("development@proimagesoftware.com", "development@proimagesoftware.com", "Photoexpress CronJob: Image Removal", $page, true, false, false);
?>