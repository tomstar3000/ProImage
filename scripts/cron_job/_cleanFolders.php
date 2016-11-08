<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);
if (strtoupper(substr(PHP_OS,0,3))=='WIN') $eol="\r\n"; else if (strtoupper(substr(PHP_OS,0,3))=='MAC') $eol="\r"; else $eol="\n"; 

$r_path = "/srv/proimage/current/";
$TotalFileSize = 0; // Returned Bytes
require_once($r_path.'scripts/cart/encrypt.php');
require_once($r_path.'scripts/fnct_send_email.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once $r_path.'scripts/fnct_phpmailer.php';
require_once($r_path.'Connections/cp_connection.php');
mysql_select_db($database_cp_connection, $cp_connection);

ob_start();

//$conn_id = ftp_connect($ftp_server) or die("Couldn't connect"); 
//$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

$ExpiredDate = date("Y-m-d H:i:s", mktime(0,0,0,date("m")-3,date("d"),date("Y")));
$ExpireField = "event_end";

echo "Removing old events<br />".PHP_EOL;
//ftp_chdir($conn_id, "photographers");
//$Photogs = ftp_nlist($conn_id, ".");
$Photogs = scandir($r_path.'photographers');

$n = 0;
foreach($Photogs as $v){
	// if(trim($v) == "Wendy" || trim($v) == "www.thephotographictouch.com" || trim($v) == "yourcreativeimagephotography"){
	if($v == '.' || $v == '..') continue;	
	$n++;
	//if($n >= 5) exit;
	$dir1 = $r_path.'photographers/'.$v;
	//if(@ftp_chdir($conn_id, $v)){
	if(1 == 1){
		echo $v."<br />".PHP_EOL;
		//$Evnts = ftp_nlist($conn_id, ".");
		$Evnts = scandir($dir1);
		foreach($Evnts as $v2){
			if($v2 == '.' || $v2 == '..') continue;	
			$REMOVE=false;
			$WHERE=array();
			//if(@ftp_chdir($conn_id, $v2)){
			$dir2 = $r_path.'photographers/'.$v.'/'.$v2;
			if(is_dir($dir2)){
				//echo " -- Opening ".$v2."<br />".PHP_EOL;
				//$Grps = ftp_nlist($conn_id, ".");
				$Grps = scandir($dir2);
				
				foreach($Grps as $v3){
					if($v3 == '.' || $v3 == '..') continue;
					//if(@ftp_chdir($conn_id, $v3)){
					$dir3 = $r_path.'photographers/'.$v.'/'.$v2.'/'.$v3;
					if(is_dir($dir3)){
						//echo " -- Opening ".$v3."<br />".PHP_EOL;
						//$Flds = ftp_nlist($conn_id, ".");
						$Flds = scandir($dir3);
						foreach($Flds as $v4){
							if($v4 == '.' || $v4 == '..') continue;
							$dir4 = $r_path.'photographers/'.$v.'/'.$v2.'/'.$v3.'/'.$v4;
							if($v4 != "Large"){
								//if(@ftp_chdir($conn_id, $v4)){
								if(is_dir($dir4)){
									//echo " --- Opening ".$v4."<br />".PHP_EOL;
									$REMOVE=false;
									$WHERE=array();
									//$Imgs = ftp_nlist($conn_id, ".");
									$Imgs = scandir($dir4);
									
									//foreach($Imgs as $v5) $WHERE[] = "`image_tiny` = '".$v5."'";
									$Fold = "photographers/".$v."/".$v2."/".$v3."/".$v4."%";
									$query_check = "SELECT COUNT(`photo_event`.`event_id`) AS `count` 
										FROM `photo_event`
										INNER JOIN `photo_event_group`
											ON `photo_event_group`.`event_id` = `photo_event`.`event_id`
										INNER JOIN `photo_event_images`
											ON `photo_event_images`.`group_id` = `photo_event_group`.`group_id`
										WHERE (`".$ExpireField."` >= '".$ExpiredDate."' OR `event_use` = 'y' )
											AND `image_folder` LIKE '".str_replace("'","''",$Fold)."' ";
																														 //AND ( ".implode(" OR ",$WHERE)." ) ) 
													//."OR ( `event_num` = '".str_replace("'","''",$v2)."' AND `cust_handle` = '".$v."' )
													//)";
									//echo $query_check.PHP_EOL;
									if(count($Imgs)>0){
										$get_check = mysql_query($query_check, $cp_connection) or die(mysql_error());
										$row_get_check = mysql_fetch_assoc($get_check);
									} else {
										$row_get_check=array();
										$row_get_check['count']=0;
									}
									if($row_get_check['count']==0){
										echo $Fold." has expired<br />".PHP_EOL;
										//echo $query_check."<br />".PHP_EOL;
										$REMOVE=true;
										foreach($Imgs as $v5){
											if($v5 == '.' || $v5 == '..') continue;
											//echo " - Removing Image ".$v5.": Original";
											
											$dir5 = $r_path.'photographers/'.$v.'/'.$v2.'/'.$v3.'/'.$v4.'/'.$v5;
											//ftp_delete($conn_id, $v5);
									
											if(is_file($dir5))
												unlink($dir5);											
											$dir5 = $r_path.'photographers/'.$v.'/'.$v2.'/'.$v3.'/Large/'.$v5;
											if(is_file($dir5))
												unlink($dir5);
											
											//ftp_cdup($conn_id);
											//if(@ftp_chdir($conn_id, "Large")){
												//echo " - Large".PHP_EOL;
												//ftp_delete($conn_id, $v5);
												//ftp_cdup($conn_id);
											//}
											//ftp_chdir($conn_id, $v4);
										}
									}
									//ftp_cdup($conn_id);
									if($REMOVE==true){
										//if(@ftp_rmdir($conn_id, $v4))
										if(@rmdir($dir4))
											echo $Fold." Removed Folder ".$v4."<br />".PHP_EOL;
										
										//if(@ftp_rmdir($conn_id, "Large"))
										if(@rmdir($dir3.'/Large'))
											echo $Fold." Removed Folder Large<br />".PHP_EOL;
									}
								}
							}
						}
						//ftp_cdup($conn_id);
						if($REMOVE==true){
							//if(@ftp_rmdir($conn_id, $v3))
							if(@rmdir($dir3))
								echo $Fold." Removed Folder ".$v3."<br />".PHP_EOL;
						}
					}
				}		
				//ftp_cdup($conn_id); 
				if($REMOVE==true){
					//if(@ftp_rmdir($conn_id, $v2))
					if(@rmdir($dir2))
						echo $Fold." Removed Folder ".$v2."<br />".PHP_EOL;
				} else {
					echo "photographers/".$v."/".$v2." has NOT expired<br />".PHP_EOL;
				}
			}
			unset($REMOVE);
			unset($WHERE);
		}
		//ftp_cdup($conn_id);
	//}
	}
}

$page = ob_get_contents();
ob_end_clean();

send_email("development@proimagesoftware.com", "development@proimagesoftware.com", "Photoexpress CronJob: Image Removal", $page, true, false, false);
?>