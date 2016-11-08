<h1><? echo $CName; ?></h1>
<p>Account Due Date: <? echo format_date($DueDate,'Short',false,true,false); ?></p>
<p>Sign Up Date: <? echo format_date($SignUpDate,'Short',false,true,false); ?></p>
<p>Status:
 <? if($UsrPaid == 'y') echo "Paid"; else echo "Payment Due"; ?>
</p>
<? if($UsrPaid == 'n'){ ?>
<p style="color:#FF0000">Warning: Our records show that you missed a payment, and your account is currently suspended!</p>
<? } ?>
<p id="Tap_Link"><a href="#" onmouseover="window.status='Renew'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Renew,Info','view','','');">Renew</a></p>
<h1>Resource Usage </h1>
<div id="BG_Quota">
 <div id="Quota" style="width:<? echo $PercUsed*182;?>px"></div>
</div>
<p><? echo round(($MBUsed/1024)*100)/100; ?>GB / <? echo round(($Quota/1024)*100)/100; ?>GB</p>
<!-- 
<p id="Tap_Link"><a href="#">Get More</a></p>
-->
<h1>Control Panel</h1>
<ul id="Main_Nav">
 <? if($UsrPaid == 'y'){ ?>
 <li><a href="#"<? if($path[0]=="Events")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Events'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Events,Events','query','','');">Event
   Manager</a></li>
 <? if($path[0]=="Events"){ ?>
</ul>
<ul id="Sub_Nav">
 <li><a href="#"<? if($path[1]=="Events")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Orders'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Events,Events','query','','');">Active
   Events</a></li>
 <li><a href="#"<? if($path[1]=="Expired")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='All Orders'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Events,Expired','query','','');">Expired
   Events</a></li>
 <li><a href="#"<? if($path[1]=="Notes")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Event Notifications'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Events,Notes','query','','');">Event
   Notifications</a></li>
</ul>
<ul id="Main_Nav">
 <? } } ?>
 <li><a href="#"<? if($path[0]=="Disc")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Discount Codes'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Disc,Disc','query','','');">Discount
   Codes</a></li>
 <? if($path[0]=="Disc"){ ?>
</ul>
<ul id="Sub_Nav">
 <li><a href="#"<? if($path[1]=="Disc")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Discount Codes'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Disc,Disc','query','','');">Discount
   Codes</a></li>
 <li><a href="#"<? if($path[1]=="Preset")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Preset Discounts'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Disc,Preset','query','','');">Preset
   Discount Codes</a></li>
</ul>
<ul id="Main_Nav">
 <? } ?>
 <li><a href="#"<? if($path[0]=="Orders")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Orders'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Orders,Open','query','','');">Orders</a></li>
 <? if($path[0]=="Orders"){ ?>
</ul>
<ul id="Sub_Nav">
 <li><a href="#"<? if($path[1]=="Open")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Orders'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Orders,Open','query','','');">Open
   Orders </a></li>
 <li><a href="#"<? if($path[1]=="All")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='All Orders'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Orders,All','query','','');">All
   Orders </a></li>
</ul>
<ul id="Main_Nav">
 <? } ?>
 <li><a href="#"<? if($path[0]=="Customers")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Clients (Customers)'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Customers,All','query','','');">Clients
   (Customers)</a></li>
 <li><a href="#"<? if($path[0]=="Guest")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Guestbook Manager'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Guest,All','query','','');">Guestbook
   Manager</a></li>
 <? if($path[0]=="Guest"){ ?>
</ul>
<ul id="Sub_Nav">
 <li><a href="#"<? if($path[1]=="All")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Guestbook Manager'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Guest,All','query','','');">Guestbook
   Manager</a></li>
 <li><a href="#"<? if($path[1]=="Board")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Message Board'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Guest,Board','query','','');">Message
   Boards</a></li>
</ul>
<ul id="Main_Nav">
 <? } ?>
 <li><a href="#"<? if($path[0]=="Price")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Products and pricing'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Price,All','query','','');">Products &amp; Pricing</a></li>
 <li><a href="#" onmouseover="window.status='Webiste Admin Tool'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Report,Comm,<? echo $CustId; ?>','query','','');">Reports</a></li>
 <li><a href="#"<? if($path[0]=="Web")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Website Manager'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Web,Home','edit','','');">Website
   Manager</a></li>
 <? if($path[0]=="Web"){ ?>
</ul>
<ul id="Sub_Nav">
 <li><a href="#"<? if($path[1]=="Home")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Home Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Web,Home','edit','','');">Home
   Page</a></li>
 <li><a href="#"<? if($path[1]=="Bio")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Bio Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Web,Bio','edit','','');">Bio
   Page</a></li>
 <li><a href="#"<? if($path[1]=="Ftr")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Footer Information'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Web,Ftr','edit','','');">Footer
   Information</a></li>
</ul>
<ul id="Main_Nav">
 <? } ?>
 <li><a href="#"<? if($path[0]=="Per")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Personal Information'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Pers,Info','view','','');">Personal
   Information </a></li>
 <li><a href="#"<? if($path[0]=="Bill")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Billing Information'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Bill,Info','view','','');">Billing
   Information </a></li>
 <li><a href="/Bulletin_Board_3" title="Bulletin Board" target="_blank" onmouseover="window.status='Bulletin Board'; return true;" onmouseout="window.status=''; return true;">Forum</a></li>
 <li><a href="#"<? if($path[0]=="Help")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Help'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Help,Help','view','','');">Help
   Instructions</a></li>
 <? if($path[0]=="Help"){ ?>
</ul>
<ul id="Sub_Nav">
 <li><a href="#"<? if($path[1]=="Help")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Home Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Help,Help','view','','');">Event
   Manager </a></li>
 <li><a href="#"<? if($path[1]=="Disc")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Bio Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Help,Disc','view','','');">Discount
   Codes </a></li>
 <li><a href="#"<? if($path[1]=="Gues")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Bio Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Help,Gues','view','','');">Guestbook
   Manager </a></li>
 <li><a href="#"<? if($path[1]=="Prod")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Bio Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Help,Prod','view','','');">Products
  &amp; Pricing </a></li>
 <li><a href="#"<? if($path[1]=="Web")echo ' class="Sub_Nav_Select"'; ?> onmouseover="window.status='Bio Page'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Help,Web','view','','');">Website
   Manager </a></li>
</ul>
<ul id="Main_Nav">
 <? } ?>
 <li><a href="#"<? if($path[0]=="Update")echo ' class="Main_Nav_Select"'; ?> onmouseover="window.status='Pro Image Update List'; return true;" onmouseout="window.status=''; return true;" onclick="javascript:set_form('','Update,Update','view','','');">Pro
   Image Update List</a></li>
</ul>
