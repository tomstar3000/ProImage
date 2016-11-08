<?php

include $r_path.'security_2.php';
function DateFormat($Date){
	$Datepattern = array("\\",".",",","/");
	$Date = str_replace($Datepattern,"-",$Date);
	$noyear = false;
	if(eregi('209',$Date)){
		$FindYear = strpos($Date,"209");
	} else if(eregi('208',$Date)){
		$FindYear = strpos($Date,"208");
	} else if(eregi('207',$Date)){
		$FindYear = strpos($Date,"207");
	} else if(eregi('206',$Date)){
		$FindYear = strpos($Date,"206");
	} else if(eregi('205',$Date)){
		$FindYear = strpos($Date,"205");
	} else if(eregi('204',$Date)){
		$FindYear = strpos($Date,"204");
	} else if(eregi('203',$Date)){
		$FindYear = strpos($Date,"203");
	} else if(eregi('202',$Date)){
		$FindYear = strpos($Date,"202");
	} else if(eregi('201',$Date)){
		$FindYear = strpos($Date,"201");
	} else if(eregi('200',$Date)){
		$FindYear = strpos($Date,"200");
	} else if(eregi('199',$Date)){
		$FindYear = strpos($Date,"199");
	} else if(eregi('198',$Date)){
		$FindYear = strpos($Date,"198");
	} else if(eregi('197',$Date)){
		$FindYear = strpos($Date,"197");
	} else if(eregi('196',$Date)){
		$FindYear = strpos($Date,"196");
	} else if(eregi('195',$Date)){
		$FindYear = strpos($Date,"195");
	} else if(eregi('194',$Date)){
		$FindYear = strpos($Date,"194");
	} else if(eregi('193',$Date)){
		$FindYear = strpos($Date,"193");
	} else if(eregi('192',$Date)){
		$FindYear = strpos($Date,"192");
	} else if(eregi('191',$Date)){
		$FindYear = strpos($Date,"191");
	} else if(eregi('190',$Date)){
		$FindYear = strpos($Date,"190");
	} else if(eregi('189',$Date)){
		$FindYear = strpos($Date,"189");
	} else {
		$noyear = true;
	}
	if(!$noyear && strlen($Date)<=10 && substr_count($Date,'-')==2){
		if($FindYear == 0){
			// YYYY/MM/DD
			if(strlen($Date)==8){
				$Date = substr($Date,0,5)."0".substr($Date,5,2)."0".substr($Date,7);
			} else if (strlen($Date)==9){
				if(strrpos($Date,"-")==6){
					$Date = substr($Date,0,5)."0".substr($Date,5);
				} else {
					$Date = substr($Date,0,8)."0".substr($Date,8);
				}
			}
		} else if($FindYear <= 4) {
			$Date = "Error";
		} else {
			// MM/DD/YYYY
			if(strlen($Date)==8){
				$Month = "0".substr($Date,0,1);
				$Day = "0".substr($Date,2,1);
				$Year = substr($Date,4);
			} else if(strlen($Date)==9){
				if(strpos($Date,"-")==1){
					// M/DD/YYYY
					// 012345678
					$Month = "0".substr($Date,0,1);
					$Day = substr($Date,2,2);
					$Year = substr($Date,5); 
				} else {
					// MM/D/YYYY
					// 012345678
					$Month = substr($Date,0,2);
					$Day = "0".substr($Date,3,1);
					$Year = substr($Date,5); 				
				}
			} else {
				//MM/DD/YYYY
				//0123456789
				$Month = substr($Date,0,2);
				$Day = substr($Date,3,2);
				$Year = substr($Date,6); 
			}
			$Date = $Year."-".$Month."-".$Day;
		}
	} else {
		$Date = "Error";
	}
	return $Date;
}
?>