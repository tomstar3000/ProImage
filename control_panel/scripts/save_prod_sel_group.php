<?php if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++){
		$r_path .= "../";
	}
}
include $r_path.'security.php';
require_once($r_path.'scripts/crumbs_pathing.php');
$SId = $path[4];
$ProdId = $path[2];
$SGroup = (isset($_POST['Selection_Group'])) ? clean_variable($_POST['Selection_Group'],true) : '';
$SMSelect = (isset($_POST['Multiple_Selections'])) ? clean_variable($_POST['Multiple_Selections'], true) : 'n';
$SNone = (isset($_POST['Select_None'])) ? clean_variable($_POST['Select_None'], true) : 'n';
$SPage = (isset($_POST['Page'])) ? clean_variable($_POST['Page'], true) : 1;
$prod_ids = isset($_POST['Product_Id']) ? $_POST['Product_Id'] : -1;
$temp_remove_prod_ids = isset($_POST['Selected_Product_Id']) ? $_POST['Selected_Product_Id'] : array();
$temp_sel_prod_ids = isset($_POST['Sel_Product_Id']) ? $_POST['Sel_Product_Id'] : array();
$temp_sel_prod_name = isset($_POST['Sel_Product_Name']) ? $_POST['Sel_Product_Name'] : array();
$temp_sel_prod_num = isset($_POST['Sel_Product_Num']) ? $_POST['Sel_Product_Num'] : array();
$temp_sel_prod_o_price = isset($_POST['Sel_Product_O_Price']) ? $_POST['Sel_Product_O_Price'] : array();
$temp_sel_prod_price = isset($_POST['Selected_Product_Price']) ? $_POST['Selected_Product_Price'] : array();
$temp_sel_prod_qty = isset($_POST['Selected_Product_Quantity']) ? $_POST['Selected_Product_Quantity'] : array();
$default = isset($_POST['Selected_Default']) ? clean_variable($_POST['Selected_Default'], true) : -1;
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
		$sel_prod_ids[$value][4] = clean_variable($temp_sel_prod_o_price[$key],true);
		$sel_prod_ids[$value][5] = clean_variable($temp_sel_prod_price[$key],true);
		$sel_prod_ids[$value][6] = clean_variable($temp_sel_prod_qty[$key],true);
	}
}
$prod_price = isset($_POST['Product_Price']) ? $_POST['Product_Price'] : array();
$prod_qty = isset($_POST['Product_Quantity']) ? $_POST['Product_Quantity'] : array();

