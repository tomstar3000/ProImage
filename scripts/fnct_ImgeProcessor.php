<?
class ImageProcessor{
	var $ftp_server = false;
	var $ftp_user_name = false;
	var $ftp_user_pass = false;
	var $Perform = false;
	var $MaxSize = 20971520;
	var $ChkError = false;
	var $ERROR = false;
	var $AllowFileTypes = array("image/gif","image/pjpeg","image/jpeg","image/x-png","image/png");
	var $TFile = array(0);
	var $Size = array(0);
	var $MimeType = array("");
	var $Ext = array("");
	var $OrigWidth = array(0);
	var $OrigHeight = array(0);
	var $CalcWidth = array(0);
	var $CalcHeight = array(0);
	var $Alpha = array(false);
	var $Indx=0;
	function ImageProcessor(){ // Start the Processor
		ini_set("memory_limit","128M");
		ini_set("max_execution_time","300");
	}
	function SetMaxSize($Size){ // Change the Max Allowed File Size
		$this->MaxSize = $Size;
	}
	function SetGifAlpha($Val,$Indx){ // Set Gif Alpha Blending
		$this->Alpha[Indx] = $Val;
	}
	function SetPerformance($Val){
		$this->Perform = $Val;
	}
	function checkErros($Val){
		$this->ChkError = $Val;
	}
	function Error(){ // Return Error
		return $this->ERROR;
	}
	function ChangeIndex($z){
		$this->Indx=$z;
	}
	function nextIndex(){
		return count($this->TFile);
	}
	function createBlank($Width, $Height, $Color, $Alph=100){		
		$TIMG = imagecreatetruecolor($Width, $Height);
		if($Alph<100){ // Track the Alpha Blending of the Image
			@imagealphablending($TIMG, true);   
			@imagesavealpha($TIMG, true);  
		}
		// Create our colors
		if ($Color[0] == '#') $Color = substr($Color, 1);
		if (strlen($Color) == 6){
			list($r, $g, $b) = array(	$Color[0].$Color[1],
																$Color[2].$Color[3],
																$Color[4].$Color[5]);
		} else if (strlen($Color)==3) {
			list($r, $g, $b) = array($Color[0], $Color[1], $Color[2]);
		}
		$r = hexdec($r);  $g = hexdec($g);  $b = hexdec($b);
		if($Alph<100) $Color = imagecolorallocatealpha($TIMG, $r, $g, $b, $Alph);
		else					$Color = imagecolorallocate($TIMG, $r, $g, $b);
		imagefill($TIMG, 0, 0, $Color);
		
		$this->Indx=$this->nextIndex();
		$this->Ext[$this->Indx] = ($Alph<100)?"png":"jpg";
		$this->Size[$this->Indx] = "10";
		$this->MimeType[$this->Indx] = ($Alph<100)?"image/x-png":"image/jpeg";
		$this->OrigWidth[$this->Indx] = $Width;
		$this->OrigHeight[$this->Indx] = $Height;
		
		$this->EndFile($TIMG); // Destroy the temparary Image
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function File($FilePath, $FileTemp = false, $z=0,$forceMime=false){ // Load File
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$this->Indx=$z;
		if($FileTemp === false){ // If we did not load a file from a form
			$FilePath = str_replace("&amp;","&",$FilePath);
			if(!is_file($FilePath)) { $this->ERROR = $FilePath." on Index ".$this->Indx.": That file doesn't exits!"; return; }
			$info = explode('.',$FilePath);
			$type = $info[(count($info)-1)];
			$this->Ext[$this->Indx] = $type;
			if($HDL = fopen($FilePath, "r")){ // Open File return error if any
				// Return File Buffer to Variable to be stored in tempfile
				$BUFFER = ''; 
				while (!feof($HDL)) $BUFFER .= fread($HDL, 8192);
				fclose($HDL);
				
				// Write the buffer to the temparary file
				$this->TFile[$this->Indx] = tempnam("/tmp", "TFile".$this->Indx);
				$HDL = fopen($this->TFile[$this->Indx], "w");
				fwrite($HDL, $BUFFER);
				fclose($HDL);
				
				// Set the Files Size
				$this->Size[$this->Indx] = filesize($FilePath);
				
				if($this->Size[$this->Indx]>$this->MaxSize){ // If the File is too large report error
					$this->ERROR = $FilePath." on Index ".$this->Indx.": Your file was too large; ".$this->ByteFormat($Size)." maximum allowed: ".$this->ByteFormat($this->MaxSize)."!";
					return;
				}
				// Get the Files Mime Type
				if($forceMime==false) $this->MimeType[$this->Indx] = $this->getType($this->TFile[$this->Indx]);
				else $this->MimeType[$this->Indx] = $forceMime;
			
				if(!in_array($this->MimeType[$this->Indx], $this->AllowFileTypes)){ // Return Error if Type is not allowed
					$this->ERROR = $FilePath." on Index ".$this->Indx.": With file type ".$this->MimeType[$this->Indx]." is not allowed!";
					return;
				}
				// Set the Files Original Height and Widths
				list($this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx]) = getimagesize($FilePath);
				if($this->OrigWidth[$this->Indx] == 0 || $this->OrigHeight[$this->Indx] == 0){
					$this->ERROR = $FilePath." on Index ".$this->Indx.": Unable to get the height and width of the image!";
					return;
				}
			} else {
				$this->ERROR = $FilePath." on Index ".$this->Indx.": There was an issue opening your file!";
			}
		} else {
			$info = explode('.',$FilePath['name']);
			$type = $info[(count($info)-1)];
			$this->Ext[$this->Indx] = $type;
			$this->TFile[$this->Indx] = $FilePath['tmp_name'];
			$this->Size[$this->Indx] = $FilePath['size'];
			$this->MimeType[$this->Indx] = $FilePath['type'];
			if(!in_array($this->MimeType[$this->Indx], $this->AllowFileTypes)){ // Return Error if Type is not allowed
				$this->ERROR = "On Index ".$this->Indx.": That file type is not allowed!";
				return;
			}
			// Set the Files Original Height and Widths
			$this->updSize();
			if($this->OrigWidth[$this->Indx] == 0 || $this->OrigHeight[$this->Indx] == 0){
				$this->ERROR = "On Index ".$this->Indx.": Unable to get the height and width of the image!";
				return;
			}
		}
	}
	function Kill(){ foreach($this->TFile as $r){ @unlink($r); } }
	function StartFile(){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		// Create Temparary Images with the Buffered Data
		if ($this->MimeType[$this->Indx] == "image/pjpeg" || $this->MimeType[$this->Indx] == "image/jpeg"){
			$TIMG = imagecreatefromjpeg($this->TFile[$this->Indx]);
		} else if ($this->MimeType[$this->Indx] == "image/gif") {
			$TIMG = imagecreatefromgif($this->TFile[$this->Indx]);
		} else if ($this->MimeType[$this->Indx] == "image/x-png" || $this->MimeType[$this->Indx] == "image/png") {
			$this->Alpha[$this->Indx] = true;
			$TIMG = imagecreatefrompng($this->TFile[$this->Indx]);
		} else {
			$this->ERROR = "There was an unexpected error number 801. Please contact you server administrator!";
		}
		if($this->Alpha[$this->Indx] == true){ // Track the Alpha Blending of the Image
			@imagealphablending($TIMG, true);   
			@imagesavealpha($TIMG, true);  
		}
		return $TIMG;
	}
	function updSize(){
		if($this->ERROR!==false){ echo $this->ERROR; exit(0); }
		list($this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx]) = getimagesize($this->TFile[$this->Indx]);
	}
	function EndFile($Image){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		if(is_file($this->TFile[$this->Indx])) unlink($this->TFile[$this->Indx]); // Remove the Temparary File
		$this->TFile[$this->Indx] = tempnam("/tmp", "TFile".$this->Indx); // Reset the Temparary File
		// Write Image to Temparary File
		if ($this->MimeType[$this->Indx] == "image/pjpeg" || $this->MimeType[$this->Indx] == "image/jpeg"){
			imagejpeg($Image,$this->TFile[$this->Indx], 95); // Was changed from 100 checking to see if this decreases files size but keeps same image quality.
			$this->updSize();
		} else if ($this->MimeType[$this->Indx] == "image/gif") {
			imagegif($Image,$this->TFile[$this->Indx]); // 9 removed
			$this->updSize();
		} else if ($this->MimeType[$this->Indx] == "image/x-png" || $this->MimeType[$this->Indx] == "image/png") {
			imagepng($Image,$this->TFile[$this->Indx], 0); // 9 changed to 0
			$this->updSize();
		} else {
			$this->ERROR = "There was an unexpected error number 802. Please contact you server administrator!";
		}
	}
	function CalcResize($Width, $Height, $Inner = false){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		if($this->OrigWidth[$this->Indx] == 0 || $this->OrigHeight[$this->Indx] == 0){
			$this->ERROR = "Unable to get the height and width of the image!";
			return;
		}
		if($Inner !== false){ // If we want to constrain on the smallest part of the image
			if ($this->OrigWidth[$this->Indx] > $this->OrigHeight[$this->Indx]){
				if($Height >= $Width) $Width = ceil(($Height / $this->OrigHeight[$this->Indx]) * $this->OrigWidth[$this->Indx]);
				else $Height = ceil(($Width / $this->OrigWidth[$this->Indx]) * $this->OrigHeight[$this->Indx]);
			} else {
				if($Height >= $Width) $Width = ceil(($Height / $this->OrigHeight[$this->Indx]) * $this->OrigWidth[$this->Indx]);
				else $Height = ceil(($Width / $this->OrigWidth[$this->Indx]) * $this->OrigHeight[$this->Indx]);
			}
		} else { // Or constrain on the largets
			if ($this->OrigWidth[$this->Indx] < $this->OrigHeight[$this->Indx]) $Width = ceil(($Height / $this->OrigHeight[$this->Indx]) * $this->OrigWidth[$this->Indx]);
			else $Height = ceil(($Width / $this->OrigWidth[$this->Indx]) * $this->OrigHeight[$this->Indx]);
		}
		// Set New Calculated Sizes of the Image
		$this->CalcWidth[$this->Indx] = $Width;
		$this->CalcHeight[$this->Indx] = $Height;
	}
	function CalcRotate($Degree){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		if($Degree < 0) $Degree = 360+$Degree; while($Degree >= 360) $Degree -= 360; 
		$swap = false; $return = false;
		if($Degree == 360){ 																		$return = true; }
		else if($Degree > 270){ 	$Degree -= 270; $swap = true;									}
		else if($Degree == 270){  $Degree -= 180; $swap = true; $return = true; }
		else if($Degree > 180){		$Degree -= 180;																}
		else if($Degree == 180){																$return = true; }
		else if($Degree > 90){		$Degree -= 90;	$swap = true;									}
		else if($Degree == 90){										$swap = true; $return = true; }
		
		if($swap){ $Temp = $this->CalcWidth[$this->Indx];
			$this->CalcWidth[$this->Indx] = $this->CalcHeight[$this->Indx];
			$this->CalcHeight[$this->Indx] = $Temp; }
		if($return) return; 
		$A1 = abs(sin($Degree)*$this->CalcWidth[$this->Indx]);
		$B1 = abs(cos($Degree)*$this->CalcWidth[$this->Indx]);
		$A2 = abs(sin($Degree)*$this->CalcHeight[$this->Indx]);
		$B2 = abs(cos($Degree)*$this->CalcHeight[$this->Indx]);
		$this->CalcHeight[$this->Indx] = round($A1+$B2); $this->CalcWidth[$this->Indx] = round($B1+$A2);
	}
	function Resize($Width, $Height, $Inner = false){ // Resize the Image
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		if($Inner !== false){ // If we want to constrain on the largest part of the image
			if ($this->OrigWidth[$this->Indx] > $this->OrigHeight[$this->Indx]){
				if($Height >= $Width) $Width = ceil(($Height / $this->OrigHeight[$this->Indx]) * $this->OrigWidth[$this->Indx]);
				else $Height = ceil(($Width / $this->OrigWidth[$this->Indx]) * $this->OrigHeight[$this->Indx]);
			} else {
				if($Height >= $Width) $Width = ceil(($Height / $this->OrigHeight[$this->Indx]) * $this->OrigWidth[$this->Indx]);
				else $Height = ceil(($Width / $this->OrigWidth[$this->Indx]) * $this->OrigHeight[$this->Indx]);
			}
		} else { // If we want to constrain on the smallest part of the image
			if ($this->OrigWidth[$this->Indx] < $this->OrigHeight[$this->Indx]) $Width = ceil(($Height / $this->OrigHeight[$this->Indx]) * $this->OrigWidth[$this->Indx]);
			else $Height = ceil(($Width / $this->OrigWidth[$this->Indx]) * $this->OrigHeight[$this->Indx]);
		}
		$TIMG = $this->StartFile();
		$Image = imagecreatetruecolor($Width,$Height); // Create Image
		if ($this->Alpha[$this->Indx] == true) {
			@imagealphablending($Image, true);
			@imagesavealpha($Image, true);
		}
		
		imagecopyresampled($Image,$TIMG, 0, 0, 0, 0, $Width, $Height, $this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx]); // Resample Image
		
		$this->EndFile($Image);
		// Set New Sizes of the Image
		$this->OrigWidth[$this->Indx] = $Width;
		$this->OrigHeight[$this->Indx] = $Height;
		
		imagedestroy($Image); // Destroy the temparary Image
		unset($TIMG);
	}
	function Crop($Width,$Height,$Pos=false,$X=0,$Y=0){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		// Set the destination of crop area on original image
		$Ratio = ($this->OrigWidth[$this->Indx] / $Width);
		$NHeight = round($Height * $Ratio);
		if ($NHeight > $this->OrigHeight[$this->Indx]){
			$Ratio = ($this->OrigHeight[$this->Indx] / $Height);
			$NHeight = $this->OrigHeight[$this->Indx];
			$NWidth = round($Width * $Ratio);
		} else {
			$NWidth = $this->OrigWidth[$this->Indx];
		}		
		if($Pos !== false){ // Set Crop Position
			$X = 0; $Y = 0;
			switch($Pos){
				case "TopLeft":				break;
				case "TopCenter":			$X = ceil(($this->OrigWidth[$this->Indx]/2)-($NWidth/2)); break;
				case "TopRight":  			$X = ceil($this->OrigWidth[$this->Indx]-$NWidth);	break;
				case "Left":		  		$Y = ceil(($this->OrigHeight[$this->Indx]/2)-($NHeight/2)); break;
				case "Center":    			$X = ceil(($this->OrigWidth[$this->Indx]/2)-($NWidth/2));
											$Y = ceil(($this->OrigHeight[$this->Indx]/2)-($NHeight/2)); break;
				case "Right":				$X = ceil($this->OrigWidth[$this->Indx]-$NWidth);
											$Y = ceil(($this->OrigHeight[$this->Indx]/2)-($NHeight/2)); break;
				case "BottomLeft":			$Y = ceil($this->OrigHeight[$this->Indx]-$NHeight); break;
				case "BottomCenter":		$X = ceil(($this->OrigWidth[$this->Indx]/2)-($NWidth/2));
											$Y = ceil($this->OrigHeight[$this->Indx]-$NHeight); break;
				case "BottomRight":			$X = ceil($this->OrigWidth[$this->Indx]-$NWidth);
											$Y = ceil($this->OrigHeight[$this->Indx]-$NHeight); break;
			}
		} else { // Check to make sure we are not trying to crop too much of the image
			if(($X+$NWidth) > $this->OrigWidth[$this->Indx]) $X -= (($X+$NWidth)-$this->OrigWidth[$this->Indx]);
			if(($Y+$NHeight) > $this->OrigHeight[$this->Indx])	$Y -= (($Y+$NHeight)-$this->OrigHeight[$this->Indx]);
		}			
		$TIMG = $this->StartFile();	
		$Image = imagecreatetruecolor($Width,$Height); // Create Image
		if ($this->Alpha[$this->Indx] == true) {
			@imagealphablending($Image, true);
			@imagesavealpha($Image, true);
		}
		/*
		if($this->Alpha[$this->Indx] == true){ // Set Alpha Blending
			$BG = imagecolorallocate($Image, 0, 0, 0);   
			@imagecolortransparent($Image, $BG);
		}
		*/
		//if($this->Perform==true)
		imagecopy($Image,$TIMG, 0, 0, $X, $Y, $Width, $Height); // Resample Image 
		//else imagecopyresampled($Image,$TIMG, 0, 0, $X, $Y, $Width, $Height, $NWidth, $NHeight); // Resample Image
		$this->EndFile($Image);
		
		// Set New Sizes of the Image
		$this->OrigWidth[$this->Indx] = $Width;
		$this->OrigHeight[$this->Indx] = $Height;
		
		imagedestroy($Image); // Destroy the temparary Image
		unset($TIMG);
	}
	function Rotate($Degree){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();	
		$Image = imagerotate($TIMG , $Degree, 0); // Rotate Image

		/*
		if($this->Alpha[$this->Indx] == true){ // Set Alpha Blending
			$BG = imagecolorallocate($Image, 0, 0, 0);   
			@imagecolortransparent($Image, $BG);   
			@imagealphablending($Image, true);   
			@imagesavealpha($Image, true);
		} */
		$this->EndFile($Image);
		imagedestroy($Image); // Destroy the temparary Image
		unset($TIMG);
	}
	function Gray($VAL = 0){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		$Image = $this->StartFile();
		// Alternative IMG_FILTER_GRAYSCALE: Converts the image into grayscale. 
		/* 
		if($this->Alpha[$this->Indx] == true){ // Set Alpha Blending
			$BG = imagecolorallocate($Image, 0, 0, 0);   
			@imagecolortransparent($Image, $BG);   
			@imagealphablending($Image, true);   
			@imagesavealpha($Image, true);
		} */
		imagecopymergegray($Image, $TIMG, 0, 0, 0, 0, $this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx], intval($VAL));
		
		$this->EndFile($Image);
		imagedestroy($Image); // Destroy the temparary Image
		unset($TIMG);
	}
	function Sepia($VAL = 0){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		for ($y = 0; $y <$this->OrigHeight[$this->Indx]; $y++){ 
			for ($x = 0; $x <$this->OrigWidth[$this->Indx]; $x++){ 
				$rgb = imagecolorat($TIMG, $x, $y);
				$red   = ($rgb >> 16) & 0xFF;
				$green = ($rgb >> 8)  & 0xFF;
				$blue  = $rgb & 0xFF;

				//sephia
				$red2 = min($red*.398 + $green*.774 + $blue*.194,255);
				$green2 = min($red*.344 + $green*.681 + $blue*.163,255);
				$blue2  = min($red*.272 + $green*.534 + $blue*.131,255);
				
				// shift gray level to the left
				$grayR = $red2 << 16;   		// R: red
				$grayG = $green2 << 8 ;    	// G: green
				$grayB = $blue2;         		// B: blue
				
				// OR operation to compute gray value
				$grayColor = $grayR | $grayG | $grayB;
				
				// set the pixel color
				imagesetpixel ($TIMG, $x, $y, $grayColor);
				imagecolorallocate ($TIMG, $gray, $gray, $gray);
			}
    }
		// copy pixel values to new file buffer
    $Image = imagecreatetruecolor($this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx]);
		if ($this->Alpha[$this->Indx] == true) {
			@imagealphablending($Image, true);
			@imagesavealpha($Image, true);
		}
    imagecopy($Image, $TIMG, 0, 0, 0, 0, $this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx]);
		
		if(intval($VAL) > 0){
			$TEMP = tempnam("/tmp", "TFile".$this->Indx);
			if ($this->MimeType[$this->Indx] == "image/pjpeg" || $this->MimeType[$this->Indx] == "image/jpeg"){
				imagejpeg($Image,$TEMP, 100);
			} else if ($this->MimeType[$this->Indx] == "image/gif") {
				imagegif($Image,$TEMP, 100);
			} else if ($this->MimeType[$this->Indx] == "image/x-png" || $this->MimeType[$this->Indx] == "image/png") {
				imagepng($Image,$TEMP, 100);
			} else {
				$this->ERROR = "There was an unexpected error number 803. Please contact you server administrator!";
			}
			imagedestroy($Image);
			if ($this->MimeType[$this->Indx] == "image/pjpeg" || $this->MimeType[$this->Indx] == "image/jpeg"){
				$Image = imagecreatefromjpeg($TEMP);
			} else if ($this->MimeType[$this->Indx] == "image/gif") {
				$Image = imagecreatefromgif($TEMP);
			} else if ($this->MimeType[$this->Indx] == "image/x-png" || $this->MimeType[$this->Indx] == "image/png") {
				$this->Alpha[$this->Indx] = true;
				$Image = imagecreatefrompng($TEMP);
			} else {
				$this->ERROR = "There was an unexpected error number 804. Please contact you server administrator!";
			}
			unlink($TEMP);
			/* 
			if($this->Alpha[$this->Indx] == true){ // Track the Alpha Blending of the Image
				@imagealphablending($Image, true);   
				@imagesavealpha($Image, true);  
			}
			if($this->Alpha[$this->Indx] == true){ // Set Alpha Blending
				$BG = imagecolorallocate($Image, 0, 0, 0);   
				@imagecolortransparent($Image, $BG);   
				@imagealphablending($Image, true);   
				@imagesavealpha($Image, true);
			} */
			imagecopymerge($Image, $TIMG, 0, 0, 0, 0, $this->OrigWidth[$this->Indx], $this->OrigHeight[$this->Indx], intval($VAL));
		}
		$this->EndFile($Image);
		imagedestroy($Image); // Destroy the temparary Image
		unset($TIMG);
	}
	function Britness($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_BRIGHTNESS, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function Contrast($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_CONTRAST, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function Colorize($R, $G, $B, $A = 0){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_COLORIZE, $R, $G, $B);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function Inverse(){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_NEGATE);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function EdgeDetect(){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_EDGEDETECT);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function Emboss($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_EMBOSS, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function GussianBlur($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_GAUSSIAN_BLUR, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function SelectiveBlur($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_SELECTIVE_BLUR, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function MeanRemoval($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_MEAN_REMOVAL, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function Smooth($VAL){ // -255 to +255
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
		imagefilter($TIMG, IMG_FILTER_SMOOTH, $VAL);
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function Overlay($From, $To, $X=0, $Y=0, $ToFrmt="jpg"){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$this->Indx = $From;
		$TIMG1 = $this->StartFile();
		$TExt = $this->Ext[$this->Indx];
		$TSize = $this->Size[$this->Indx];
		$TMimeType = $this->MimeType[$this->Indx];
		$TWidth = $this->OrigWidth[$this->Indx];
		$THeight = $this->OrigHeight[$this->Indx];
			
		$this->Indx = $To;
		$TIMG2 = $this->StartFile();
		
		//if($this->Perform==true) 
		//imagecopymerge($TIMG2,$TIMG1,$X,$Y,0,0,$this->OrigWidth[$From],$this->OrigHeight[$From],$Alph);
		imagecopy($TIMG2, $TIMG1,$X,$Y,0,0,$this->OrigWidth[$From],$this->OrigHeight[$From]);
		//else imagecopyresampled($TIMG2, $TIMG1,$X,$Y,0,0,$this->OrigWidth[$From],$this->OrigHeight[$From],$this->OrigWidth[$From],$this->OrigHeight[$From]);
		
		$this->Indx=$this->nextIndex();
		$this->Ext[$this->Indx] = $TExt;
		$this->Size[$this->Indx] = $TSize;
		$this->MimeType[$this->Indx] = $this->switchType($ToFrmt);
		$this->OrigWidth[$this->Indx] = $TWidth;
		$this->OrigHeight[$this->Indx] = $THeight;
		
		$this->EndFile($TIMG2); // Destroy the temparary Image
		imagedestroy($TIMG1); // Destroy the temparary Image
	}
	function TextBox($Color,$Size,$Font,$Text,$X=0,$Y=0,$Ang=0,$Alph=100){// Text box $Text = array
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$TIMG = $this->StartFile();
				
		if ($Color[0] == '#') $Color = substr($Color, 1);
		if (strlen($Color) == 6){
			list($r, $g, $b) = array(	$Color[0].$Color[1],
																$Color[2].$Color[3],
																$Color[4].$Color[5]);
		} else if (strlen($Color)==3) {
			list($r, $g, $b) = array($Color[0], $Color[1], $Color[2]);
		}
		$r = hexdec($r);  $g = hexdec($g);  $b = hexdec($b);
		if($Alph<100) $Color = imagecolorallocatealpha($TIMG, $r, $g, $b, $Alph);
		else					$Color = imagecolorallocate($TIMG, $r, $g, $b);
		
		$Dem = $this->calcText($Size,$Font,"Ap");
		
		$Height = abs($Dem[5]-$Dem[3]);
		$Y += $Height;
		$X += $Dem[0];
		
		for($n=0; $n<count($Text); $n++){
			$NY=$Y+($n*($Height+$Size));
			imagettftext($TIMG, $Size, $Ang, $X, $NY, $Color, $Font, mb_convert_encoding(urldecode($Text[$n]),'UTF-8','HTML-ENTITIES'));
		}
		
		$this->EndFile($TIMG);
		imagedestroy($TIMG); // Destroy the temparary Image
	}
	function calcText($Size, $Font, $Text, $Ang=0){
		return imagettfbbox($Size,$Ang,$Font,$Text);
	}
	function OutputBuffer(){ // Outbut buffer to the browser
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		header("Cache-Control: private");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Pragma: private"); 
		header('Content-type: '.$this->MimeType[$this->Indx]);
		header('Content-Length: '. filesize($this->TFile[$this->Indx]));
		
		$HND = fopen($this->TFile[$this->Indx], "r");
		while (!feof($HND)) echo fread($HND, 8192);
		fclose($HND);
		
		unlink($this->TFile[$this->Indx]); // Make sure to remove the temparary file from the server.
		
		// Reset Our INI
		ini_restore("memory_limit"); 
		ini_restore("max_execution_time");
		
		exit(0);
	}
	function OutputContents(){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		ob_start();
	
		$HND = fopen($this->TFile[$this->Indx], "r");
		while (!feof($HND)) echo fread($HND, 8192);
		fclose($HND);
		
		$buffer = ob_get_contents();
		ob_end_clean();

		unlink($this->TFile[$this->Indx]); // Make sure to remove the temparary file from the server.
		
		// Reset Our INI
		ini_restore("memory_limit"); 
		ini_restore("max_execution_time");
		
		return $buffer;
	}
	function OutputServer($Name,$Folder,$FTPIp=false,$FTPUser=false,$FTPPass=false,$FTPPassive=true){
		if($this->ChkError == true && $this->ERROR!==false){ echo $this->ERROR; exit(0); }
		$Fatal = false;
		if($FTPIp !== false){
			if($conn_id = ftp_connect($FTPIp)){
				ftp_pasv ($conn_id,((isset($FTPPassive))?$FTPPassive:true));
				if($result = ftp_login ($conn_id, $FTPUser, $FTPPass)){
					$Folder = explode("/",$Folder); $n=0;
					while($n < count($Folder)){
						if(ftp_chdir($conn_id,$Folder[$n])){
							$Fatal = false;
						} else {
							if (ftp_mkdir($conn_id, $Folder[$n])) {
								if(ftp_chdir($conn_id,$Folder[$n])){
									$Fatal = false;
								} else {
									$Fatal = true;
									$this->ERROR = "There was a problem while creating ".$Folder[$n];
								}
							} else {
								$Fatal = true;
								$this->ERROR = "There was a opening ".$Folder[$n];
							}
						}
						$n++;
					}
					if($Fatal==false){
						if(!ftp_put($conn_id, $Name, $this->TFile[$this->Indx], FTP_BINARY)){
							$this->ERROR = "There was a problem uploading file ".$Name;
						}
					}
					ftp_close($conn_id);
				} else {
					$this->ERROR = "Couldn't connect to $FTPIp";
				}
			} else {
				$this->ERROR = "Couldn't connect to $FTPIp";
			}
		} else {
			if(!copy($this->TFile[$this->Indx], $Folder."/".$Name)){
				$this->ERROR = "There was a problem uploading file ".$Name;
			}
		}
		
		unlink($this->TFile[$this->Indx]); // Make sure to remove the temparary file from the server.
		
		// Reset Our INI
		ini_restore("memory_limit"); 
		ini_restore("max_execution_time");
	}
	function ByteFormat($bytes){ // Display File size in something that makes sense
    $symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
    $exp = 0; $converted_value = 0;
    if( $bytes > 0 ){
      $exp = floor( log($bytes)/log(1024) );
      $converted_value = ( $bytes/pow(1024,floor($exp)) );
    }
		return sprintf( '%.2f '.$symbol[$exp], $converted_value );
  }
	function getType($filename){ // Function to return the Mimi Type of a file
		if (!function_exists('mime_content_type')) {
			if(!function_exists('finfo_open')){
				function mime_content_type($filename) {
					exec("identify $filename",$out);
					if(!empty($out)){
						$info = $out[0];
						$info = explode(' ',$out[0]);
						$type = $info[1];
					} else { 
						$type = trim(exec('file -bi '.escapeshellarg($filename)));
					}
					return $this->switchType($type);
				}
			} else {
				function mime_content_type($filename) {
					$finfo = finfo_open(FILEINFO_MIME);
					finfo_file($finfo, $filename);
					finfo_close($finfo);
				}
			}
		}
		return mime_content_type($filename);
	}
	function switchType($type){
		switch(strtolower($type)){ // Switch to change file extention to Mime Type.  Populare extensions not all.
			case "jpe":  return "image/jpeg"; break;
			case "jpeg": return "image/jpeg"; break;
			case "jpg":  return "image/jpeg"; break;
			case "gif":  return "image/gif"; break;
			case "png":  return "image/x-png"; break;
			default: return "text/plain"; break;
		}
	}
}
?>
