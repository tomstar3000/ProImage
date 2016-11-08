<?

$getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
$getInfo->mysql("SELECT `cust_id`, `cust_fname`, `cust_mint`, `cust_lname`, `cust_suffix`, `cust_cname`, `cust_desc`, `cust_desc_2`, `cust_add`, `cust_add_2`, `cust_suite_apt`, `cust_city`, `cust_state`, `cust_zip`, `cust_country`, `cust_phone`, `cust_cell`, `cust_fax`, `cust_work`, `cust_ext`, `cust_email`, `cust_website`, `cust_thumb`, `cust_image`, `cust_fcname`, `cust_fwork`, `cust_fext`, `cust_femail`, `cust_icon`,`cust_active`,`cust_paid` FROM `cust_customers` WHERE `cust_handle` = '$handle' LIMIT 0,1;");
$getInfo = $getInfo->Rows();
if (empty($getInfo)) {
    $GoTo = "/index.php?Error=We were unable to find that photographer.";
    header(sprintf("Location: %s", $GoTo));
}
$custid = $getInfo[0]['cust_id'];
$MetaTitle = (strlen($getInfo[0]['cust_cname']) > 0) ? $getInfo[0]['cust_cname'] : $getInfo[0]['cust_fname'] . " " . substr($getInfo[0]['cust_lname'], 0, 1) . ".";
/*
  if($getInfo[0]['cust_active'] == 'n'){
  $GoTo = "/index.php?Error=We were unable to find that photographer.";
  header(sprintf("Location: %s", $GoTo));
  }
 */
$launch_full = false;
?>