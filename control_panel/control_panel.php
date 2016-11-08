<? 
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("PhotoExpress Pro", true);
define('Allow Scripts',true);
require_once $r_path.'scripts/fnct_get_variable.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
require_once $r_path.'../scripts/cart/ssl_paths.php';
require_once $r_path.'scripts/fnct_format_date.php';
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'config.php');
mysql_select_db($database_cp_connection, $cp_connection);
if($loginsession[1] >= 10 || (isset($_GET['admin']) && $_GET['admin'] == "true"))require_once($r_path.'scripts/get_user_information.php');
require_once($r_path.'scripts/fnct_find_parents.php');
require_once($r_path.'scripts/fnct_find_children.php');
$pathing = (isset($_POST['Path'])) ? explode(",",$_POST['Path']) : array("");
if(isset($_POST['form_path']) || isset($_POST['path'])){
	$path = (isset($_POST['form_path'])) ? clean_variable($_POST['form_path'],true) : ((isset($_POST['path'])) ? clean_variable($_POST['path'],true) : "Orders,Open");
	$cont = (isset($_POST['form_cont'])) ? clean_variable($_POST['form_cont'],true) : ((isset($_POST['cont'])) ? clean_variable($_POST['cont'],true) : "query");
	$sort = (isset($_POST['form_sort'])) ? clean_variable($_POST['form_sort'],true) : ((isset($_POST['sort'])) ? clean_variable($_POST['sort'],true) : "");
	$rcrd = (isset($_POST['form_rcrd'])) ? clean_variable($_POST['form_rcrd'],true) : ((isset($_POST['rcrd'])) ? clean_variable($_POST['rcrd'],true) : "");
} else {
	$path = (isset($_GET['form_path'])) ? clean_variable($_GET['form_path'],true) : ((isset($_GET['path'])) ? clean_variable($_GET['path'],true) : "Orders,Open");
	$cont = (isset($_GET['form_cont'])) ? clean_variable($_GET['form_cont'],true) : ((isset($_GET['cont'])) ? clean_variable($_GET['cont'],true) : "query");
	$sort = (isset($_GET['form_sort'])) ? clean_variable($_GET['form_sort'],true) : ((isset($_GET['sort'])) ? clean_variable($_GET['sort'],true) : "");
	$rcrd = (isset($_GET['form_rcrd'])) ? clean_variable($_GET['form_rcrd'],true) : ((isset($_GET['rcrd'])) ? clean_variable($_GET['rcrd'],true) : "");
}
$path = explode(",",$path);

