<? require_once('../Connections/cp_connection.php'); ?>
<?
if(isset($_GET['info'])){
	$path = explode(",",$_GET['info']);
} else {
	$path = explode(",",$path);
}
if(count($path) == 1){
	$path[1] = $path[0];
}
$save = 'scripts/save_'.$path[0].'.php';
$form = 'forms/'.$path[0].'_info.php';
$pos = strrpos($path[1], "s");
if ($pos !== false && $pos == (strlen($path[1])-1)) {
   $path[1] = substr($path[1],0,-1);
}
$header = ucwords(str_replace("_"," ",$path[1]));
mysql_select_db($database_cp_connection, $cp_connection);
//define ("AevNet CONTROL PANEL", true);
//require_once 'config.php';
include 'security.php';
include $save;
include 'FCKeditor/fckeditor.php';
?>
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? echo $AevNet_Path; ?>: Control Panel</title>
<link href="stylesheet/<? echo $AevNet_Path; ?>.css" rel="stylesheet" type="text/css" />
-->
<? if($message){
	print("<script language=\"javascript\">alert(\"".$message."\");</script>\n");
}
?>
<script src="javascript/set_tel_numbers.js"></script>
<!-- 
</head>
<body>
-->
<div id="Info">
  <form method="post" action="<? echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" name="<? echo $header; ?> Information Form" id="<? echo $header; ?>_Information_Form" enctype="multipart/form-data">
    <div align="left">
      <? include $form; ?>
    </div>
	<? if($_GET['cont'] == "add" || $_GET['cont'] == "edit"){ ?>
    <div id="Save_Btn">
      <input type="submit" name="btnSave" id="btnSave" value="Save <? echo $header; ?> Information" alt="Save <? echo $header; ?> Information" onmouseup="this.disabled=true; this.form.submit();" />
      <input type="hidden" name="controller" id="controller" value="true" />
    </div>
	<? } ?>
  </form>
</div>
<!--
</body>
</html>
-->
