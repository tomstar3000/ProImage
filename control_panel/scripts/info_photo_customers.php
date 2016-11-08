<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
include $r_path.'FCKeditor/fckeditor.php';
include $r_path.'scripts/save_customers.php';
$temppathing = $path[0].",".$path[1];
$pattern = "/Path=";
  for($n=0;$n<count($pathing);$n++){
  	$pattern .= ($n == 0) ? "[\\d\\w]*" : ",[\\d\\w]*" ;
  }
  $pattern .= "/";
$is_enabled = ((count($path)<=3 && $cont == "view") || (count($path)>3)) ? false : true;
$is_back = ($cont == "edit") ? "view" : "query"; ?>
<div id="Sub_Header"> <img src="/control_panel/images/hdr_form_left.jpg" width="17" height="33" border="0" align="left" />
  <h2>Customer Information </h2>
  <p id="Back"><a href="#" onclick="javascript:set_form('form_','<? echo implode(",",array_slice($path,0,2)); ?>','<? echo $is_back; ?>','<? echo $sort; ?>','<? echo $rcrd; ?>');">Back</a></p>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="20%"><strong>Name: </strong></td>
    <td width="80%"><? echo $FName." ";
		if($MInt != "")echo $MInt.". "; 
		echo $LName." ";?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Address:</strong></td>
    <td><? echo $Add;
	 	if($SApt != "")	echo " Suite/Apt: ".$SApt;?>
      &nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><? echo $City.", ".$State.". ".$Zip; ?>
      &nbsp;</td>
  </tr>
  <tr>
    <td><strong>Phone:</strong></td>
    <td><? if($Phone != "0")echo "(".$P1.") ".$P2."-".$P3; ?>
      &nbsp;</td>
  </tr>
  
  <tr>
    <td><strong>E-mail:</strong></td>
    <td><a href="mailto:<? echo $Email; ?>"><? echo $Email; ?></a>
      &nbsp;</td>
  </tr>
</table>
