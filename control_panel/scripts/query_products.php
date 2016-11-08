<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../";}
include $r_path.'security.php';
include $r_path.'scripts/fnct_record_5_table.php';
if(isset($_POST['Controller']) && $_POST['Controller'] == "Delete")include $r_path.'scripts/del_products.php';
if(isset($_POST['Dropdowns'])){
	$PCats = $_POST['Dropdowns'];
	$Count = count($PCats);
	$PCat = $PCats[$Count-1];
	$PSel_Cal = (isset($_POST['Sel_Dropdowns'])) ? $_POST['Sel_Dropdowns'] : -1;
	$Sorting = explode(",",$sort,3);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
	$sort = implode(",",$PCats);
} else if($sort != ""){
	$Sorting = explode(",",$sort,3);
	$PCats = explode(",",$Sorting[2]);
	$Count = count($PCats);
	$PCat = $PCats[0];
	if($Count > 1){
		foreach($PCats as $k => $v){
			if($v == '-1'){
				$PCat = $PCats[$k-1];
			}
		}
	}
	$PSel_Cal = explode(",",$Sorting[2]);
	$Sort = str_replace("_"," ",$Sorting[0]);
	$Order = str_replace("_"," ",$Sorting[1]);
} else {
	$PCats = array("-1");
	$PCat = -1;
	$PSel_Cal = -1;
	$Sort = "Product Name";
	$Order = "ASC";
}
$sort = $Sort.",".$Order.",".implode(",",$PCats);
$rcrd = explode(",",$rcrd);
$TempSort = array();
$TempSort[0] = $Order;
$TempSort[1] = $Sort;
$n = 0;
while($PCats[$n] != -1 && isset($PCats[$n])){
	array_push($TempSort, $PCats[$n]);
	$n++;
}
if($n == 0){
	$TempSort[2] = -1;
}
$Sorting = implode(",",$TempSort);
$Sorting = str_replace(" ","_",$Sorting);
unset($TempSort);

if($Sort == "Product Name"){
	$Sort_val = " ORDER BY `prod_products`.`prod_name` ".$Order;
} else if ($Sort == "Number"){
	$Sort_val = " ORDER BY `prod_products`.`prod_number` ".$Order;
} else if ($Sort == "Availability"){
	$Sort_val = " ORDER BY `prod_availability`.`avail_name` ".$Order;
} else if ($Sort == "Price"){
	$Sort_val = " ORDER BY `prod_products`.`prod_price` ".$Order;
} else if ($Sort == "Quantity"){
	$Sort_val = " ORDER BY `prod_products`.`prod_qty` ".$Order;
}
?>

<div id="Form_Header">
  <div id="Add"><img src="/<? echo $AevNet_Path; ?>/images/add.jpg" width="72" height="33" alt="add" onclick="javascript:set_form('','Prod,Prod','add','<? echo $sort; ?>','<? echo $rcrd; ?>');" /> </div>
  <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
  <p>Product List </p>