if(isset($_POST['Prod_Dropdowns'])){
	$ProdCats = $_POST['Prod_Dropdowns'];
	if(is_array($ProdCats)){
		$ProdCount = count($ProdCats);
		$ProdCat = $ProdCats[$ProdCount-1];
	} else {
		$ProdCat = $ProdCats;
	}
	$ProdSel_Cal = (isset($_POST['Prod_Sel_Dropdowns'])) ? $_POST['Prod_Sel_Dropdowns'] : -1;
} else {
	$ProdCat = -1;
	$ProdSel_Cal = -1;
}
if(count($ProdSel_Cal)>0 && is_array($ProdSel_Cal)){
	foreach($ProdSel_Cal as $key => $value){
		if($value != $ProdCats[$key]){
			if($ProdCats[$key] == -1){
				if($key == 0){
					$ProdCat = -1;
				} else {
					$ProdCat = $ProdCats[$key-1];
				}
			} else {
				$ProdCat = $ProdCats[$key];
			}
			break;
		}
	}
}
$parents = array();
$children = array();
if($ProdCat == -1){
	$children[0] = 0;
} else {
	$children[0] = $ProdCat;
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
				find_children($test_id,$n,$table, $parent, $feild_id);
			}
			
			mysql_free_result($test_children);
			
		} while ($row_get_children = mysql_fetch_assoc($get_children));
	}	
	mysql_free_result($get_children);
}
$add_where = "";
if($ProdCat != -1 && $ProdCat != 0){
	find_children_2($ProdCat,$n,'prod_categories','cat_parent_id','cat_id');
	$children = array_unique($children);
	foreach($children as $key => $value){
		$add_where .= (!$add_where) ? " WHERE `cat_id` = '$value'" : " OR `cat_id` = '$value'";
	}
} else if ($ProdCat == 0) {
	$add_where = " WHERE `cat_id` = '0'";
}
find_parents($ProdCat,$n,'prod_categories','cat_parent_id','cat_id');
if(((isset($_POST['Controller']) && $_POST['Controller'] == "false") || (!isset($_POST['Controller']))) && $cont == 'edit'){
	$query_get_information = "SELECT `prod_link_prod_sel`.*, `prod_link_prod_sel_group`.`sel_id` AS `selection_id`, `prod_link_prod_sel_group`.`sel_mult`, `prod_link_prod_sel_group`.`sel_none`, `prod_link_prod_sel_group`.`sel_page` FROM `prod_link_prod_sel_group` INNER JOIN `prod_link_prod_sel` ON `prod_link_prod_sel`.`sel_id` = `prod_link_prod_sel_group`.`link_prod_sel_group_id` WHERE `prod_link_prod_sel_group`.`link_prod_sel_group_id` = '$SId'";
	$get_information = mysql_query($query_get_information, $cp_connection) or die(mysql_error());
	$row_get_information = mysql_fetch_assoc($get_information);
	$totalRows_get_information = mysql_num_rows($get_information);
	
	$prod_ids = array();
	$SGroup = $row_get_information['selection_id'];
	$SMSelect = $row_get_information['sel_mult'];
	$SNone = $row_get_information['sel_none'];
	$SPage = $row_get_information['sel_page'];
	do{		
		if(in_array($row_get_information['prod_id'],$prod_ids)){
			$key = array_search($row_get_information['prod_id'], $prod_ids); 
			$prod_qty[$key] .= ",".$row_get_information['link_prod_sel_qty'];
		} else {
			array_push($prod_ids,$row_get_information['prod_id']); 
			array_push($prod_price,($row_get_information['link_prod_sel_price'] == "0") ? "" : $row_get_information['link_prod_sel_price']);
			array_push($prod_qty,$row_get_information['link_prod_sel_qty']);
		}
		
		if($row_get_information['link_prod_sel_default'] == 'y'){
			$default = $row_get_information['prod_id'];
			$sel_prod_ids[$row_get_information['prod_id']][0] = true;
		} else {
			$sel_prod_ids[$row_get_information['prod_id']][0] = false;
		}
		
	} while($row_get_information = mysql_fetch_assoc($get_information));
	
	mysql_free_result($get_information);
}
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

$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$ProdCat' ORDER BY `cat_name` ASC";
$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
$row_get_categories = mysql_fetch_assoc($get_categories);
$totalRows_get_categories = mysql_num_rows($get_categories);

if($totalRows_get_categories != 0 && $ProdCat != 0){
	$drop_downs[$y][0][0] = -2;
	$drop_downs[$y][1][0] = -1;
	$drop_downs[$y][1][1] = "-- Select All --";
	$drop_downs[$y][2][0] = 0;
	$drop_downs[$y][2][1] = "-- No Category --";
	$z = 2;
	do{
		$drop_downs[$y][$z][0] = $row_get_categories['cat_id'];
		$drop_downs[$y][$z][1] = $row_get_categories['cat_name'];
		$z++;
	} while ($row_get_categories = mysql_fetch_assoc($get_categories));
}
mysql_free_result($get_categories);	

$query_get_products = "SELECT `prod_products`.* FROM `prod_products`".$add_where;
$get_products = mysql_query($query_get_products, $cp_connection) or die(mysql_error());
$row_get_products = mysql_fetch_assoc($get_products);
$totalRows_get_products = mysql_num_rows($get_products);
$n = 0;
$m = 0;
$records = array();

