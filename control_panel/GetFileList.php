<?php
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
//This is a base server folder for subfolders, between which the user can choose
$strBasePath = realpath($r_path);
//This is a default subfolder from which files will be downloaded
$strFolderPath = "toPhatFoto/";
require_once($strBasePath.'/Connections/cp_connection.php');
//Construct and encode a URL of the supplied file for reliable HTTP transmission to the client
function EncodeFileName($name){
	global $strFolderPath;
	global $r_path;
	$index = strpos($name,$strFolderPath);
	return str_replace("%2F", "/", rawurlencode($r_path.substr($name,$index)));
}
header("Content-Type: text/plain;charset=utf-8");
if(isset($_SERVER['QUERY_STRING'])){
	parse_str($_SERVER['QUERY_STRING']);
	while(strlen($id) < 5){
		$id = "0".$id;
	}
}
$conn_id = ftp_connect($ftp_server);
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

function ftp_All($start_dir) {
	$files = array();
  if (is_dir($start_dir)) {
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false) {
      if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
      $filepath = $start_dir.'/'.$file;
      if (is_dir($filepath))
        $files = array_merge($files, ftp_All($filepath));
      else
        array_push($files, $filepath);
    }
    closedir($fh);
  } else {
    $files = false;
  }
  return $files;
}
//Check if the selected subfolder exists
if (file_exists($strBasePath."/".$strFolderPath.$id)){
	//Iterate through all files in the subfolder and build a file list
	$objDir = ftp_All(($strBasePath."/".$strFolderPath.$id));
	foreach ($objDir as $objFile){
		if ($objFile != "." && $objFile != ".."){
			$path_parts = pathinfo($objFile);
			//Add the MIME type, the size, and the name for saving
			$index = strpos($objFile,$strFolderPath);
			//$path_parts['basename']
			echo "*/* | " . filesize($objFile) . " | " . substr($objFile,$index+strlen($strFolderPath)) . " | ";
			//Add the URL to the file and the CRLF combination
			echo EncodeFileName($objFile) . "\r\n";
		}
	}
}

?>