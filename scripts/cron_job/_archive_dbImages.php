<?

$count = count(explode("/", substr($_SERVER['PHP_SELF'], 1))) - 1;
$r_path = "";
for ($n = 0; $n < $count; $n++)
	$r_path .= "../";

$r_path = "/srv/proimage/current/";
require_once $r_path . 'Connections/cp_connection.php';
mysql_select_db($database_cp_connection, $cp_connection);

$sql = "

SELECT photo_event.event_id, photo_event_images.*

FROM photo_event_images

LEFT JOIN orders_invoice_photo
	ON (orders_invoice_photo.image_id = photo_event_images.image_id)
LEFT JOIN photo_event_group
	ON (photo_event_group.group_id = photo_event_images.group_id)
LEFT JOIN photo_event
	ON (photo_event.event_id = photo_event_group.event_id)
LEFT JOIN cust_customers
	ON (cust_customers.cust_id = photo_event_images.cust_id AND cust_customers.cust_photo = 'y')

WHERE
(
	(
		image_active = 'n'
			AND DATEDIFF(NOW(), image_time) > 120
			AND orders_invoice_photo.image_id IS NULL
	)
	OR
	(
		photo_event_group.group_id IS NULL
			OR photo_event.event_id IS NULL
			OR cust_customers.cust_id IS NULL
			AND orders_invoice_photo.image_id IS NULL
	)
	OR
	(
		photo_event.event_id IS NOT NULL
			AND photo_event.event_use = 'n'
			AND DATEDIFF(NOW(), photo_event.event_updated) > 120
			AND orders_invoice_photo.image_id IS NULL
	)
	OR
	(
		cust_customers.cust_id IS NOT NULL
			AND cust_customers.cust_active = 'n'
			AND DATEDIFF(NOW(), cust_customers.cust_modified) > 365
			AND orders_invoice_photo.image_id IS NULL
	)
)
GROUP BY photo_event_images.image_id

LIMIT 1000000
;
";

$get_images = mysql_query($sql, $cp_connection) or die(mysql_error());

$InsertTables = array();
$InsertRows = array();
$image_ids = array();
while ($row_get_images = mysql_fetch_assoc($get_images))
{
	$image_ids[] = $row_get_images['image_id'];
	$key = substr($row_get_images['image_id'], -1);
	$table = 'archive_photo_event_images_' . $key;
	if (!isset($InsertTables[$key]))
	{
		$InsertTables[$key] = "INSERT INTO " . $table . " (image_id, cust_id, group_id, image_name, image_desc, image_tiny, image_small, image_large, image_folder, image_size, imgage_org_rotate, image_rotate, image_color_space, image_time, image_active) VALUES ";
	}
	$InsertRows = " 
		( '" . mysql_escape_string($row_get_images['image_id']) .
					"', '" . mysql_escape_string($row_get_images['cust_id']) .
					"', '" . mysql_escape_string($row_get_images['group_id']) .
					"', '" . mysql_escape_string($row_get_images['image_name']) .
					"', '" . mysql_escape_string($row_get_images['image_desc']) .
					"', '" . mysql_escape_string($row_get_images['image_tiny']) .
					"', '" . mysql_escape_string($row_get_images['image_small']) .
					"', '" . mysql_escape_string($row_get_images['image_large']) .
					"', '" . mysql_escape_string($row_get_images['image_folder']) .
					"', '" . mysql_escape_string($row_get_images['image_size']) .
					"', '" . mysql_escape_string($row_get_images['imgage_org_rotate']) .
					"', '" . mysql_escape_string($row_get_images['image_rotate']) .
					"', '" . mysql_escape_string($row_get_images['image_color_space']) .
					"', '" . mysql_escape_string($row_get_images['image_time']) .
					"', '" . mysql_escape_string($row_get_images['image_active']) . "' ) ";
	$insert = $InsertTables[$key] . ' ' . $InsertRows . '; ';

	var_dump($insert);
	var_dump(mysql_query($insert, $cp_connection) or die(mysql_error()));
}

$deleteImages = 'DELETE FROM photo_event_images WHERE image_id IN (' . implode(', ', $image_ids) . ');';
var_dump($deleteImages);
var_dump(mysql_query($deleteImages, $cp_connection) or die(mysql_error()));
var_dump(count($image_ids));
?>