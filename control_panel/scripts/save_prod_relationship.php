<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once($r_path.'scripts/crumbs_pathing.php');
$ProdId = $path[2];
$SGroup = (isset($_POST['Selection_Group'])) ? clean_variable($_POST['Selection_Group'],true) : '';
$SPage = (isset($_POST['Page'])) ? clean_variable($_POST['Page'], true) : 1;
$prod_ids = isset($_POST['Product_Id']) ? $_POST['Product_Id'] : -1;
$temp_remove_prod_ids = isset($_POST['Selected_Product_Id']) ? $_POST['Selected_Product_Id'] : array();
$temp_sel_prod_ids = isset($_POST['Sel_Product_Id']) ? $_POST['Sel_Product_Id'] : array();
$temp_sel_prod_name = isset($_POST['Sel_Product_Name']) ? $_POST['Sel_Product_Name'] : array();
$temp_sel_prod_num = isset($_POST['Sel_Product_Num']) ? $_POST['Sel_Product_Num'] : array();
$sel_prod_ids = array();
foreach($temp_sel_prod_ids as $key => $value){
	if(!in_array($value,$temp_remove_prod_ids)){
		if($default == -1 && $key == 0){
			$sel_prod_ids[$value][0] = true;
		} else if($default == $value) {		
			$sel_prod_ids[$value][0] = true;
		} else {	
			$sel_prod_ids[$value][0] = false;
		}
		$sel_prod_ids[$value][1] = $value;
		$sel_prod_ids[$value][2] = clean_variable($temp_sel_prod_name[$key],true);
		$sel_prod_ids[$value][3] = clean_variable($temp_sel_prod_num[$key],true);
	}
}
if(((isset($_POST['Controller']) && $_POST['Controller'] == "false") || (!isset($_POST['Controller'])))  && $cont == 'view'){
	$query_getInfo = "SELECT * FROM `prod_relationships` WHERE `prod_id` = '$ProdId'";
	$getInfo = mysql_query($query_getInfo, $cp_connection) or die(mysql_error());
	$row_getInfo = mysql_fetch_assoc($getInfo);
	$totalRows_getInfo = mysql_num_rows($getInfo);
	
	$rel_ids = $row_getInfo['rel_ids'];
	$rel_ids = explode("[+]",$rel_ids);
	$prod_ids = array();
	foreach($rel_ids as $v){		
		if(in_array($v,$prod_ids)) $key = array_search($v, $prod_ids); else array_push($prod_ids,$v);
	}	
	mysql_free_result($getInfo);
}
if(isset($_POST['Prod_Dropdowns'])){
	$PCats = $_POST['Prod_Dropdowns'];
	$Count = count($PCats);
	$PCat = $PCats[$Count-1];
	$PSel_Cal = (isset($_POST['Prod_Sel_Dropdowns'])) ? $_POST['Prod_Sel_Dropdowns'] : -1;
} else {
	$PCats = array("-1");
	$PCat = -1;
	$PSel_Cal = -1;
}
if(count($PSel_Cal)>0 && is_array($PSel_Cal)){
	foreach($PSel_Cal as $key => $value){
		if($value != $PCats[$key]){
			if($PCats[$key] == -1){
				if($key == 0)	$PCat = -1; else 	$PCat = $PCats[$key-1];
			} else {
				$PCat = $PCats[$key];
			}
			break;
}	}	}
$n = 0;
$parents = array();
$children = array();
if($PCat == -1){
	$children[0] = 0;
} else {
	$children[0] = $PCat;
}
function find_children_2($value, $n, $table, $parent, $feild_id){
	global $children;
	global $cp_connection;
	
	$query_get_children = "SELECT `".$feild_id."` FROM `".$table."` WHERE `".$parent."` = '$value'";
	$get_children = mysql_query($query_get_children, $cp_connection) or die(mysql_error());
	$row_get_children = mysql_fetch_assoc($get_children);
	$totalRows_get_children = mysql_num_rows($get_children);
	
	
	if($totalRows_get_children != 0){
		do{
			$test_id = $row_get_children[$feild_id];
			$children[count($children)] = $row_get_children[$feild_id];
			
			$query_test_children = "SELECT COUNT(`".$feild_id."`) FROM `".$table."` WHERE `".$parent."` = '$test_id'";
			$test_children = mysql_query($query_test_children, $cp_connection) or die(mysql_error());
			$row_test_children = mysql_fetch_assoc($test_children);
			$totalRows_test_children = mysql_num_rows($test_children);
			
			if($row_test_children['COUNT(`'.$feild_id.'`)'] != 0){
				find_children_2($test_id,$n,$table, $parent, $feild_id);
			}
			
			mysql_free_result($test_children);
			
		} while ($row_get_children = mysql_fetch_assoc($get_children));
	}	
	mysql_free_result($get_children);
}
$add_where = "";
if($PCat != -1 && $PCat != 0){
	find_children_2($PCat,$n,'prod_categories','cat_parent_id','cat_id');
	$children = array_unique($children);
	foreach($children as $key => $value){
		$add_where .= (!$add_where) ? "`cat_id` = '$value'" : " OR `cat_id` = '$value'";
	}
	$add_where = " AND (".$add_where.")";
} else if ($PCat == 0) {
	$add_where = " AND `cat_id` = '0'";
}
find_parents($PCat,$n,'prod_categories','cat_parent_id','cat_id');
$drop_downs = array();
$y = 0;
for($n=count($parents)-1;$n>=0;$n--){
	$Temp_id = $parents[$n][0];			
	$Cur_id = $parents[$n][1];
	
	$query_get_categories = "SELECT * FROM `prod_categories`  WHERE `cat_parent_id` = '$Temp_id' ORDER BY `cat_name` ASC";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	$z = 3;
	$drop_downs[$y][0][0] = $Cur_id;
	$drop_downs[$y][1][0] = -1;
	$drop_downs[$y][1][1] = "-- Select All --";
	$drop_downs[$y][2][0] = 0;
	$drop_downs[$y][2][1] = "-- No Category --";
	do{
		$drop_downs[$y][$z][0] = $row_get_categories['cat_id'];
		$drop_downs[$y][$z][1] = $row_get_categories['cat_name'];
		$z++;
	} while ($row_get_categories = mysql_fetch_assoc($get_categories));
	$y++;
}
mysql_free_result($get_categories);

