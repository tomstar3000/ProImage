<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$Acc_Comp = (isset($_POST['Accept'])) ? $_POST['Accept'] : array();
$CompIds = (isset($_POST['Comp_Id'])) ? $_POST['Comp_Id'] : array();
$Acc_Speed = (isset($_POST['Accept_Speed'])) ? $_POST['Accept_Speed'] : array();
$SpeedIds = (isset($_POST['Speed_Id'])) ? $_POST['Speed_Id'] : array();
$Prices = (isset($_POST['Speed_Price'])) ? $_POST['Speed_Price'] : array();
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	foreach($CompIds as $k => $v){
		$v = clean_variable($v, true);
		if(array_search($v, $Acc_Comp) !== false){
			$upd = "UPDATE `billship_shipping_companies` SET `ship_comp_us` = 'y' WHERE `ship_comp_id` = '$v'";
		} else {
			$upd = "UPDATE `billship_shipping_companies` SET `ship_comp_us` = 'n' WHERE `ship_comp_id` = '$v'";
		}
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
	foreach($SpeedIds as $k => $v){
		$v = clean_variable($v, true);
		$p = clean_variable($Prices[$k], true);
		if(array_search($v, $Acc_Speed) !== false){
			$upd = "UPDATE `billship_shipping_speeds` SET `ship_speed_price` = '$p', `ship_speed_us` = 'y' WHERE `ship_speed_id` = '$v'";
		} else {
			$upd = "UPDATE `billship_shipping_speeds` SET `ship_speed_price` = '$p', `ship_speed_us` = 'n' WHERE `ship_speed_id` = '$v'";
		}
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
}
?>

<div align="left">
  <div id="Form_Header"><img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
    <p>Accepted Shipping Companies </p>
    <br clear="all" />
  </div>
  <table border="1" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <?php	$query_get_ship = "SELECT * FROM `billship_shipping_companies` ORDER BY `ship_comp_name` ASC";
	$get_ship = mysql_query($query_get_ship, $cp_connection) or die(mysql_error());	
	$a = 0;
	while ($row_get_ship = mysql_fetch_assoc($get_ship)){ 
	if($a == 2) {echo "</tr><tr>"; $a=0;} $a++;
	?>
      <td valign="top" width="25%"><table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td><input type="checkbox" name="Accept[]" id="Accept[]" value="<?php echo $row_get_ship['ship_comp_id']; ?>"<?php if($row_get_ship['ship_comp_us']=="y"){print(" checked=\"checked\""); } ?> />
              <input type="hidden" name="Comp_Id[]" id="Comp_Id[]" value="<?php echo $row_get_ship['ship_comp_id']; ?>" />
              <?php echo $row_get_ship['ship_comp_name']; ?> </td>
          </tr>
          <tr>
            <td><hr size="1" /></td>
          </tr>
          <?php
			$comp_id = $row_get_ship['ship_comp_id'];
			$query_get_speed = "SELECT * FROM `billship_shipping_speeds` WHERE `ship_comp_id` = '$comp_id' ORDER BY `ship_speed_name` ASC";
			$get_speed = mysql_query($query_get_speed, $cp_connection) or die(mysql_error());
			$totalRows_get_speed = mysql_num_rows($get_speed);
			if($totalRows_get_speed > 0){
				while ($row_get_speed = mysql_fetch_assoc($get_speed)){	?>
          <tr>
            <td><input type="checkbox" name="Accept_Speed[]" id="Accept_Speed[]" value="<?php echo $row_get_speed['ship_speed_id']; ?>"<?php if($row_get_speed['ship_speed_us']=="y"){print(" checked=\"checked\""); } ?> />
              <input type="hidden" name="Speed_Id[]" id="Speed_Id[]" value="<?php echo $row_get_speed['ship_speed_id']; ?>" />
              <?php echo $row_get_speed['ship_speed_name']; ?>  $
              <input type="text" name="Speed_Price[]" id="Speed_Price[]" value="<?php echo ($row_get_speed['ship_speed_price']!=0)?number_format($row_get_speed['ship_speed_price'],2,".",","):""; ?>" size="6" /></td>
          </tr>
          <?php }	}	mysql_free_result($get_speed); ?>
        </table></td>
      <?php	} mysql_free_result($get_ship); ?>
    </tr>
  </table>
</div>
