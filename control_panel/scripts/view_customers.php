<?php include $r_path.'security.php';
$temppathing = $path[0].",".$path[1];
$pattern = "/Path=";
  for($n=0;$n<count($pathing);$n++){
  	$pattern .= ($n == 0) ? "[\\d\\w]*" : ",[\\d\\w]*" ;
  }
  $pattern .= "/";
?>

<div id="Tri_Nav">
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Over",$_SERVER['QUERY_STRING']);?>'"><p>Overview</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Proj",$_SERVER['QUERY_STRING']); ?>'"><p>Projects</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Inv",$_SERVER['QUERY_STRING']); ?>'"><p>Invoices</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Order",$_SERVER['QUERY_STRING']); ?>'"><p>Service Orders</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Contr",$_SERVER['QUERY_STRING']); ?>'"><p>Contracts</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Cont",$_SERVER['QUERY_STRING']); ?>'"><p>Contacts</p></td>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Bill",$_SERVER['QUERY_STRING']); ?>'"><p>Billing</p></td>
    </tr>
    <tr>
      <td align="center" class="Tabs" onclick="document.location.href='<?php echo $_SERVER['PHP_SELF']."?".preg_replace($pattern,"Path=".$temppathing.",Ship",$_SERVER['QUERY_STRING']); ?>'"><p>Shipping</p></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
<div id="Info">
  <?php include $r_path.'forms/customer_info.php'; ?>
</div>
<div id="Product_Content">
  <?php if ($pathing[2] == "Over"){
		include $r_path.'scripts/query_cust_overview.php';
	} else if ($pathing[2] == "Proj"){
		include $r_path.'scripts/query_cust_projects.php';
	} else if ($pathing[2] == "Inv"){
		include $r_path.'scripts/query_cust_invoices.php';
	} else if ($pathing[2] == "Order"){
		include $r_path.'scripts/query_cust_service.php';
	}?>
</div>
