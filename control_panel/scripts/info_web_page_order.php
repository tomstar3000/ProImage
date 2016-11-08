<? if(!isset($r_path)){
	$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
	$r_path = "";
	for($n=0;$n<$count;$n++)$r_path .= "../";
}
include $r_path.'security.php'; ?>
<script type="text/javascript">
function compute(addthis,orderval,fieldvar,e){
	if(window.event) {
		// for IE, e.keyCode or window.event.keyCode can be used
		keycodeval = e.keyCode; 
	} else if(e.which) {
		// Netscape
		keycodeval = e.which; 
	}
	if(keycodeval < 32 || (keycodeval >= 33 && keycodeval <= 46) || (keycodeval >= 112 && keycodeval <= 123)){
		changeorder(addthis,orderval,fieldvar);
	} else {
		if(orderval.match(/^\d+$/)){
			changeorder(addthis,orderval,fieldvar);
		} else {
			alert ("Value Must Be a Number!");
		}
	}
	delete keycodeval;
	
}
function changeorder(addthis,orderval,fieldvar){
	pagecount = "pagecount"+addthis;
	valarray = new Array();
	idarray = new Array();
	a = 0;
	for(n=1;n<=document.getElementById(pagecount).value;n++){
		pageid = "Page_Order"+addthis+"_"+n;
		thisvalue = parseInt(document.getElementById(pageid).value); 
		if(thisvalue==orderval && pageid != fieldvar.id){
			if(orderval == 1){
				thisvalue++;
			} else if (orderval == document.getElementById(pagecount).value){
				thisvalue--;
			} else {
				thisvalue++;
			}
		} else if (thisvalue>orderval){
			thisvalue++;
		} else if (thisvalue<orderval){
			thisvalue--;
		}
		idarray[a] = n;
		valarray[a] = thisvalue;
		a++;
	}
	sortarray = new Array(idarray,valarray);
	sortedarray = sortthearray(sortarray);
	for(n=0;n<sortedarray[0].length;n++){
		pageid = "Page_Order"+addthis+"_"+sortedarray[0][n];
		document.getElementById(pageid).value = (n+1);
	}
}
function sortthearray(thearray){
	var holder = new Array();
	for (var i = 0; i < thearray[0].length; i++) {
		if(thearray[1][i]>thearray[1][i+1]){
			holder[0] = thearray[0][i];
			holder[1] = thearray[1][i];
			thearray[0][i] = thearray[0][i+1];
			thearray[1][i] = thearray[1][i+1];
			thearray[0][i+1] = holder[0];
			thearray[1][i+1] = holder[1];
			sortthearray(thearray);
		}
  	}
	return thearray;
}
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
 <tr>
  <th colspan="2" id="Form_Header"> <img src="/<? echo $AevNet_Path; ?>/images/hdr_form_left.jpg" width="18" height="33" align="left" />
   <p>Website Page Order </p></th>
 </tr>
 <? foreach ($PPages as $k => $v){
			if($k==0)	$Prnt_Id = -1; else $Prnt_Id = $PPages[$k-1];
			$Cur_id = $v;
			$query_get_pages = "SELECT `nav_id`,`nav_name` FROM `web_review_navigation` WHERE `nav_part_id` = '$Prnt_Id' ORDER BY `nav_name` ASC";
			$get_pages = mysql_query($query_get_pages, $cp_connection) or die(mysql_error());
			$row_get_pages = mysql_fetch_assoc($get_pages);
			$total_get_pages = mysql_num_rows($get_pages);
			if($total_get_pages  > 0){ ?>
 <tr>
  <td width="20%"><strong>Parent Page :</strong></td>
  <td width="80%"><select name="Parnt_Page[]" id="Parnt_Page[]" onchange="document.getElementById('Controller').value='false'; this.form.submit();">
    <? if($k==0){ ?>
    <option value="-1"<? if($Cur_id == -1)echo ' selected="selected"'; ?>>-- Main Navigation --</option>
    <option value="0"<? if($Cur_id == 0)echo ' selected="selected"'; ?>>Home Page</option>
    <? } else { ?>
    <option value="-1"<? if($Cur_id == -1)echo ' selected="selected"'; ?>>-- Select Parent --</option>
    <? } do { ?>
    <option value="<? echo $row_get_pages['nav_id']; ?>"<? if($row_get_pages['nav_id'] == $Cur_id)echo ' selected="selected"'; ?>>
    <? echo substr($row_get_pages['nav_name'],0,25);
		if(strlen($row_get_pages['nav_name'])>25) echo "..."; ?>
    </option>
    <? } while ($row_get_pages = mysql_fetch_assoc($get_pages)); ?>
   </select>
   <input type="hidden" name="Sel_Parnt_Page[]" id="Sel_Parnt_Page[]" value="<? echo $Cur_id; ?>" />
   &nbsp;</td>
 </tr>
 <? } mysql_free_result($get_pages);
		if($PSel_Page[$k]!= $v) break; 
	}
	
	$query_get_pages = "SELECT `nav_id`,`nav_name` FROM `web_review_navigation` WHERE `nav_part_id` = '$Cur_id'";
	$get_pages = mysql_query($query_get_pages, $cp_connection) or die(mysql_error());
	$row_get_pages = mysql_fetch_assoc($get_pages);
	$totalRows_get_pages = mysql_num_rows($get_pages);
	
	if($totalRows_get_pages != 0 && $Cur_id != -1){ ?>
 <tr>
  <td><strong>Parent Page :</strong></td>
  <td><select name="Parnt_Page[]" id="Parnt_Page[]" onchange="document.getElementById('Controller').value='false'; this.form.submit();">
    <option value="-1">-- Select Parent Page --</option>
    <? do { ?>
    <option value="<? echo $row_get_pages['nav_id']; ?>">
    <?
	  echo substr($row_get_pages['nav_name'],0,25);
		if(strlen($row_get_pages['nav_name'])>25) echo "..."; ?>
    </option>
    <? } while ($row_get_pages = mysql_fetch_assoc($get_pages)); ?>
   </select>
   <input type="hidden" name="Sel_Parnt_Page[]" id="Sel_Parnt_Page[]" value="-1" />
   &nbsp;</td>
 </tr>
 <? }	mysql_free_result($get_pages);	
	foreach($PPages as $k => $v){
		if($v == "-1"){
			if($k == 0){
				$Cur_id = $PPages[$k];
				break;		
			} else {
				$Cur_id = $PPages[$k-1];
				break;
			}
		}
	}
	$query_get_child_pages = "SELECT `nav_id`,`nav_name`,`nav_order` FROM `web_review_navigation` WHERE `nav_part_id` = '$Cur_id' ORDER BY `nav_order` ASC";
	$get_child_pages = mysql_query($query_get_child_pages, $cp_connection) or die(mysql_error());
	$row_get_child_pages = mysql_fetch_assoc($get_child_pages);
	$totalRows_get_child_pages = mysql_num_rows($get_child_pages);
	
	if($totalRows_get_child_pages <= 0){
		echo '<tr><td colspan="2">There are no pages in this selection</td></tr>';
	} else {
		$n = 0;
		do{	$n++;
	?>
 <tr>
  <td>&nbsp;</td>
  <td><input type="text" id="Page_Order_<? echo $n; ?>" name="Page_Order[]" value="<? echo $row_get_child_pages['nav_order']; ?>" size="3" onkeyup="compute('',this.value,this,event);" />
   &nbsp;<? echo $row_get_child_pages['nav_name']; ?>
   <input type="hidden" name="Page_Id[]" id="Page_Id[]" value="<? echo $row_get_child_pages['nav_id']; ?>" /></td>
 </tr>
 <? } while($row_get_child_pages = mysql_fetch_assoc($get_child_pages)); }
	mysql_free_result($get_child_pages); ?>
 <tr>
  <td><input type="hidden" name="pagecount" id="pagecount" value="<? echo $n; ?>"></td>
 </tr>
</table>
