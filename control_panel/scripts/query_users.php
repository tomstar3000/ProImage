<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_users.php'; ?>

<div id="Form_Header">
<div id="Add">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Admin,User','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>User List</p>
</div>
<div id="Div_Records">
  <?
$query_get_users = "SELECT `user_id`, `user_username` FROM `admin_users` WHERE `user_level` > '0' ORDER BY `user_username` ASC";
$get_users = mysql_query($query_get_users, $cp_connection) or die(mysql_error());
$row_get_users = mysql_fetch_assoc($get_users);
$totalRows_get_users = mysql_num_rows($get_users);

$records = array();
$headers = array();
$sortheaders = false;
$div_data = false;
$drop_downs = false;
$headers[0] = "User Name";
do{
	$count = count($records);
	$records[$count][0][0] = false;
	$records[$count][0][1] = false;
	$records[$count][0][2] = false;
	$records[$count][1] = $row_get_users['user_id'];
	$records[$count][2] = $row_get_users['user_username'];
} while ($row_get_users = mysql_fetch_assoc($get_users)); 

mysql_free_result($get_users);
	build_record_5_table('Users','Users',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete User(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
if(is_array($rcrd))$rcrd = implode(",",$rcrd);
?>
</div>
