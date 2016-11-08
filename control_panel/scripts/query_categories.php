<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_record_6_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')) include $r_path.'scripts/del_categories.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "QuerySave") include $r_path.'scripts/query_save_categories.php';
if(count($path)>2){
	$cat_pid = $path[count($path)-1];
	$is_back = "view";
	$is_path = array_slice($path,0,(count($path)-1));
} else {
	$cat_pid = 0;
	$is_back = "query";
	$is_path = "Prod,Cat";
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('form_','<? echo implode(',',$path); ?>','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Product Category List </p>
</div>
<div id="Div_Records">
  <?
$query_get_categories = "SELECT * FROM `prod_categories` WHERE `cat_parent_id` = '$cat_pid' ORDER BY `cat_name` ASC";
$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
$row_get_categories = mysql_fetch_assoc($get_categories);
$totalRows_get_categories = mysql_num_rows($get_categories);

	$records = array();
	$headers = array();
	$sortheaders = false;
	$div_data = false;
	$drop_downs = false;
	$headers[0] = "Category Name";
	$headers[1] = "Sub-Categories";
	$headers[2] = "Order";
	$n = 0;
	do{
		$cid = $row_get_categories['cat_id'];
		
		$query_get_record_count = "SELECT `cat_id` FROM `prod_categories` WHERE `cat_parent_id` = '$cid'";
		$get_record_count = mysql_query($query_get_record_count, $cp_connection) or die(mysql_error());
		$row_get_record_count = mysql_fetch_assoc($get_record_count);
		$totalRows_get_record_count = mysql_num_rows($get_record_count);
		
		$records_count = $totalRows_get_record_count;
		
		mysql_free_result($get_record_count);
		
		$records[$n][0][0] = false;
		$records[$n][0][1] = false;
		$records[$n][0][2] = false;
		$records[$n][1] = $row_get_categories['cat_id'];
		$records[$n][2][0] = $row_get_categories['cat_name'];
		$records[$n][2][1] = false;
		$records[$n][3][0] = $records_count;
		$records[$n][3][1] = false;
		$records[$n][4][0] = $row_get_categories['cat_order'];
		$records[$n][4][1] = "Order";
		
		$n++;
	} while ($row_get_categories = mysql_fetch_assoc($get_categories)); 
	
	mysql_free_result($get_categories);
	$Btns = array();
	$Count = count($Btns);
	$Btns[$Count] = array();
	$Btns[$Count][0] = 'Delete Categorie(s)';
	$Btns[$Count][1] = 'Delete';
	$Btns[$Count][2] = 'Delete';
	$Btns[$Count][3] = false;
	$Count = count($Btns);
	$Btns[$Count] = array();
	$Btns[$Count][0] = 'Save Changes';
	$Btns[$Count][1] = 'Save';
	$Btns[$Count][2] = 'QuerySave';
	$Btns[$Count][3] = false;
	
	build_record_6_table('Categories','Categories',$headers,$sortheaders,$records,$div_data,$drop_downs,$Btns,false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd)) $rcrd = implode(",",$rcrd);
?>
</div>