</div>
<div id="Div_Records">
  <? 
	$records = array();
	$headers = array();
	$sortheaders = array();
	$div_data = array();
	$drop_downs = array();
	$headers[0] = "Product Name";
	$headers[1] = "Number";
	$headers[2] = "Avail.";
	$headers[3] = "Price";
	$headers[4] = "Qty.";
	$headers[5] = "Updated";
	$sortheaders[0] = $Sort;
	$sortheaders[1] = $Order;
	if(count($PSel_Cal)>0 && is_array($PSel_Cal)){
		foreach($PSel_Cal as $key => $value){
			if($value != $PCats[$key]){
				if($PCats[$key] == -1){
					if($key == 0){
						$PCat = -1;
					} else {
						$PCat = $PCats[$key-1];
					}
				} else {
					$PCat = $PCats[$key];
				}
				break;
			}
		}
	}
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
	
	$query_get_categories = "SELECT `cat_id`,`cat_name` FROM `prod_categories` WHERE `cat_parent_id` = '$PCat' ORDER BY `cat_name` ASC";
	$get_categories = mysql_query($query_get_categories, $cp_connection) or die(mysql_error());
	$row_get_categories = mysql_fetch_assoc($get_categories);
	$totalRows_get_categories = mysql_num_rows($get_categories);
	
	if($totalRows_get_categories != 0 && $PCat != 0){
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
	
	
	$query_get_products = "SELECT `prod_products`.*, `prod_availability`.`avail_name` FROM `prod_products` LEFT OUTER JOIN `prod_availability` ON `prod_availability`.`availability_id` = `prod_products`.`availability_id` WHERE `prod_service` = 'n' AND `prod_use` = 'y'".$add_where.$Sort_val;
	$maxRows_get_products = 50;
	$pageNum_get_products = 0;
  $pageNum_get_products = $rcrd[0];
	$startRow_get_products = $pageNum_get_products * $maxRows_get_products;
	
	$query_limit_get_products = sprintf("%s LIMIT %d, %d", $query_get_products, $startRow_get_products, $maxRows_get_products);
	$get_products = mysql_query($query_limit_get_products, $cp_connection) or die(mysql_error());
	$row_get_products = mysql_fetch_assoc($get_products);	

	//$query_get_products = "SELECT `prod_products`.*, `prod_availability`.`avail_name` FROM `prod_products` LEFT OUTER JOIN `prod_availability` ON `prod_availability`.`availability_id` = `prod_products`.`availability_id`".$add_where.$Sort_val;
	//$get_products = mysql_query($query_get_products, $cp_connection) or die(mysql_error());
	$get_products = mysql_query($query_limit_get_products, $cp_connection) or die(mysql_error());
	$row_get_products = mysql_fetch_assoc($get_products);
	//$totalRows_get_products = mysql_num_rows($get_products);
	
	//if (isset($_GET['Rows'])) {
	//  $totalRows_get_products = $_GET['Rows'];
	//} else {
	  $all_get_products = mysql_query($query_get_products);
	  $totalRows_get_products = mysql_num_rows($all_get_products);
	//}
	$totalPages_get_products = ceil($totalRows_get_products/$maxRows_get_products)-1;
	$queryString_get_products = "";
	
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&",$querystring);
	  $newParams = array();

	  foreach ($params as $param) {
		if (stristr($param, "pageNum") == false && 
			stristr($param, "Rows") == false) {
		 	array_push($newParams, $param);
		}
	  }
	  if(stristr($querystring, "Sort") == false){
		array_push($newParams, "Sort=".$Sorting);
	  }
	  if (count($newParams) != 0) {
		$queryString_get_products = "&" . htmlentities(implode("&", $newParams));
	  }
	}
	$queryString_get_products = sprintf("&Rows=%d%s", $totalRows_get_products, $queryString_get_products);
	
	
	$n = 0;
	do{
		$a = 1;
		$parents = array();
		$parents[0] = $row_get_products['cat_id'];
			$exp_date = split(' ', $row_get_products['prod_sale_exp']);
			if($exp_date == "0000-00-00 00:00:00"){
				$exp_date[0] = preg_replace('-','',$exp_date[0]);
				$exp_date[1] = preg_replace(':','',$exp_date[1]);
				if($exp_date[1] == "000000" || $exp_date[1] == "0"){
					$date = date("YmdHis", mktime(0,0,0,date("m"),date("d"),date("Y")));
				} else {
					$date = date("YmdHis", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));
				}
				$exp_date = $exp_date[0]+$exp_date[1];
				if($date <= $exp_date){
					$price = "Sale: $".number_format($row_get_products['prod_sale'],2,".",",");
				} else {
					$price = "$".number_format($row_get_products['prod_price'],2,".",",");
				}
			} else {
				$price = "$".number_format($row_get_products['prod_price'],2,".",",");
			}
			$records[$n][0][0] = false;
			$records[$n][0][1] = false;
			$records[$n][0][2] = false;
			$records[$n][1] = $row_get_products['prod_id'];
			$records[$n][2] = $row_get_products['prod_name'];
			$records[$n][3] = $row_get_products['prod_number'];
			$records[$n][4] = $row_get_products['avail_name'];
			$records[$n][5] = $price;
			$records[$n][6] = $row_get_products['prod_qty'];
			$records[$n][7] = format_date($row_get_products['prod_updated'],"Dash",'',true,false);
			$div_data[$n] = $row_get_products['prod_desc'];
			
			$n++;
	} while ($row_get_products = mysql_fetch_assoc($get_products)); 
	
	mysql_free_result($get_products);?>
  <div align="right">
    <?