do{
	$price = "W: $".number_format($row_get_products['prod_whole'],2,".",",")."<br />P: $".number_format($row_get_products['prod_price'],2,".",",");
	if(is_array($prod_ids) && in_array($row_get_products['prod_id'],$prod_ids)){
		if(($default == -1 && $n == 0) || $default == $row_get_products['prod_id']){
			$sel_prod_ids[$row_get_products['prod_id']][0] = true;
		} else {
			$sel_prod_ids[$row_get_products['prod_id']][0] = false;
		}
		$sel_prod_ids[$row_get_products['prod_id']][1] = $row_get_products['prod_id'];
		$sel_prod_ids[$row_get_products['prod_id']][2] = $row_get_products['prod_name'];
		$sel_prod_ids[$row_get_products['prod_id']][3] = $row_get_products['prod_number'];
		$sel_prod_ids[$row_get_products['prod_id']][4] = clean_variable($price,true);
		$sel_prod_ids[$row_get_products['prod_id']][5] = clean_variable($prod_price[$m],true);
		$sel_prod_ids[$row_get_products['prod_id']][6] = ($prod_qty[$m] == "") ? "1" : clean_variable($prod_qty[$m],true);
		$m++;
	} else if (!array_key_exists($row_get_products['prod_id'],$sel_prod_ids)){
		$records[$n][0] = false;
		$records[$n][1] = $row_get_products['prod_id'];
		$records[$n][2] = $row_get_products['prod_name'];
		$records[$n][3] = $row_get_products['prod_number'];	
		$records[$n][4] = $price;	
		$n++;
		if(isset($_POST['Sel_Product_Name'])){
			$m++;
		}
	}
} while ($row_get_products = mysql_fetch_assoc($get_products)); 

mysql_free_result($get_products);

if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($cont == "add"){
		$add = "INSERT INTO `prod_link_prod_sel_group` (`prod_id`,`sel_id`,`sel_page`,`sel_mult`,`sel_none`) VALUES ('$ProdId','$SGroup','$SPage','$SMSelect','$SNone');";
		$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
		
		$query_get_last = "SELECT `link_prod_sel_group_id` FROM `prod_link_prod_sel_group` WHERE `prod_id` = '$ProdId' ORDER BY `link_prod_sel_group_id` DESC LIMIT 0,1";
		$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
		$row_get_last = mysql_fetch_assoc($get_last);
		
		$last_id = $row_get_last['link_prod_sel_group_id'];
		
		mysql_free_result($get_last);
		
		foreach($sel_prod_ids as $key => $value){
		
			$qty = explode(",",$value[6]);
			$price = $value[5];
			$default = ($value[0] === true) ? 'y' : 'n';
			foreach($qty as $k => $v){
				$add = "INSERT INTO `prod_link_prod_sel` (`sel_id`,`prod_id`,`link_prod_sel_qty`,`link_prod_sel_price`,`link_prod_sel_default`) VALUES ('$last_id','$key','$v','$price','$default');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			}
		}
	} else {
		$upd = "UPDATE `prod_link_prod_sel_group` SET `sel_id` = '$SGroup',`sel_page` = '$SPage',`sel_mult` = '$SMSelect',`sel_none` = '$SNone' WHERE `link_prod_sel_group_id` = '$SId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
	
		$del= "DELETE FROM `prod_link_prod_sel` WHERE `sel_id` = '$SId'";
		$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
		
		$optimize = "OPTIMIZE TABLE `prod_link_prod_sel`";
		$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
	
		foreach($sel_prod_ids as $key => $value){
		
			$qty = explode(",",$value[6]);
			$price = $value[5];
			$default = ($value[0] === true) ? 'y' : 'n';
			foreach($qty as $k => $v){
				$add = "INSERT INTO `prod_link_prod_sel` (`sel_id`,`prod_id`,`link_prod_sel_qty`,`link_prod_sel_price`,`link_prod_sel_default`) VALUES ('$SId','$key','$v','$price','$default');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			}
		}
		$path = array_slice($path,0,4);
	}
	$cont = "query";
}
?>