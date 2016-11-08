<? if(!isset($r_path)){	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;	$r_path = "";	for($n=0;$n<$count;$n++)$r_path .= "../"; }
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';

$getOrders = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getOrders->mysql("SELECT *
	FROM (
			(SELECT `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_date`, `photo_event`.`event_id`, `photo_event`.`event_num`, `online_customers`.`cust_fname`, `online_customers`.`cust_lname`, `orders_invoice`.`invoice_grand`, `orders_invoice`.`invoice_accepted`, `orders_invoice`.`invoice_printed`, `orders_invoice`.`invoice_comp`
			FROM `orders_invoice` 
			INNER JOIN `cust_customers` AS `online_customers`
				ON `online_customers`.`cust_id` = `orders_invoice`.`cust_id`
			INNER JOIN `orders_invoice_photo`
				ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
			INNER JOIN `photo_event_images`
				ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
			INNER JOIN `photo_event_group`
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
			INNER JOIN `photo_event`
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
			WHERE `photo_event_images`.`cust_id` = '$CustId'
			GROUP BY `orders_invoice`.`invoice_id`)
		UNION 
			(SELECT `orders_invoice`.`invoice_id`, `orders_invoice`.`invoice_num`, `orders_invoice`.`invoice_date`, `photo_event`.`event_id`, `photo_event`.`event_num`, `online_customers`.`cust_fname`, `online_customers`.`cust_lname`, `orders_invoice`.`invoice_grand`, `orders_invoice`.`invoice_accepted`, `orders_invoice`.`invoice_printed`, `orders_invoice`.`invoice_comp`
			FROM `orders_invoice` 
			INNER JOIN `cust_customers` AS `online_customers`
				ON `online_customers`.`cust_id` = `orders_invoice`.`cust_id`
			INNER JOIN `orders_invoice_border`
				ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id`
			INNER JOIN `photo_event_images`
				ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`
			INNER JOIN `photo_event_group`
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
			INNER JOIN `photo_event`
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
			WHERE `photo_event_images`.`cust_id` = '$CustId'
			GROUP BY `orders_invoice`.`invoice_id`)
	) AS `DV1`
GROUP BY `invoice_id`
ORDER BY `invoice_date` DESC
LIMIT 0,10;");
$getOrdersCnt = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getOrdersCnt->mysql("SELECT SUM(`invoice_count`) AS `invoice_count`
	FROM (
			(SELECT COUNT(`orders_invoice`.`invoice_id`) AS `invoice_count`
			FROM `orders_invoice` 
			INNER JOIN `orders_invoice_photo`
				ON `orders_invoice_photo`.`invoice_id` = `orders_invoice`.`invoice_id`
			INNER JOIN `photo_event_images`
				ON `photo_event_images`.`image_id` = `orders_invoice_photo`.`image_id`
			INNER JOIN `photo_event_group`
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
			INNER JOIN `photo_event`
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
			WHERE `photo_event_images`.`cust_id` = '$CustId'
				AND `orders_invoice`.`invoice_accepted` = 'n')
		UNION 
			(SELECT COUNT(`orders_invoice`.`invoice_id`) AS `invoice_count`
			FROM `orders_invoice` 
			INNER JOIN `orders_invoice_border`
				ON `orders_invoice_border`.`invoice_id` = `orders_invoice`.`invoice_id`
			INNER JOIN `photo_event_images`
				ON `photo_event_images`.`image_id` = `orders_invoice_border`.`image_id`
			INNER JOIN `photo_event_group`
				ON `photo_event_group`.`group_id` = `photo_event_images`.`group_id`
			INNER JOIN `photo_event`
				ON `photo_event`.`event_id` = `photo_event_group`.`event_id`
			WHERE `photo_event_images`.`cust_id` = '$CustId'
				AND `orders_invoice`.`invoice_accepted` = 'n')
	) AS `DV1`;");
$getOrdersCnt = $getOrdersCnt->Rows(); $getOrdersCnt = $getOrdersCnt[0]['invoice_count'];

$getEvents = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getEvents->mysql("SELECT 
	DISTINCT COUNT(`photo_event_images`.`image_id`) AS `image_count`,
	(SELECT count(`group_id`) 
		FROM `photo_event_group` 
		WHERE `photo_event_group`.`event_id` = `photo_event`.`event_id` 
			AND `photo_event_group`.`group_use` = 'y') AS `group_count`,
	ROUND((SUM(`photo_event_images`.`image_size`)*100)/100) AS `total_size`,
	`photo_event`.`event_id`,
	`photo_event`.`event_name`,
	`photo_event`.`event_date`,
	`photo_event`.`owner_location`,
	`photo_photographers`.`photo_fname`,
	`photo_photographers`.`photo_lname`
	FROM `photo_event`
	LEFT JOIN `photo_event_group`
		ON (`photo_event_group`.`event_id` = `photo_event`.`event_id`
		OR `photo_event_group`.`event_id` IS NULL)
		AND `photo_event_group`.`group_use` = 'y'
	LEFT JOIN `photo_event_images`
		ON (`photo_event_images`.`group_id` = `photo_event_group`.`group_id`
		OR `photo_event_images`.`group_id` IS NULL)
		AND `photo_event_images`.`image_active` = 'y'
	LEFT JOIN `photo_photographers`
		ON (`photo_event`.`photo_id` = `photo_photographers`.`photo_id`
			OR `photo_event`.`photo_id` IS NULL)
	WHERE `photo_event`.`cust_id` = '$CustId'
		AND `event_use` = 'y'
		AND `event_end` >= NOW()
	GROUP BY `photo_event`.`event_id`
	ORDER BY `photo_event`.`event_date` DESC
	LIMIT 0,5;");
//$getEventsCnt = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
//$getEventsCnt->mysql("SELECT COUNT(`event_id`) AS `event_count`
//	FROM `photo_event`
//	WHERE `cust_id` = '$CustId'
//		AND `event_use` = 'y'
//		AND `event_end` >= NOW()
//          ;");
$getEventsCnt = $getEvents->TotalRows();

$getUpdates = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getUpdates->mysql("SELECT `page_text_text`
	FROM `web_page_text`
	WHERE `page_id` = '1'
	ORDER BY `page_text_order` ASC;");

$getPosts = new sql_processor($database_cp_connection,$cp_connection,$gateways_cp_connection);
$getPosts->mysql("SELECT `post_subject`, `post_text`, `post_time`, `post_username`
	FROM `phpbb3_posts`
	WHERE `post_approved` = '1'
	ORDER BY `post_time` DESC
	LIMIT 0,1;");
$getPosts = $getPosts->Rows();
?>