if ($pageNum_get_products > 0) { // Show if not first page ?>
    <a href="#" onclick="javascript:set_form('form_','Prod,Prod','query','<? echo $sort; ?>','0,<? echo $totalPages_get_products; ?>');" title="First Page" onmouseover="window.status='First Page';return true"  onmouseout="window.status='Done';return true">&laquo;</a> <a href="#" onclick="javascript:set_form('form_','Prod,Prod','query','<? echo $sort; ?>','<? echo max(0, $pageNum_get_products - 1).','.$totalPages_get_products; ?>');" title="Previous Page" onmouseover="window.status='Previous Page';return true"  onmouseout="window.status='Done';return true">&lt;</a>
    <? } else if($totalRows_get_products>$maxRows_get_products) {// Show if not first page
		 } if($totalRows_get_products>$maxRows_get_products){
			if(($totalPages_get_products+1)<10){$addpages = $totalPages_get_products+1;
			} else {$addpages = 10;}
			if(($pageNum_get_products+1)<5){$start = 1;
			} else if(abs(($pageNum_get_products+1)-($totalPages_get_products+1))<=4) {
				$start = ($totalPages_get_products+1)-$addpages+1;
			} else {$start = ($pageNum_get_products+1)-4;	}
			for($n=$start;$n<($start+$addpages);$n++){
		print ('<a href="#"  onclick="javascript:set_form(\'form_\',\'Prod,Prod\',\'query\',\''.$sort.'\',\''.($n-1).','.$totalPages_get_products.'\');" onmouseover="window.status=\'Page '.$n.'\'; return true;"  onmouseout="window.status=\'Done\'; return true;" title="Page '.$n.'" ');
		if($n!=($pageNum_get_products+1)){ echo "";
		} else { echo ""; 	}
		echo ">".$n."</a>";
		echo ($n<($start+$addpages-1)) ? " . " : "";
	} } ?>
    <? if ($pageNum_get_products < $totalPages_get_products) { // Show if not last page ?>
    <a href="#" onclick="javascript:set_form('form_','Prod,Prod','query','<? echo $sort; ?>','<? echo min($totalPages_get_products, $pageNum_get_products + 1).','.$totalPages_get_products; ?>');" title="Next Page" onmouseover="window.status='Next Page';return true"  onmouseout="window.status='Done';return true">&gt;</a> <a href="#" onclick="javascript:set_form('form_','Prod,Prod','query','<? echo $sort; ?>','<? echo $totalPages_get_products.','.$totalPages_get_products; ?>');"  title="Last Page" onmouseover="window.status='Last Product';return true"  onmouseout="window.status='Done';return true">&raquo;</a>
    <? } else if($totalRows_get_products>$maxRows_get_products){// Show if not last page ?>
    <? } ?>
  </div>
  <? build_record_5_table('Products','Products',$headers,$sortheaders,$records,$div_data,$drop_downs,array(array('Delete Product(s)','Delete','Delete',false)),false,'100%','0','0','0',false,false,$Rec_Style_1,$Rec_Style_2,$Rec_Style_3,$Rec_Style_4,$Rec_Style_5);
$rcrd = implode(",",$rcrd);
?>
</div>
