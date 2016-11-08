<div id="Form_Header"> <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
 <p>Publishing Website </p>
 <br clear="all">
</div>
<? 
echo '<div align="left"><p>Dumping Home Page Information. </p></div>';
$truncatetable = "TRUNCATE TABLE `web_home_info`";
$truncatetableinfo = mysql_query($truncatetable, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Retrieving Reviewed Home Page Information. </p></div>';
$query_get_records = "SELECT * FROM `web_review_home_info`";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

$PId = clean_variable($row_get_records['home_id'],'Store');
$PH = clean_variable($row_get_records['home_header'],'Store');
$PH2 = clean_variable($row_get_records['home_header_2'],'Store');
$PT = clean_variable($row_get_records['home_tag'],'Store');
$PT2 = clean_variable($row_get_records['home_tag_2'],'Store');
$PText = clean_variable($row_get_records['home_text'],'Store');

$query_test_record = "SELECT * FROM `web_home_info` WHERE `home_id` = '$PId'";
$test_record= mysql_query($query_test_record, $cp_connection) or die(mysql_error());
$row_test_record = mysql_fetch_assoc($test_record);
$totalRows_test_record = mysql_num_rows($test_record);

echo '<div align="left"><p>Storing Home Page Information. </p></div>';
if($totalRows_test_record == 0) {
	$upd = "INSERT INTO `web_home_info` (`home_id`, `home_header`, `home_header_2`, `home_tag`, `home_tag_2`, `home_text`) VALUES ('$PId','$PH','$PH2','$PT', '$PT2','$PText');";
	$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
}

mysql_free_result($test_record);	

echo '<div align="left"><p>Dumping Navigation. </p></div>';

$truncatetable = "TRUNCATE TABLE `web_navigation`";
$truncatetableinfo = mysql_query($truncatetable, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Retrieving Reviewed Navigation. </p></div>';
$query_get_records = "SELECT * FROM `web_review_navigation`";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

echo '<div align="left"><p>Storing Navigation';
$upd = "INSERT INTO `web_navigation` (`nav_id`, `nav_part_id`, `nav_name`, `nav_order`, `nav_is_nav`, `nav_us`) VALUES ";
$n = 0;
do{	
	echo '. ';
	$PId = clean_variable($row_get_records['nav_id'],'Store');
	$PPId = clean_variable($row_get_records['nav_part_id'],'Store');
	$PName = clean_variable($row_get_records['nav_name'],'Store');
	$POrder = clean_variable($row_get_records['nav_order'],'Store');
	$PNav = clean_variable($row_get_records['nav_is_nav'],'Store');
	$PUse = clean_variable($row_get_records['nav_us'],'Store');
	$n ++;
	if($n == 1){
		$upd .= "('$PId','$PPId','$PName','$POrder', '$PNav','$PUse') ";
	} else {
		$upd .= ",('$PId','$PPId','$PName','$POrder', '$PNav','$PUse') ";
	}
} while ($row_get_records = mysql_fetch_assoc($get_records));
echo '</p></div>';
$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Dumping Web Pages. </p></div>';
$truncatetable = "TRUNCATE TABLE `web_pages`";
$truncatetableinfo = mysql_query($truncatetable, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Retrieving Reviewed Web Pages. </p></div>';
$query_get_records = "SELECT * FROM `web_review_pages`";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

echo '<div align="left"><p>Storing Web Pages';
$upd = "INSERT INTO `web_pages` (`page_id`, `nav_id`, `page_name`, `page_header_1`, `page_header_2`, `page_tag_1`, `page_tag_2`, `page_style`) VALUES ";
$n = 0;
do{	
	echo '. ';
	$PId = clean_variable($row_get_records['page_id'],'Store');
	$PNav = clean_variable($row_get_records['nav_id'],'Store');
	$PName = clean_variable($row_get_records['page_name'],'Store');
	$PH = clean_variable($row_get_records['page_header_1'],'Store');
	$PH2 = clean_variable($row_get_records['page_header_2'],'Store');
	$PT = clean_variable($row_get_records['page_tag_1'],'Store');
	$PT2 = clean_variable($row_get_records['page_tag_2'],'Store');
	$PStyle = clean_variable($row_get_records['page_style'],'Store');
	$n ++;
	if($n == 1){
		$upd .= "('$PId','$PNav','$PName','$PH', '$PH2','$PT','$PT2','$PStyle') ";
	} else {
		$upd .= ",('$PId','$PNav','$PName','$PH', '$PH2','$PT','$PT2','$PStyle') ";
	}
} while ($row_get_records = mysql_fetch_assoc($get_records));
echo '</p></div>';
$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Dumping Web Page Text. </p></div>';
$truncatetable = "TRUNCATE TABLE `web_page_text`";
$truncatetableinfo = mysql_query($truncatetable, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Retrieving Reviewed Web Page Text</p></div>';
$query_get_records = "SELECT * FROM `web_review_page_text`";
$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
$row_get_records = mysql_fetch_assoc($get_records);
$totalRows_get_records = mysql_num_rows($get_records);

echo '<div align="left"><p>Storing Web Page Text';
$upd = "INSERT INTO `web_page_text` (`page_text_id`, `page_id`, `page_text_header`, `page_text_text`, `page_text_order`) VALUES ";
$n = 0;
do{	
	echo '. ';
	$PTId = clean_variable($row_get_records['page_text_id'],'Store');
	$PId = clean_variable($row_get_records['page_id'],'Store');
	$PH = clean_variable($row_get_records['page_text_header'],'Store');
	$PText = clean_variable($row_get_records['page_text_text'],'Store');
	$POrder = clean_variable($row_get_records['page_text_order'],'Store');
	$n ++;
	if($n == 1){
		$upd .= "('$PTId','$PId','$PH','$PText','$POrder') ";
	} else {
		$upd .= ",('$PTId','$PId','$PH','$PText','$POrder') ";
	}
} while ($row_get_records = mysql_fetch_assoc($get_records));
echo '</p></div>';
$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());

echo '<div align="left"><p>Publishing Completed. </p></div>'; 
?>
