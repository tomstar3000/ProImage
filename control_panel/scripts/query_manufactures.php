<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)	$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_manufactures.php';?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Prod,Man','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /> </div>
  <img src="/<?php echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Manufacture List </p>
</div>
<div id="Div_Records">
  <?php	
	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = array();
	$drop_downs = false;
	$headers[0] = "Manufacture Name";
	
	$query_get_manufactures = "SELECT * FROM `sellers` ORDER BY `sell_cname` ASC";
	$get_manufactures = mysql_query($query_get_manufactures, $cp_connection) or die(mysql_error());
	$row_get_manufactures = mysql_fetch_assoc($get_manufactures);
	$totalRows_get_manufactures = mysql_num_rows($get_manufactures);
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_manufactures['sell_id'];
		$records[$count][2] = $row_get_manufactures['sell_cname'];
		$temp_div = "";
		if($row_get_manufactures['sell_add'] != ""){
			$temp_div .= $row_get_manufactures['sell_add'];
				if($row_get_manufactures['sell_suite_apt'] != ""){
					$temp_div .= " Suite/Apt. ".$row_get_manufactures['sell_suite_apt'];
				}
				$temp_div .= "<br />";
				if($row_get_manufactures['sell_add_2'] != ""){
					$temp_div .= $row_get_manufactures['sell_add_2']."<br />";
				}
				$temp_div .= $row_get_manufactures['sell_city'].", ".$row_get_manufactures['sell_state'].". ".$row_get_manufactures['sell_zip']."<br />";
				if($row_get_manufactures['sell_country'] != ""){
					$temp_div .= $row_get_manufactures['sell_country']."<br />";
				}
		}
		if($row_get_manufactures['sell_phone'] != "" && $row_get_manufactures['sell_phone'] != 0){
			$temp_div .= "Phone: ".phone_number($row_get_manufactures['sell_phone'])."<br />";
		}
		if($row_get_manufactures['sell_cell'] != "" && $row_get_manufactures['sell_cell'] != 0){
			$temp_div .= "Mobile: ".phone_number($row_get_manufactures['sell_cell'])."<br />";
		}
		if($row_get_manufactures['sell_fax'] != "" && $row_get_manufactures['sell_fax'] != 0){
			$temp_div .= "Fax: ".phone_number($row_get_manufactures['sell_fax'])."<br />";
		}
		if($row_get_manufactures['sell_website'] != ""){
			$temp_div .= "Website: <a href=\"".$row_get_manufactures['sell_website']."\" target=\"_blank\">".$row_get_manufactures['sell_website']."</a>";
		}
		$div_data[$count] = $temp_div;
	} while ($row_get_manufactures = mysql_fetch_assoc($get_manufactures)); 
	
	mysql_free_result($get_manufactures);
	build_record_5_table('Manufactures','Manufactures',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Manufacture(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
