<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
$is_enabled = ($cont == "view") ? false : true;
$is_back = ($cont == "edit") ? "view" : "query";?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <th width="100%" id="Form_Header"><p>Keyword</p></th>
 </tr>
 <tr>
  <td><textarea name="Keywords" id="Keywords" style="height:500px; width:98%"><? echo $keywords; ?></textarea></td>
 </tr>
</table>
