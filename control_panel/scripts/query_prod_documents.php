<?php if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete"){
	include $r_path.'scripts/del_prod_documents.php';
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<?php echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<?php echo implode(",",$path); ?>','add','<?php echo $sort; ?>','<?php echo $rcrd; ?>');" /></div>
</div>
<div>
  <?php
	$query_get_documents = "SELECT * FROM `prod_documentation` WHERE `prod_id` = '$PId'";
	$get_documents = mysql_query($query_get_documents, $cp_connection) or die(mysql_error());
	$row_get_documents = mysql_fetch_assoc($get_documents);
	$totalRows_get_documents = mysql_num_rows($get_documents);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "File Name";
	$headers[1] = "View File";
	do{
		$count = count($records);
		$records[$count][0][0] = false;
		$records[$count][0][1] = false;
		$records[$count][0][2] = false;
		$records[$count][1] = $row_get_documents['prod_doc_id'];
		$records[$count][2] = $row_get_documents['prod_doc_name'];
		$records[$count][3] = "<a href=\"/".$Prod_White_Folder."/".$row_get_documents['prod_doc_file']."\" target=\"_blank\">View</a>";
	} while ($row_get_documents = mysql_fetch_assoc($get_documents)); 
	mysql_free_result($get_documents);
	
	build_record_5_table('Documents','Documents',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Document(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
?>
</div>
