<?
function loadimage($MaxSize, $Fname, $Iname, $ISize, $ITemp, $IType, $Folder, $IWidth, $IHeight, $crop, $resize, $use_ftp, $conn_id){
	if ($ISize > $MaxSize) {
		$Loaded = array();
		$Loaded[0] = false;
		$Loaded[1] = $Fname." - is too large";
		unlink($ITemp);
	} else {
		if ($IType != "image/gif" && $IType != "image/pjpeg" && $IType != "image/jpeg" && $IType != "image/x-png" && $IType != "image/png") {
			$Loaded = array();
			$Loaded[0] = false;
			$Loaded[1] = $Fname." - That file type is not allowed";
			unlink($ITemp);
		} else {
			list($IWidth_orig, $IHeight_orig) = getimagesize($ITemp);
			if($crop){
				if($resize){
					if($IHeight_orig < $IWidth_orig){
					   $Ratio = ($IHeight_orig / $IHeight);
					   $IWidth_new = round($IWidth * $Ratio);
					   if ($IWidth_new > $IWidth_orig) {
						   $Ratio = ($IWidth_orig / $IWidth);
						   $IWidth_new = $IWidth_orig;
						   $IHeight_new = round($IHeight * $Ratio);
						   $StartX = 0;
						   $StartY = round(($IHeight_orig - $IHeight_new) / 2);
					   } else {
						   $IHeight_new = $IHeight_orig;
						   $StartX = round(($IWidth_orig - $IWidth_new) / 2);
						   $StartY = 0;
					   }
					} else {
					   $Ratio = ($IWidth_orig / $IWidth);
					   $IHeight_new = round($IHeight * $Ratio);
					   if ($IHeight_new > $IHeight_orig){
						   $Ratio = ($IHeight_orig / $IHeight);
						   $IHeight_new = $IHeight_orig;
						   $IWidth_new = round($IWidth * $Ratio);
						   $StartX = round(($IWidth_orig - $IWidth_new) / 2);
						   $StartY = 0;
					   } else {
						   $IWidth_new = $IWidth_orig;
						   $StartX = 0;
						   $StartY = round(($IHeight_orig - $IHeight_new) / 2);
					   }
					}
				} else {
					$StartX = ($IWidth_orig/2)-($IWidth/2); 
					$StartY = ($IHeight_orig/2)-($IHeight/2);
					$IWidth_new = $IWidth;
					$IHeight_new = $IHeight;
				}
			} else {
				$StartX = 0; 
				$StartY = 0;
				$IWidth_new = $IWidth_orig;
				$IHeight_new = $IHeight_orig;
				if ($IWidth && ($IWidth_orig < $IHeight_orig)) {
				   $IWidth = ($IHeight / $IHeight_orig) * $IWidth_orig;
				} else {
				   $IHeight = ($IWidth / $IWidth_orig) * $IHeight_orig;
				}
			}
			$Image = imagecreatetruecolor($IWidth,  $IHeight);
			if ($IType == "image/pjpeg" || $IType == "image/jpeg"){
				$TImage = imagecreatefromjpeg($ITemp);
			} else if ($IType == "image/gif") {
				$TImage = imagecreatefromgif($ITemp);
			} else if ($IType == "image/x-png" || $IType == "image/png") {
				//$background = imagecolorallocate($Image, 0, 0, 0);
				//imagecolortransparent($Image, $background);
				$TImage = imagecreatefrompng($ITemp);
				imagealphablending($Image, false);
			}
			
			imagecopyresampled($Image, $TImage, 0, 0, $StartX, $StartY, $IWidth, $IHeight, $IWidth_new, $IHeight_new);
			$Image_temp = tempnam ("/tmp", "TEMP");

			if ($IType == "image/pjpeg" || $IType == "image/jpeg"){
				imagejpeg($Image,$Image_temp, 100);
			} else if ($IType == "image/gif") {
				imagegif($Image,$Image_temp, 100);
			} else if ($IType == "image/x-png" || $IType == "image/png") {
				//imagesavealpha($Image, true);
				//imagepng($Image,$Image_temp, 100);
			}
			if($use_ftp === true){
				if($IType == "image/x-png" || $IType == "image/png"){
					ftp_put($conn_id, $Folder."/".$Iname, $ITemp, FTP_BINARY);
				} else {
					ftp_put($conn_id, $Folder."/".$Iname, $Image_temp, FTP_BINARY);
				}
			} else {
				if($IType == "image/x-png" || $IType == "image/png"){
					copy($ITemp, $Folder."/".$Iname);
				} else {
					copy($Image_temp, $Folder."/".$Iname);
				}
			}
			imagedestroy($TImage);
			//unlink($ITemp);
			$Loaded = array();
			$Loaded[0] = true;
			$Loaded[1] = $Iname;
		}
	}
	return $Loaded;
}
?>