<h1>Control Panel</h1>
<ul id="Main_Nav">
  <? if($loginsession[2] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Prod','query','','');" title="Products"<? if($path[0] == "Prod")print(' class="Main_Nav_Select"');?>>Products</a></li>
  <? if($path[0] == "Prod"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[3] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Prod','query','','');" title="Products"<? if($path[1] == "Prod")print(' class="Sub_Nav_Select"');?>>Products</a></li>
  <li><a href="#" onclick="javascript:set_form('','Prod,Bord','query','','');" title="Borders"<? if($path[1] == "Bord")print(' class="Sub_Nav_Select"');?>>Borders</a></li>
  <li><a href="#" onclick="javascript:set_form('','Prod,Serv','query','','');" title="Services"<? if($path[1] == "Serv")print(' class="Sub_Nav_Select"');?>>Services</a></li>
  <? } if($loginsession[4] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Cat','query','','');" title="Categories"<? if($path[1] == "Cat")print(' class="Sub_Nav_Select"');?>>Categories</a></li>
  <? } if($loginsession[56] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Discount','query','','');" title="Discount Codes"<? if($path[1] == "Discount")print(' class="Sub_Nav_Select"');?>>Discount
      Codes</a></li>
  <? } if($loginsession[5] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Feat','query','','');" title="Features"<? if($path[1] == "Feat")print(' class="Sub_Nav_Select"');?>>Features</a></li>
  <? } if($loginsession[6] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Spec','query','','');" title="Specs"<? if($path[1] == "Spec")print(' class="Sub_Nav_Select"');?>>Specs</a></li>
  <? } if($loginsession[7] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Attrib','query','','');" title="Attributes"<? if($path[1] == "Attrib")print(' class="Sub_Nav_Select"');?>>Attributes</a></li>
  <? } if($loginsession[8] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Group','query','','');" title="Groups"<? if($path[1] == "Group")print(' class="Sub_Nav_Select"');?>>Groups</a></li>
  <? } if($loginsession[9] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Specl','query','','');" title="Special Delivery Costs"<? if($path[1] == "Specl")print(' class="Sub_Nav_Select"');?>>Special
      Delivery</a></li>
  <? } if($loginsession[56] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Sel','query','','');" title="Product Selections Groups"<? if($path[1] == "Sel")print(' class="Sub_Nav_Select"');?>>Selections
      Groups</a></li>
  <? } if($loginsession[10] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Prod,Man','query','','');" title="Manufactures"<? if($path[1] == "Man")print(' class="Sub_Nav_Select"');?>>Manufactures</a></li>
  <? } ?>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[11] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','BillShip,Credit','edit','','');"<? if($pathing[0] == "BillShip")print(' class="Main_Nav_Select"');?>>Billing
      and Shipping</a></li>
  <? if($path[0] == "BillShip"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[12] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','BillShip,Credit','edit','','');" title="Credit Cards"<? if($path[1] == "Credit")print(' class="Sub_Nav_Select"');?>>Credit
      Cards</a></li>
  <? } if($loginsession[13] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','BillShip,Ship','edit','','');" title="Shipping Companies"<? if($path[1] == "Ship")print(' class="Sub_Nav_Select"');?>>Shipping
      Companies</a></li>
  <? } if($loginsession[14] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','BillShip,State','query','','');" title="State Taxes"<? if($path[1] == "State")print(' class="Sub_Nav_Select"');?>>State
      Taxes</a></li>
  <? } if($loginsession[15] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','BillShip,Count','query','','');" title="County Taxes"<? if($path[1] == "Count")print(' class="Sub_Nav_Select"');?>>County
      Taxes </a></li>
  <? } ?>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[16] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Cust,All','query','','');"<? if($path[0] == "Cust")print(' class="Main_Nav_Select"');?>>Customers</a></li>
  <? if($path[0] == "Cust"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[17] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Cust,All','query','','');" title="Active"<? if($path[1] == "All")print(' class="Sub_Nav_Select"');?>>Active </a></li>
  <? } if($loginsession[18] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Cust,Online','query','','');" title="On-Line Customers"<? if($path[1] == "Online")print(' class="Sub_Nav_Select"');?>>On-Line
      Customers </a></li>
  <? } ?>
</ul>
<? } } ?>
</ul>
<ul id="Main_Nav">
  <li><a href="javascript:set_form('','Evnt,Act','edit','','');"<? if($path[0] == "Evnt")print(' class="Main_Nav_Select"');?>>Re-Activate Event</a></li>
  <? if($loginsession[19] == 1){ ?>
  <li><a href="javascript:set_form('','Inv,Open','query','','');"<? if($path[0] == "Inv")print(' class="Main_Nav_Select"');?>>Orders
      / Invoices</a></li>
  <? if($path[0] == "Inv"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[20] == 1){ ?>
  <li><a href="javascript:set_form('','Inv,Paid','query','','');" title="Photographer Sign-Up"<? if($path[1] == "Paid")print(' class="Sub_Nav_Select"');?>>Photographer
      Sign-Up </a></li>
  <? } if($loginsession[20] == 1){ ?>
  <li><a href="javascript:set_form('','Inv,Open','query','','');" title="New Invoices"<? if($path[1] == "Open")print(' class="Sub_Nav_Select"');?>>
      New Invoices</a></li>
  <li><a href="javascript:set_form('','Inv,Ship','query','','');" title="Un-Shipped Invoices"<? if($path[1] == "Ship")print(' class="Sub_Nav_Select"');?>>    Un-Shipped
      Invoices</a></li>
  <? } if($loginsession[21] == 1){ ?>
  <li><a href="javascript:set_form('','Inv,All','query','','');" title="All Invoices"<? if($path[1] == "All")print(' class="Sub_Nav_Select"');?>>Completed
      Invoices </a></li>
  <? } ?>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[22] == 1){ ?>
  <li><a href="javascript:set_form('','Rept,Daily','query','','');"<? if($path[0] == "Rept")print(' class="Main_Nav_Select"');?>>Reports</a></li>
  <? if($path[0] == "Rept"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[23] == 1){ ?>
  <li><a href="javascript:set_form('','Rept,Daily','query','','');" title="Daily Reports"<? if($path[1] == "Daily")print(' class="Sub_Nav_Select"');?>>Daily
      Reports</a></li>
  <? } if($loginsession[24] == 1){ ?>
  <li><a href="javascript:set_form('','Rept,Monthly','query','','');" title="Monthly Reports"<? if($path[1] == "Monthly")print(' class="Sub_Nav_Select"');?>>Monthly
      Reports</a></li>
  <? } if($loginsession[25] == 1){ ?>
  <li><a href="javascript:set_form('','Rept,Man','query','','');" title="Manufacture Reports"<? if($path[1] == "Man")print(' class="Sub_Nav_Select"');?>>Manufacture
      Reports</a></li>
  <? } if($loginsession[26] == 1){ ?>
  <li><a href="javascript:set_form('','Rept,High','query','','');" title="Manufacture / Customers"<? if($path[1] == "High")print(' class="Sub_Nav_Select"');?>>Manufacture
      / Customers</a></li>
  <? } ?>
  <li><a href="javascript:set_form('','Rept,Comm','query','','');" title="Commission Report"<? if($path[1] == "Comm")print(' class="Sub_Nav_Select"');?>>Commission
      Report</a></li>
	<li><a href="javascript:set_form('','Rept,Tax','query','','');" title="Commission Report"<? if($path[1] == "Tax")print(' class="Sub_Nav_Select"');?>>Taxes
	    / Shipping </a></li>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[27] == 1){ ?>
  <li><a href="#"<? if($pathing[0] == "Cont")print(' class="Main_Nav_Select"');?>>Contracts</a></li>
  <? if($pathing[0] == "Cont"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[28] == 1){ ?>
  <li><a href="#" title="Invoice Contract"<? if($pathing[1] == "Inv")print(' class="Sub_Nav_Select"');?>>Invoice</a></li>
  <? } if($loginsession[29] == 1){ ?>
  <li><a href="#" title="Service Order Contract"<? if($pathing[1] == "Order")print(' class="Sub_Nav_Select"');?>>Service
      Order</a></li>
  <? } if($loginsession[30] == 1){ ?>
  <li><a href="#" title="General Contract"<? if($pathing[1] == "Gen")print(' class="Sub_Nav_Select"');?>>General</a></li>
  <? } ?>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[31] == 1){ ?>
  <li<? if($path[0] == "Proj")print(' class="Main_Nav_Select"');?>><a href="#" onclick="javascript:set_form('','Proj,Open','query','','');">Projects</a></li>
  <? if($path[0] == "Proj"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[32] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Proj,Open','query','','');" title="Open Projects"<?php if($path[1] == "Open")print(' class="Sub_Nav_Select"');?>>Open
      Projects </a></li>
  <? } if($loginsession[33] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Proj,All','query','','');" title="All Projects"<?php if($path[1] == "All")print(' class="Sub_Nav_Select"');?>>All
      Projects </a></li>
  <? } if($loginsession[34] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Proj,Cat','query','','');" title="Project Categories"<?php if($path[1] == "Cat")print(' class="Sub_Nav_Select"');?>>Project
      Categories </a></li>
  <? } ?>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[35] == 1){ ?>
  <li<? if($pathing[0] == "Web")print(' class="Main_Nav_Select"');?>><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Home">Website</a></li>
  <? if($pathing[0] == "Web"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <? if($loginsession[36] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Home" title="Home Page"<? if($pathing[1] == "Home")print(' class="Sub_Nav_Select"');?>>Home
      Page </a></li>
  <? } if($loginsession[37] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Page" title="Website Pages"<? if($pathing[1] == "Page")print(' class="Sub_Nav_Select"');?>>Website
      Pages</a></li>
  <? } if($loginsession[38] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Order" title="Website Page Order"<? if($pathing[1] == "Order")print(' class="Sub_Nav_Select"');?>>Website
      Page Order </a></li>
  <? } if($loginsession[39] == 1){ ?>
  <li><a href="../?review=true" title="Review Changes" target="_blank"<? if($pathing[1] == "Review")print(' class="Sub_Nav_Select"');?>>Review
      Changes </a></li>
  <? } if($loginsession[40] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Save" title="Save Changes"<? if($pathing[1] == "Save")print(' class="Sub_Nav_Select"');?>>Save
      Changes </a></li>
  <? } if($loginsession[41] == 1){ ?>
  <li><a href="#" title="Contact Information"<? if($pathing[1] == "Cont_info")print(' class="Sub_Nav_Select"');?>>Contact
      Information </a></li>
  <? } if($loginsession[42] == 1){ ?>
  <li><a href="#" title="Testimonials"<? if($pathing[1] == "Test")print(' class="Sub_Nav_Select"');?>>Testimonials</a></li>
  <? } if($loginsession[43] == 1){ ?>
  <li><a href="#" title="Events"<? if($pathing[1] == "Evnt")print(' class="Sub_Nav_Select"');?>>Events</a></li>
  <? } if($loginsession[44] == 1){ ?>
  <li><a href="#" title="Event Contacts"<? if($pathing[1] == "Evnt_Cont")print(' class="Sub_Nav_Select"');?>>Event
      Contacts </a></li>
  <? } if($loginsession[45] == 1){ ?>
  <li><a href="#" title="Event Locations"<? if($pathing[1] == "Evnt_Loc")print(' class="Sub_Nav_Select"');?>>Event
      Locations </a></li>
  <? } if($loginsession[46] == 1){ ?>
  <li><a href="#" title="Guestbook"<? if($pathing[1] == "Quest")print(' class="Sub_Nav_Select"');?>>Guestbook</a></li>
  <? } if($loginsession[47] == 1){ ?>
  <li><a href="#" title="Links"<? if($pathing[1] == "Link")print(' class="Sub_Nav_Select"');?>>Links</a></li>
  <? } if($loginsession[48] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,News" title="News"<? if($pathing[1] == "News")print(' class="Sub_Nav_Select"');?>>News</a></li>
  <? } if($loginsession[55] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Letter" title="Newsletter"<? if($pathing[1] == "Letter")print(' class="Sub_Nav_Select"');?>>Newsletter</a></li>
  <? } if($loginsession[54] == 1){ ?>
  <li><a href="<? echo $_SERVER['PHP_SELF']; ?>?Path=Web,Stats" title="Stats"<? if($pathing[1] == "Stats")print(' class="Sub_Nav_Select"');?>>Stats</a></li>
  <? } ?>
</ul>
<ul id="Main_Nav">
  <? } } if($loginsession[50] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','CustForm,Form','query','','');"<?php if($path[0] == "CustForm")print(' class="Main_Nav_Select"');?>>Custom
      Forms</a></li>
  <? if($path[0] == "CustForm"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;<? if($path[0] != "CustForm")print(" display: none;");?>">
  <? if($loginsession[51] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','CustForm,Form','query','','');" title="Custome Forms"<?php if($path[1] == "Form")print(' class="Sub_Nav_Select"');?>>Forms </a></li>
  <? } /* if($loginsession[52] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','CustForm,Table','query','','');" title="Custome Forms"<?php if($path[1] == "Table")print(' class="Sub_Nav_Select"');?>>Form
      Tables </a></li>
  <? } */ ?>
</ul>
<ul id="Main_Nav">
  <? } } ?>
  <li><a href="#" onclick="javascript:set_form('','Admin,User','query','','');"<? if($path[0] == "Admin")print(' class="Main_Nav_Select"');?>>Adminisration </a></li>
  <? if($path[0] == "Admin"){ ?>
</ul>
<ul id="Sub_Nav" style="margin:0px;">
  <li><a href="#" onclick="javascript:set_form('','Admin,User','query','','');" title="User Settings"<? if($path[1] == "User")print(' class="Sub_Nav_Select"');?>>&nbsp;Users </a> </li>
  <? if($loginsession[53] == 1){ ?>
  <li><a href="#" onclick="javascript:set_form('','Admin,Emp','query','','');" title="Employees"<? if($path[1] == "Emp")print(' class="Sub_Nav_Select"');?>>&nbsp;Employees</a></li>
  <? } } ?>
</ul>