$header = $pathing[0];
switch($header){
	case "Events":
		$header = "Events";
		break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<link rel="icon" href="http://www.proimagesoftware.com/photoexpress.ico" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="robots" content="index,follow" />
<meta name="keywords" content=""/>
<meta name="language" content="english"/>
<meta name="author" content="Pro Image Software" />
<meta name="copyright" content="2007" />
<meta name="reply-to" content="info@proimagesoftware.com" />
<meta name="document-rights" content="Copyrighted Work" />
<meta name="document-type" content="Web Page" />
<meta name="document-rating" content="General" />
<meta name="document-distribution" content="Global" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="Pragma" content="no-cache" />
<title>Photo Express Digital Studio</title>
<? if($loginsession[1] < 10 && (!isset($_GET['admin']) || $_GET['admin'] != "true")){ ?>
<link href="/control_panel/stylesheet/PhotoExpressAdmin.css" rel="stylesheet" type="text/css" />
<? } else { print '<link href="/control_panel/stylesheet/PhotoExpress.css" rel="stylesheet" type="text/css" />';}?>
<script type="text/javascript" src="/control_panel/javascript/set_tel_numbers.js"></script>
<script type="text/JavaScript">
<!--
function set_form(Form, Path, Cont, Sort, Rcrd){
	document.getElementById(Form+'path').value = Path;
	document.getElementById(Form+'cont').value = Cont;
	document.getElementById(Form+'sort').value = Sort;
	document.getElementById(Form+'rcrd').value = Rcrd;
	//document.getElementById(Form+'action_form').submit();
	document.location.href = '<? echo $_SERVER['PHP_SELF']; ?>?path='+Path+'&cont='+Cont+'&sort='+Sort+'&rcrd='+Rcrd+'<? echo $token; ?>';
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
<script type="text/javascript" src="/control_panel/javascript/body_size.js"></script>
</head>
<body onload="checkDiv(false);" onresize="checkDiv(false);">
<div id="Container">
 <div id="Header">
  <h1 id="Logo">
   <p>Photo Express Digital Pro</p>
  </h1>
  <p id="Top_Nav"><a href="#" onclick="javascript:set_form('','Contact,Comment','query','','');">Contact/comment Photo Express</a> | <a href="#" onclick="javascript:set_form('','Contact,Report','query','','');">Report
    Technical Issue</a> | <a href="#" onclick="javascript:set_form('','Contact,Request','query','','');">Request a Feature</a></p>
 </div>
 <div style="width:1401px;">
  <div id="Navigation">
   <? if($loginsession[1] < 10 && (!isset($_GET['admin']) || $_GET['admin'] != "true")){
	 		include $r_path.'scripts/a_admin_actions.php';
	  	require_once($r_path.'scripts/a_admin_nav.php');
	  } else {
	  	require_once($r_path.'scripts/a_photo_nav.php');
	  } ?>
   <form action="<? echo $_SERVER['PHP_SELF'].'?'.$QueryString.$token; ?>" name="action_form" id="action_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="path" id="path" value="<? echo implode(",",$path); ?>" />
    <input type="hidden" name="cont" id="cont" value="<? echo $cont; ?>" />
    <input type="hidden" name="sort" id="sort" value="<? echo $sort; ?>" />
    <input type="hidden" name="rcrd" id="rcrd" value="<? echo $rcrd; ?>" />
   </form>
   <br clear="all" />
  </div>
  <div id="Dotted_Line"></div>
  <div id="Content">
   <? if(isset($path) || isset($_GET['uploaded']) || isset($_GET['updated'])){ ?>
   <form action="<? echo $_SERVER['PHP_SELF'].'?'.$QueryString.$token; ?>" name="form_action_form" id="form_action_form" method="post" enctype="multipart/form-data">
    <? if($loginsession[1] < 10 && (!isset($_GET['admin']) || $_GET['admin'] != "true")){
				require_once($r_path.'scripts/a_admin_tree.php');
			} else {
				require_once($r_path.'scripts/a_photo_tree.php');
			}
			if(($cont == "add" || $cont == "edit" || $cont == "choose") && !defined("Aurigma Loading")){
			if(isset($required_string)){
				$onclick = '"MM_validateForm('.$required_string.'); if(document.MM_returnValue == true){document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();}"';
			} else {
				$onclick = '"document.getElementById(\'Controller\').value = \'Save\'; this.disabled=true; this.form.submit();"';
			}
			 ?>
    <div id="Save_Btn">
     <input type="button" name="btnSave" id="btnSave" value="Save Information" alt="Save Information" onmouseup=<? echo $onclick; ?> />
    </div>
    <? } ?>
    <input type="hidden" name="form_path" id="form_path" value="<? echo implode(",",$path); ?>" />
    <input type="hidden" name="form_cont" id="form_cont" value="<? echo $cont; ?>" />
    <input type="hidden" name="form_sort" id="form_sort" value="<? echo $sort; ?>" />
    <input type="hidden" name="form_rcrd" id="form_rcrd" value="<? echo $rcrd; ?>" />
    <input type="hidden" name="Time" id="Time" value="<? echo time(); ?>" />
    <input type="hidden" name="Controller" id="Controller" value="false" />
   </form>
   <? } ?>
  </div>
 </div>
 <br clear="all" />
</div>
</body>
</html>
