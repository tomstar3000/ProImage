<?
function cleanNames($VAL, $Type=1){
	$VAL = mb_convert_encoding($VAL,'UTF-8','HTML-ENTITIES'); // Convert to a UTF-8 format and drop HTML entities
	$VAL = removeSpecial($VAL,true,true);	// Additional Scrub to clean up HTML code
	$VAL = str_replace(array("&amp;","&#039;"),array("&","'"),$VAL); // Convert &amp; to & and &#039; to '
	if($Type==1){ // Store Name for the Database
		$VAL = preg_replace("/[^[:space:]&'A-Za-z0-9._-]/","",$VAL);
		return str_replace("'","''",$VAL);
	} else if($Type==2){ // Store Name for file structure
		return preg_replace("/[^[:space:]&'A-Za-z0-9._-]/","",$VAL);
	} else { // Only Alpha Numeric
		return preg_replace("[^A-Za-z0-9._-]","",$VAL);
	}
}
function incodeNames($VAL){
	return str_replace(array("&","'",'"'),array("&amp;","&#039;","&quot;"),$VAL);
}
function decodeNames($VAL){
	return str_replace(array("&amp;","&#039;"),array("&","'"),$VAL);
}
?>