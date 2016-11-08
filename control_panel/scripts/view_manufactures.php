<?php include $r_path.'security.php'; 
require_once($r_path.'scripts/crumbs_pathing.php');
$temppathing = array();
if(count($pathing)>2){
	$temppathing = array_slice($pathing,0,-2);
} else {
	$temppathing = $pathing;
}
?>
<div id="Tri_Nav">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing),$query_string);?>'"><p>Overview</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Bill",$query_string);?>'"><p>Billing</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Ship",$query_string);?>'"><p>Shipping</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Whare",$query_string);?>'"><p>Wharehouse</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Cont",$query_string);?>'"><p>Contact</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Sale",$query_string);?>'"><p>Sales Rep</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace("/Path=[\\d\\w,]*/","Path=".implode(',',$temppathing).",".$_GET['id'].",Acct",$query_string);?>'"><p>Account Rep</p></td>
    </tr>
  </table>
</div>
<div id="Info">
  <?php include $r_path.'forms/manufactures_info.php'; ?>
</div>
<div id="Manufacture_Content">
  <?php if ($pathing[3] == "Bill"){
		include $r_path.'scripts/query_man_bill.php';
	} else if ($pathing[3] == "Ship"){
		include $r_path.'scripts/query_man_ship.php';
	} else if ($pathing[3] == "Whare"){
		include $r_path.'scripts/query_man_ware.php';
	} else if ($pathing[3] == "Cont"){
		include $r_path.'scripts/query_man_cont.php';
	} else if ($pathing[3] == "Sale"){
		include $r_path.'scripts/query_man_rep.php';
	} else if ($pathing[3] == "Acct"){
		include $r_path.'scripts/query_man_sale.php';
	}?>
</div>
