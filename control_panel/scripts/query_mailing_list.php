<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_mailing_list.php';?>

<div id="Form_Header">
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Mailing List </p>
</div>
<div style="clear:both">
  <p><a href="/<? echo $AevNet_Path; ?>/downloader.php?type=csv&list=mailing" target="_blank">Download CSV File</a>&nbsp;|&nbsp;<a href="/<? echo $AevNet_Path; ?>/downloader.php?type=exl&list=mailing" target="_blank">Download Excel File</a></p>
</div>
<div id="Div_Records">
  <?	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "E-mail";
	$headers[1] = "Name";
	$headers[2] = "Date";
	
	$query_get_records = "SELECT * FROM `mail_mailing_list` ORDER BY `email` ASC";
	$get_records = mysql_query($query_get_records, $cp_connection) or die(mysql_error());
	
	while ($row_get_records = mysql_fetch_assoc($get_records)){
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_records['mail_id'];
		$records[$count][2] = $row_get_records['email'];
		$records[$count][3] = $row_get_records['mail_fname']." ".$row_get_records['mail_lname'];
		$records[$count][4] = format_date($row_get_records['date'],"Short",false,true,false);
	}
	
	mysql_free_result($get_records);
	build_record_5_table('Mailing_List','Mailing List',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete E-mail(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
