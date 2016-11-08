<? require_once('../Connections/cp_connection.php'); ?>
<?
define ("<? echo $AevNet_Path; ?> CONTROL PANEL", true);
include 'security.php';
include 'config.php';
$pathing = (isset($_GET['Path'])) ? explode(",",$_GET['Path']) : explode(",","Prod,Prod");
$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2;
$r_path = "";
for($n=0;$n<$count;$n++){
	$r_path .= "../";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? echo $AevNet_Path; ?>: Control Panel</title>
<link href="stylesheet/<? echo $AevNet_Path; ?>.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function open_div(div_layer) {
	var button_array = new Array("Product","Billing_Shipping","Customer","Order","Project","Website","Custom_Form","Administrative");
	for(n=0; n<button_array.length; n++){
		if(div_layer == button_array[n]){
			document.getElementById(button_array[n]+'_Buttons').style.display = "block";
		} else {
			document.getElementById(button_array[n]+'_Buttons').style.display = "none";
		}
	}
}
function page_refresh(){
	window.location.reload(true);
}
//-->
</script>
</head>
<body>
<div id="Main_Table">
  <div id="Nav">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td id="Btn_Product" onclick="open_div('Product');"><h1>Products \/ </h1></td>
        <td id="Btn_Billing_Shipping" onclick="open_div('Billing_Shipping');"><h1>Billing and Shipping Settings \/ </h1></td>
        <td id="Btn_Customer" onclick="open_div('Customer');"><h1>Customers \/ </h1></td>
        <td id="Btn_Order" onclick="open_div('Order');"><h1>Orders and Contracts \/ </h1></td>
        <td id="Btn_Project" onclick="open_div('Project');"><h1>Projects \/ </h1></td>
        <td id="Btn_Website" onclick="open_div('Website');"><h1>Website \/ </h1></td>
        <td id="Btn_Custom_Form" onclick="open_div('Custom_Form');"><h1>Custom Forms \/ </h1></td>
        <td id="Btn_Administrative" onclick="open_div('Administrative');"><h1>Administrative \/ </h1></td>
      </tr>
    </table>
  </div>
  <div id="Container">
    <div id="Product_Buttons"<? if($pathing[0] != "Prod"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Prod"><img src="images/btn_products.jpg" alt="Products" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Cat"><img src="images/btn_categories.jpg" alt="Categories" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Feat"><img src="images/btn_features.jpg" alt="Features" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Spec"><img src="images/btn_specs.jpg" alt="Specs" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Attrib"><img src="images/btn_attribute.jpg" alt="Attributes" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Group"><img src="images/btn_groups.jpg" alt="Groups" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Specl"><img src="images/btn_special.jpg" alt="Special Delivery Requirements" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Prod,Man"><img src="images/btn_manufactures.gif" alt="Manufactures" width="50" height="50" border="0" /></a></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Billing_Shipping_Buttons"<? if($pathing[0] != "BillShip"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><img src="images/" alt="Credit Cards" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Shipping Companies" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="State Taxes" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="County Taxes" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Customer_Buttons"<? if($pathing[0] != "Cust"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Cust,All"><img src="images/btn_customer.jpg" alt="Customers" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Cust,Online"><img src="images/btn_customer_online.jpg" alt="On-line Customers" width="50" height="50" border="0" /></a></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Order_Buttons"<? if($pathing[0] != "Inv"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Inv,Open"><img src="images/" alt="Open Invoices" width="50" height="50" border="0" /></a></td>
          <td align="center"><img src="images/" alt="All Invoices" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Open Service Orders" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="All Service Orders" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Service Order Services" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Groups" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Service Order Settings" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Invoice Contract" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Service Order Contract" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="General Contracts" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Project_Buttons"<? if($pathing[0] != "Proj"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Proj,Open"><img src="images/btn_projects_open.jpg" alt="Open Projects" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Proj,All"><img src="images/btn_projects_all.jpg" alt="All Projects" width="50" height="50" border="0" /></a></td>
          <td align="center"><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Proj,Cat"><img src="images/btn_projects_cat.jpg" alt="Project Categories" width="50" height="50" border="0" /></a></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Website_Buttons"<? if($pathing[0] != "Web"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><img src="images/" alt="Home Page" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="navigation" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Website Pages" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Review Changes" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Save Changes" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Contact Information" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Testimonials" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Events" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Event Contacts" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Event Locations" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Guestbook" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Links" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="News" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Press Releases" width="50" height="50" /></td>
        </tr>
        <tr>
          <td align="center"><img src="images/" alt="Maps" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Hotels" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Disclaimer" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Custom_Form_Buttons"<? if($pathing[0] != "CustForm"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><img src="images/" alt="Custom Forms" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Custom Tables" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
    <div id="Administrative_Buttons"<? if($pathing[0] != "Admin"){ print(" style=\"display: none;\""); } ?>>
      <table border="0" cellpadding="5" cellspacing="0" width="100%">
        <tr>
          <td align="center"><img src="images/" alt="User Settings" width="50" height="50" /></td>
          <td align="center"><img src="images/" alt="Employees" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
          <td align="center"><img src="images/spacer.gif" width="50" height="50" /></td>
        </tr>
      </table>
    </div>
  </div>
  <div id="Action_Div">
    <?
		mysql_select_db($database_cp_connection, $cp_connection);
		include 'scripts/fnct_record_table.php';
		if($pathing[0] == "Prod"){
			if($pathing[1] == "Prod"){
				if(isset($_GET['id'])){
					include 'scripts/view_products.php';
				} else {
					include 'scripts/query_products.php';
				}
			} else if($pathing[1] == "Cat"){
				include 'scripts/query_categories.php';
			} else if($pathing[1] == "Feat"){
				include 'scripts/query_features.php';
			} else if($pathing[1] == "Spec"){
				include 'scripts/query_specs.php';
			} else if($pathing[1] == "Attrib"){
				include 'scripts/query_attribute.php';
			} else if($pathing[1] == "Group"){
				include 'scripts/query_groups.php';
			} else if ($pathing[1] == "Specl"){
				include 'scripts/query_specl.php';
			} else if($pathing[1] == "Man"){
				if(isset($_GET['id'])){
					include 'scripts/view_manufactures.php';
				} else {
					include 'scripts/query_manufactures.php';
				}
			}
		} else if($pathing[0] == "Cust"){
			if(isset($_GET['id'])){
				if($pathing[1] == "Proj"){
					include 'scripts/view_projects.php';
				} else {
					include 'scripts/view_customers.php';
				}
			} else {
				include 'scripts/query_customers.php';
			}
			
		} else if($pathing[0] == "Inv"){
			if($pathing[1] == "Open"){
				include 'scripts/query_invoice.php';
			}		
		} else if($pathing[0] == "Proj"){
			if($pathing[1] == "Open" || $pathing[1] == "All"){
				if(isset($_GET['id'])){
					include 'scripts/view_projects.php';
				} else {
					include 'scripts/query_projects.php';
				}
			} else if($pathing[1] == "Cat"){
				include 'scripts/query_proj_categories.php';
			}
		}
?>
  </div><br clear="all"/>
</div>
</body>
</html>
