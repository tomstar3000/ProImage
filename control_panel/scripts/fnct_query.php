<?php require_once('../../Connections/cp_connection.php'); ?>
<?php

include $r_path.'security_2.php';
function sql_query($Query, $MaxRows, $PageNum, $TotalRows){
	global $cp_connection;
	$PageNum = $PageNum-1;
	if($MaxRows!=0){
		$StartRow = $PageNum * $MaxRows;
	}
	
	if($MaxRows > 0){
		$Limit = sprintf("%s LIMIT %d, %d", $Query, $StartRow, $MaxRows);
		$Fetch = mysql_query($Limit, $cp_connection) or die(mysql_error());
	} else {
		$Fetch = mysql_query($Query, $cp_connection) or die(mysql_error());
	}
	$Rows = mysql_fetch_assoc($Fetch);
	$NumRows = mysql_num_rows($Fetch);
	
	if ($TotalRows == 0) {
	  $All = mysql_query($Query);
	  $TotalRows = mysql_num_rows($All);
	}
	if($MaxRows!=0){
		$TotalPages = ceil($TotalRows/$MaxRows)-1;
	} else {
		$TotalPages = 0;
	}
	$i=1;
	$Records = array();
	$Records[0] = array('PageNum' => $PageNum, 'Rows' => $NumRows, 'TotalPages' => $TotalPages, 'TotalRows' => $TotalRows);
	do{
		foreach($Rows as $key => $value){
			$Records[$i][$key] = $value;
		}
		$i++;
	} while ($Rows = mysql_fetch_assoc($Fetch));
	mysql_free_result($Fetch);
	
	return $Records;
}
$MaxRows = 10;
if(isset($_GET['Total'])){
	$PageNum = $_GET['PageNum'];
} else {
	$PageNum = 1;
}
if(isset($_GET['Total'])){
	$TotalRows = $_GET['Total'];
} else {
	$TotalRows = 0;
}
mysql_select_db($database_cp_connection, $cp_connection);
$Query = "SELECT * FROM billship_shipping_rates";
$PageName = $_SERVER['PHP_SELF'];
$Records = array();
$Records = sql_query($Query, $MaxRows, $PageNum, $TotalRows);
?>

<table width="700">
  <tr>
    <td><a href="<?php echo $PageName."?PageNum=1&Total=".$Records[0]['TotalRows']; ?>">First</a> <a href="<?php echo $PageName."?PageNum=".max(($PageNum-1), 1)."&Total=".$Records[0]['TotalRows']; ?>">Previous</a></td>
    <td><?php echo (($Records[0]['PageNum']*$MaxRows) + 1) ?> - <?php echo ((($Records[0]['PageNum']*$MaxRows)) + $Records[0]['Rows']); ?> of <?php echo $Records[0]['TotalRows']; ?> </td>
    <td><a href="<?php echo $PageName."?PageNum=".min(($PageNum+1),($Records[0]['TotalPages']+1))."&Total=".$Records[0]['TotalRows']; ?>">Next</a> <a href="<?php echo $PageName."?PageNum=".($Records[0]['TotalPages']+1)."&Total=".$Records[0]['TotalRows']; ?>">Last</a></td>
  </tr>
  <?php 
  array_shift($Records);
  foreach($Records as $key => $value) { ?>
  <tr>
    <td colspan="3"><?php
	  	echo $Records[$key]['ship_rate_id']; 
	   ?></td>
  </tr>
  <?php } ?>
</table>
