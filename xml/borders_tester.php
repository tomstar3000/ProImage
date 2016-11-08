<?
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1;
$r_path = "";
for($n=0;$n<$count;$n++)$r_path .= "../";
define ("Allow Scripts", true);

$data = file_get_contents("php://input");

include $r_path.'scripts/security.php';
require_once($r_path.'Connections/cp_connection.php');
require_once($r_path.'scripts/fnct_clean_entry.php');
require_once($r_path.'scripts/fnct_xml_parser.php');

$data = '<?xml version="1.0" encoding="iso-8859-1"?>'.$data;
$parser = new XMLParser($data, 'raw', 1);
$tree = $parser->getTree();
$ImageId = $tree['IMAGE']['ATTRIBUTES']['ID'];
$ImageId = 510720;
$PriceId = $tree['IMAGE']['ATTRIBUTES']['PRICE'];
$PriceId = 93;
$query_get_info = "SELECT `photo_price` 
FROM `photo_event_price` 
WHERE `photo_event_price_id` = '$PriceId'";
$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
$row_get_info = mysql_fetch_assoc($get_info);
$prices = explode(",",$row_get_info['photo_price']);
		
mysql_select_db($database_cp_connection, $cp_connection);
$query_getImage = "SELECT * FROM `photo_event_images` WHERE `image_id` = '$ImageId'";
$getImage = mysql_query($query_getImage, $cp_connection) or die(mysql_error());
$row_getImage = mysql_fetch_assoc($getImage);
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
$folder = substr($row_getImage['image_folder'],0,-11);
echo '		<image id="'.$row_getImage['image_id'].'" tiny="'.$row_getImage['image_tiny'].'" small="'.$row_getImage['image_small'].'" zoom="'.$row_getImage['image_small'].'" folder="'.$folder.'">'.$name.'</image>';

mysql_free_result($getImage);

$query_getImage = "SELECT `prod_categories`.*
	FROM `prod_categories`
	INNER JOIN `prod_products`
		ON `prod_products`.`cat_id` = `prod_categories`.`cat_id`
	WHERE `cat_parent_id` = '16'
		AND `prod_service` = 'b'
	GROUP BY `prod_categories`.`cat_id`";
$getImage = mysql_query($query_getImage, $cp_connection) or die(mysql_error());
echo '<categories>';
while($row_getImage = mysql_fetch_assoc($getImage)){
	echo '<category id="'.$row_getImage['cat_id'].'" name="'.$row_getImage['cat_name'].'"></category>';
}
echo '</categories>';

mysql_free_result($getImage);

$query_getImage = "SELECT *
	FROM `prod_products`
	WHERE `prod_service` = 'b'";
$getImage = mysql_query($query_getImage, $cp_connection) or die(mysql_error());

echo '<borders>';
while($row_getImage = mysql_fetch_assoc($getImage)){
	echo '<border id="'.$row_getImage['prod_id'].'" name="'.$row_getImage['prod_name'].'" tiny="'.$row_getImage['prod_tiny'].'" folder="products/" category="'.$row_getImage['cat_id'].'">';
	if($row_getImage['prod_image'] == ""){
		echo '<horizontal>None</horizontal>';
	} else {
		echo '<horizontal small="'.$row_getImage['prod_thumb'].'" zoom="'.$row_getImage['prod_image'].'"></horizontal>';
	}
	if($row_getImage['prod_image2'] == ""){
		echo '<vertical>None</vertical>';
	} else {
		echo '<vertical small="'.$row_getImage['prod_thumb2'].'" zoom="'.$row_getImage['prod_image2'].'"></vertical>';
	}
		echo '<prices>';
		$query_get_info = "SELECT `rel_ids` 
		FROM `prod_relationships` 
		WHERE `prod_id` = '".$row_getImage['prod_id']."'";
		$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
		$row_get_info = mysql_fetch_assoc($get_info);
		
		$Avail = $row_get_info['rel_ids'];
		$Avail = explode("[+]",$Avail);
		
		$foundprices = false;
		foreach($Avail as $k => $v){
			$query_get_info = "SELECT `prod_name`, `prod_price`, `prod_desc`, `prod_id` 
				FROM `prod_products`
				WHERE `prod_id` = '".$v."' 
					AND `prod_use` = 'y'";
			$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
			$row_get_info = mysql_fetch_assoc($get_info);
			$total_get_info = mysql_num_rows($get_info);
			foreach($prices as $k2 => $v2){
				$id = explode(":",$v2);
				if($v == $id[0] && $total_get_info){
					echo '	<price id="'.$row_get_info['prod_id'].'" name="'.$row_get_info['prod_name'].'" price="'.$id[1].'">'.$row_get_info['prod_desc'].'</price>';
					$foundprices = true;
					break;
				}
			}
		}
		if($foundprices == false){
			foreach($Avail as $k => $v){
				$query_get_info = "SELECT `prod_name`, `prod_price`, `prod_desc`, `prod_id` 
					FROM `prod_products`
					WHERE `prod_id` = '".$v."' 
						AND `prod_use` = 'y'";
				$get_info = mysql_query($query_get_info, $cp_connection) or die(mysql_error());
				$row_get_info = mysql_fetch_assoc($get_info);
				$total_get_info = mysql_num_rows($get_info);
				if($total_get_info){
					echo '	<price id="'.$row_get_info['prod_id'].'" name="'.$row_get_info['prod_name'].'" price="'.($row_get_info['prod_price']*2).'">'.$row_get_info['prod_desc'].'</price>';
				}
			}
		}
		echo '</prices>';
	echo '</border>';}
echo '</borders>';
?>