$query_getCat = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat' ORDER BY `cat_name` ASC";
$getCat = mysql_query($query_getCat, $cp_connection) or die(mysql_error());
$row_getCat = mysql_fetch_assoc($getCat);
$totalRows_getCat = mysql_num_rows($getCat);

if($totalRows_getCat != 0 && $PCat != 0){
	$drop_downs[$y][0][0] = -2;
	$drop_downs[$y][1][0] = -1;
	$drop_downs[$y][1][1] = "-- Select All --";
	$drop_downs[$y][2][0] = 0;
	$drop_downs[$y][2][1] = "-- No Category --";
	$z = 2;
	do{
		$drop_downs[$y][$z][0] = $row_getCat['cat_id'];
		$drop_downs[$y][$z][1] = $row_getCat['cat_name'];
		$z++;
	} while ($row_getCat = mysql_fetch_assoc($getCat));
}
mysql_free_result($getCat);	

$query_getProd = "SELECT `prod_products`.* FROM `prod_products` WHERE `prod_service` = 'n' AND `prod_use` = 'y'".$add_where;
$getProd = mysql_query($query_getProd, $cp_connection) or die(mysql_error());
$row_getProd = mysql_fetch_assoc($getProd);
$totalRows_getProd = mysql_num_rows($getProd);
$n = 0;
$m = 0;
$records = array();
do{
	if(is_array($prod_ids) && in_array($row_getProd['prod_id'],$prod_ids)){
		if(($default == -1 && $n == 0) || $default == $row_getProd['prod_id']){
			$sel_prod_ids[$row_getProd['prod_id']][0] = true;
		} else {
			$sel_prod_ids[$row_getProd['prod_id']][0] = false;
		}
		$sel_prod_ids[$row_getProd['prod_id']][1] = $row_getProd['prod_id'];
		$sel_prod_ids[$row_getProd['prod_id']][2] = $row_getProd['prod_name'];
		$sel_prod_ids[$row_getProd['prod_id']][3] = $row_getProd['prod_number'];
		$m++;
	} else if (!array_key_exists($row_getProd['prod_id'],$sel_prod_ids)){
		$records[$n][0] = false;
		$records[$n][1] = $row_getProd['prod_id'];
		$records[$n][2] = $row_getProd['prod_name'];
		$records[$n][3] = $row_getProd['prod_number'];
		$n++;
		if(isset($_POST['Sel_Product_Name']))$m++;
	}
} while ($row_getProd = mysql_fetch_assoc($getProd)); 

mysql_free_result($getProd);

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit' || $cont == 'view')){
	
	$query_getInfo = "SELECT * FROM `prod_relationships` WHERE `prod_id` = '$ProdId'";
	$getInfo = mysql_query($query_getInfo, $cp_connection) or die(mysql_error());
	$row_getInfo = mysql_fetch_assoc($getInfo);
	$totalRows_getInfo = mysql_num_rows($getInfo);
	
	$RId = $row_getInfo['rel_id'];
	$prod_ids = array();
	foreach($sel_prod_ids as $v) array_push($prod_ids,$v[1]);
	if($totalRows_getInfo == 0){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			
			$add = "INSERT INTO `prod_relationships` (`prod_id`,`rel_ids`) VALUES ('$ProdId','".implode("[+]",$prod_ids)."');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		}
	} else {
		$upd = "UPDATE `prod_relationships` SET `rel_ids` = '".implode("[+]",$prod_ids)."' WHERE `rel_id` = '$RId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	}
}
?>