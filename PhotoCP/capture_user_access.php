<?
  require_once('../Connections/cp_connection.php');
  $my_id = $_SESSION['AdminLog'][2];
  $update = "UPDATE `cust_customers` SET `cust_accessed` = NOW() WHERE `cust_id` = '$my_id'";
  $updateinfo = mysql_query($update, $cp_connection) or die(mysql_error());
?>