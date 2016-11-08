<?php
include $r_path.'security_2.php';
function clean_variable($variable, $isnt_html = false){
	if(get_magic_quotes_gpc()){
		$variable = stripslashes($variable);
	}
	if($isnt_html === true){
		$variable = htmlentities($variable, ENT_QUOTES);
		$not_healthy = array(">","<","©","™","®");
		$healthy = array("&gt;","&lt;","&copy;","&trade;","&reg;");
		$variable = str_replace($not_healthy,$healthy,$variable);
	} else if($isnt_html == "Store") {
		$variable = str_replace("'","''",$variable);
	}
	return $variable;
}
function sanatizeentry($value, $is_html = false){
	if(!$is_html){
		$noaccept = array ("<",">","\r","\n","[\]r","[\]n","%0A","x0A","%0D","%20","x20","content-type:","charset=","mime-version:","multipart/mixed","bcc:","to:","from:","bc:","cc:","reply-to:");
		$replace = array(" "," ","","","","","","","","","","content","character set","version","mixed","carbon copy","to","from","carbon copy","carbon copy","reply to");
	} else {
		$noaccept = array ("\r","\n","[\]r","[\]n","%0A","x0A","%0D","%20","x20","content-type:","charset=","mime-version:","multipart/mixed","bcc:","to:","from:","bc:","cc:","reply-to:");
		$replace = array("","","","","","","","","","content","character set","version","mixed","carbon copy","to","from","carbon copy","carbon copy","reply to");

	}
	foreach($noaccept as $k => $v){
		do{
			$temp = strtolower($value);
			$pos = strpos($temp,$v);
			if($pos !== false){
				$value = substr($value,0,$pos).$replace[$k].substr($value,($pos+strlen($noaccept[$k])));
			}
		} while ($pos !== false);
	}
	return $value;
}
/*
function sanatizeentry($value, $is_html = false){
	if(!$is_html){
		$noaccept = array ("<",">","\r","\n","[\]r","[\]n","%0A","x0A","%0D","%20","x20","content-type:","charset=","mime-version:","multipart/mixed","bcc:","to:","from:","bc:","cc:","reply-to:");
		$replace = array(" "," ","","","","","","","","","","content","character set","version","mixed","carbon copy","to","from","carbon copy","carbon copy","reply to");
	} else {
		$noaccept = array ("\r","\n","[\]r","[\]n","%0A","x0A","%0D","%20","x20","content-type:","charset=","mime-version:","multipart/mixed","bcc:","to:","from:","bc:","cc:","reply-to:");
		$replace = array("","","","","","","","","","content","character set","version","mixed","carbon copy","to","from","carbon copy","carbon copy","reply to");

	}
	$value = str_replace($noaccept,$replace,strtolower($value));
	return (ucwords($value));
}
*/
?>