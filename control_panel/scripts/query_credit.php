<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++) $r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$Accept = (isset($_POST['Accept'])) ? $_POST['Accept'] : array();
$CCIds = (isset($_POST['CC_Id'])) ? $_POST['CC_Id'] : array();
$Order = (isset($_POST['Order'])) ? $_POST['Order'] : array();
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	foreach($CCIds as $k => $v){
		$v = clean_variable($v, true);
		$o = clean_variable($Order[$k], true);
		if(array_search($v, $Accept) !== false){
			$upd = "UPDATE `billship_cc_types` SET `cc_accept` = 'y', `cc_order` = '$o' WHERE `cc_type_id` = '$v'";
		} else {
			$upd = "UPDATE `billship_cc_types` SET `cc_accept` = 'n', `cc_order` = '$o' WHERE `cc_type_id` = '$v'";
		}
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
}
?>

<div align="left">
  <div id="Form_Header"><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Accepted Credit Cards</p>
    <br clear="all" />
  </div>
  <p>
    <?php	
	$query_get_ccs = "SELECT * FROM `billship_cc_types` ORDER BY `cc_type_name` ASC";
	$get_ccs = mysql_query($query_get_ccs, $cp_connection) or die(mysql_error());	
	while ($row_get_ccs = mysql_fetch_assoc($get_ccs)){ ?>
    <input type="checkbox" name="Accept[]" id="Accept[]" value="<?php echo $row_get_ccs['cc_type_id']; ?>"<?php if($row_get_ccs['cc_accept']=="y"){print(" checked=\"checked\""); } ?> />
    <input name="Order[]" type="text" id="Order[]" value="<? echo $row_get_ccs['cc_order']; ?>" size="3" maxlength="2" />
	<input type="hidden" name="CC_Id[]" id="CC_Id[]" value="<?php echo $row_get_ccs['cc_type_id']; ?>" />
    <?php echo $row_get_ccs['cc_type_name']; ?> <br />
    <?php } mysql_free_result($get_ccs);?>
  </p>
</div>
