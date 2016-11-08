<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php';
require_once $r_path.'scripts/fnct_clean_entry.php';
$PId = $path[count($path)-1];
if(isset($_POST['Parnt_Page'])){
	$PPages = $_POST['Parnt_Page'];
	$Count = count($PPages);
	$PPage = $PPages[$Count-1];
} else {
	$PPages = array();
	$PPages[0] = "-1";
	$PPage = '0';
}
$PSel_Page = (isset($_POST['Sel_Parnt_Page'])) ? $_POST['Sel_Parnt_Page'] : array('-1');
$PNav = (isset($_POST['Is_in_Navigation'])) ? clean_variable($_POST['Is_in_Navigation'],true) : 'n';
$PUse = (isset($_POST['Use_Parent_Navigation'])) ? clean_variable($_POST['Use_Parent_Navigation'],true) : 'n';
$PNName = (isset($_POST['Navigation_Name'])) ? clean_variable($_POST['Navigation_Name'],true) : '';
$PageId = (isset($_POST['Page_Info_Id'])) ? clean_variable($_POST['Page_Info_Id'],true) : '';
$PName = (isset($_POST['Page_Name'])) ? clean_variable($_POST['Page_Name'],true) : '';
$PHead1 = (isset($_POST['Header_1'])) ? clean_variable($_POST['Header_1'],true) : '';
$PHead2 = (isset($_POST['Header_2'])) ? clean_variable($_POST['Header_2'],true) : '';
$PTag1 = (isset($_POST['Tag_Line_1'])) ? clean_variable($_POST['Tag_Line_1'],true) : '';
$PTag2 = (isset($_POST['Tag_Line_2'])) ? clean_variable($_POST['Tag_Line_2'],true) : '';
$PStyle = (isset($_POST['Page_Style'])) ? clean_variable($_POST['Page_Style'],true) : 0;
$PBId = (isset($_POST['Text_Ids'])) ? $_POST['Text_Ids'] : array('');
$PBHead = (isset($_POST['Text_Header'])) ? $_POST['Text_Header'] : array('');
$PBDesc = array();
if(count($PBHead)>0){
	foreach($PBHead as $k => $v) array_push($PBDesc, clean_variable($_POST['Description_'.($k+1)]));
} else {
	$PBDesc = (isset($_POST['Description_1'])) ? array($_POST['Description_1']) : array('');
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "Save" && ($cont == 'add' || $cont == 'edit')){
	if($PPage <= -1 && count($PPages)>1){
		$n = count($PPages);
		do{	
			$n--;
			$PPages_db = $PPages[$n];
		} while($PPages[$n] <= -1 && $n>=0);
	} else {
		$PPages_db = $PPage;
	}
	if($cont == "add"){
		if($_POST['Time'] != $_SESSION['Time']){
			$_SESSION['Time'] = $_POST['Time'];	
			$query_get_next = "SELECT `nav_order` FROM `web_review_navigation` WHERE `nav_part_id` = '$PPages_db' ORDER BY `nav_order` DESC LIMIT 0,1";
			$get_next = mysql_query($query_get_next, $cp_connection) or die(mysql_error());
			$row_get_next = mysql_fetch_assoc($get_next);			
			$next_order = $row_get_next['nav_order']+1;			
			mysql_free_result($get_next);
			
			$add = "INSERT INTO `web_review_navigation` (`nav_part_id`,`nav_name`,`nav_order`,`nav_is_nav`, `nav_us`) VALUES ('$PPages_db','$PNName','$next_order','$PNav','$PUse');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$query_get_last = "SELECT `nav_id` FROM `web_review_navigation` ORDER BY `nav_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);			
			$PId = $row_get_last['nav_id'];	
			
			array_push($path,$PId);		
			mysql_free_result($get_last);
			
			$add = "INSERT INTO `web_review_pages` (`nav_id`,`page_name`,`page_header_1`,`page_header_2`,`page_tag_1`,`page_tag_2`,`page_style`) VALUES ('$PId','$PName','$PHead1','$PHead2','$PTag1','$PTag2','$PStyle');";
			$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			
			$query_get_last = "SELECT `page_id` FROM `web_review_pages` WHERE `nav_id` = '$PId' ORDER BY `page_id` DESC LIMIT 0,1";
			$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
			$row_get_last = mysql_fetch_assoc($get_last);			
			$PageId = $row_get_last['page_id'];
				
			mysql_free_result($get_last);
			
			foreach($PBHead as $k => $v){
				if($v == "" &&  $PBDesc[$k] == ""){
				} else {
					$text = clean_variable($PBDesc[$k],'Store');
					$next_order = $k+1;
					$add = "INSERT INTO `web_review_page_text` (`page_id`,`page_text_header`,`page_text_text`,`page_text_order`) VALUES ('$PageId','$v','$text','$next_order');";
					$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
				}
			}
		}
	} else {
		$query_check_info = "SELECT `nav_part_id`, `nav_order` FROM `web_review_navigation` WHERE `nav_id` = '$PId'";
		$check_info = mysql_query($query_check_info, $cp_connection) or die(mysql_error());
		$row_check_info = mysql_fetch_assoc($check_info);	
		$cur_id = $row_check_info['nav_part_id'];
		
		if($cur_id != $PPages_db){
			$query_get_next = "SELECT `nav_order` FROM `web_review_navigation` WHERE `nav_part_id` = '$PPages_db' ORDER BY `nav_order` DESC LIMIT 0,1";
			$get_next = mysql_query($query_get_next, $cp_connection) or die(mysql_error());
			$row_get_next = mysql_fetch_assoc($get_next);			
			$next_order = $row_get_next['nav_order']+1;
		} else {
			$next_order = $row_check_info['nav_order'];
		}
		mysql_free_result($check_info);
	
		$upd = "UPDATE `web_review_navigation` SET `nav_part_id` = '$PPages_db',`nav_name` = '$PNName',`nav_order` = '$next_order',`nav_is_nav` = '$PNav', `nav_us` = '$PUse' WHERE `nav_id` = '$PId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
		$upd = "UPDATE `web_review_pages` SET `page_name` = '$PName',`page_header_1` = '$PHead1',`page_header_2` = '$PHead2',`page_tag_1` = '$PTag1',`page_tag_2` = '$PTag2',`page_style` = '$PStyle' WHERE `page_id` = '$PageId'";
		$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
		
		$query_check_info = "SELECT `page_text_id` FROM `web_review_page_text` WHERE `page_id` = '$PageId'";
		$check_info = mysql_query($query_check_info, $cp_connection) or die(mysql_error());
		while($row_check_info = mysql_fetch_assoc($check_info)) {
			$cur_id = $row_check_info['page_text_id'];
			foreach($PBId as $k => $v){
				switch($v){
					case $cur_id:
						$action = "Update";
						$key = $k;
						break 2;
					default:
						$action = "Delete";
						$key = false;
      			break;
				}
			}
			if($action == "Update"){
				$text = clean_variable($PBDesc[$key],'Store');
				$upd = "UPDATE `web_review_page_text` SET `page_text_header` = '$PBHead[$key]',`page_text_text` = '$text' WHERE `page_text_id` = '$cur_id'";
				$updinfo = mysql_query($upd, $cp_connection) or die(mysql_error());
				
				unset($PBId[$key]);
				unset($PBHead[$key]);
				unset($PBDesc[$key]);
			} else if($action == "Delete"){
				$del= "DELETE FROM `web_review_page_text` WHERE `page_text_id` = '$cur_id'";
				$delinfo = mysql_query($del, $cp_connection) or die(mysql_error());
				
				$optimize = "OPTIMIZE TABLE `web_review_page_text`";
				$optimizeinfo = mysql_query($optimize, $cp_connection) or die(mysql_error());
			}
		}
		mysql_free_result($check_info);
		
		foreach($PBId as $k => $v){
			if($PBHead[$k] != "" || $PBDesc[$k] != ""){
				$query_get_last = "SELECT `page_text_order` FROM `web_review_page_text` WHERE `page_id` = '$PageId' ORDER BY `page_text_order` DESC LIMIT 0,1";
				$get_last = mysql_query($query_get_last, $cp_connection) or die(mysql_error());
				$row_get_last = mysql_fetch_assoc($get_last);					
				$next_order = $row_get_last['nav_order']+1;					
				mysql_free_result($get_last);

				$text = clean_variable($PBDesc[$k],'Store');
				$add = "INSERT INTO `web_review_page_text` (`page_id`,`page_text_header`,`page_text_text`,`page_text_order`) VALUES ('$PageId','$PBHead[$k]','$text','$next_order');";
				$addinfo = mysql_query($add, $cp_connection) or die(mysql_error());
			}
		}
		$PBId = (isset($_POST['Text_Ids'])) ? $_POST['Text_Ids'] : array('');
		$PBHead = (isset($_POST['Text_Header'])) ? $_POST['Text_Header'] : array('');
		$PBDesc = array();
		if(count($PBHead)>0){
			foreach($PBHead as $k => $v) array_push($PBDesc, clean_variable($_POST['Description_'.($k+1)]));
		} else {
			$PBDesc = (isset($_POST['Description_1'])) ? array($_POST['Description_1']) : array('');
		}
	}
	$cont = "view";
} else {
	if($cont != "add"){
		if(isset($_POST['Controller']) && $_POST['Controller'] == "Reset"){
			$query_info = "SELECT * FROM `web_navigation` WHERE `nav_id` = '$PId'";
			$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			$row_info = mysql_fetch_assoc($info);
		} else {
			$query_info = "SELECT * FROM `web_review_navigation` WHERE `nav_id` = '$PId'";
			$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			$row_info = mysql_fetch_assoc($info);
		}
		
		$PName = $row_info['proj_name'];
		if(isset($_POST['Parnt_Page'])){
			$PPages = $_POST['Parnt_Page'];
			$Count = count($PPages);
			$PPage = $PPages[$Count-1];
		} else {
			$PPage = $row_info['nav_part_id'];
			if($PPage == "-1" || $PPage == "0"){
				$PPages = array($PPage);
			} else {
				$PPages = array();
				$parents = array();
				find_parents($PPage, 0, 'web_review_navigation', 'nav_part_id', 'nav_id');
				foreach($parents as $k => $v){
					if($v[0] != -1 && $v[0] != "") array_push($PPages,$v[0]);
				}
				array_push($PPages,$PPage);
			}
		}
		$PSel_Page = (isset($_POST['Sel_Parnt_Page'])) ? $_POST['Sel_Parnt_Page'] : $PPages;
		$PNav = $row_info['nav_is_nav'];
		$PUse = $row_info['nav_us'];
		$PNName = $row_info['nav_name'];
		
		mysql_free_result($info);
		
		if(isset($_POST['Controller']) && $_POST['Controller'] == "Reset"){
			$query_info = "SELECT * FROM `web_pages` WHERE `nav_id` = '$PId'";
			$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			$row_info = mysql_fetch_assoc($info);
		} else {
			$query_info = "SELECT * FROM `web_review_pages` WHERE `nav_id` = '$PId'";
			$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			$row_info = mysql_fetch_assoc($info);
		}
				
		$PageId = $row_info['page_id'];
		$PName = $row_info['page_name'];
		$PHead1 = $row_info['page_header_1'];
		$PHead2 = $row_info['page_header_2'];
		$PTag1 = $row_info['page_tag_1'];
		$PTag2 = $row_info['page_tag_2'];
		$PStyle = $row_info['page_style'];
		
		mysql_free_result($info);
		
		if(!isset($_POST['Text_Ids'])){
			if(isset($_POST['Controller']) && $_POST['Controller'] == "Reset"){
				$query_info = "SELECT * FROM `web_page_text` WHERE `page_id` = '$PageId'";
				$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			} else {
				$query_info = "SELECT * FROM `web_review_page_text` WHERE `page_id` = '$PageId'";
				$info = mysql_query($query_info, $cp_connection) or die(mysql_error());
			}
			$PBId = array();
			$PBHead = array();
			$PBDesc = array();
			while($row_info = mysql_fetch_assoc($info)){
				array_push($PBId,$row_info['page_text_id']);
				array_push($PBHead,$row_info['page_text_header']);
				array_push($PBDesc,$row_info['page_text_text']);
			}
			mysql_free_result($info);
		}
	}
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "new_block"){
	array_push($PBId,"-1");
	array_push($PBHead,"");
	array_push($PBDesc,"");
}
if(isset($_POST['Controller']) && $_POST['Controller'] == "remove_block"){
	array_splice($PBId, $_POST['Rmv_Id'], 1);
	array_splice($PBHead, $_POST['Rmv_Id'], 1);
	array_splice($PBDesc, $_POST['Rmv_Id'], 1);
}
?>