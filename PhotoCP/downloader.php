<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
switch(intval($_GET['type'])){
	case 1: include $r_path.'includes/dwn_photo_quest_promo_csv.php'; break;
	case 2: include $r_path.'includes/dwn_photo_quest_promo_excel.php'; break;
	case 3: include $r_path.'includes/dwn_photo_quest_csv.php'; break;
	case 4: include $r_path.'includes/dwn_photo_quest_excel.php'; break;
	case 5: include $r_path.'includes/dwn_photo_event_notification.php'; break;
}

?>