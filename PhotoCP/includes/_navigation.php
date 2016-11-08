<? if ($path[0] != "Prods") { ?>

    <div id="LeftPanel">
        <br clear="all" />
        <?
        require_once $r_path . 'includes/_hotmenu_names.php';
        if ($path[0] == "Evnt" && (!isset($path[2]) || $path[1] == 'Note')) {
            ?>
            <h1 id="HdrType1" class="EvntToolCntr">
                <div>Event Tool Center</div>
            </h1>
            <ul class="NavStyle1">
                <li id="BtnEvntNew"<? if ($path[1] == "Evnt" && $cont == "add") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Evnt,Evnt', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Create New Event';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Create New Event">
                        <div>Create New Event</div>
                    </a></li>
                <li id="BtnEvntMarket"<? if ($path[1] == "Mrkt") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Evnt,Mrkt', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Event Marketing';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Marketing">
                        <div>Event Marketing</div>
                    </a></li>
                <li id="BtnEvntEvntMrk"<? if ($path[1] == "Note") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Evnt,Note', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Event Notifications';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Notifications">
                        <div>Event Notifications</div>
                    </a></li>
                <li id="BtnEvntReRel"><a href="javascript:document.getElementById('Controller').value='ReRelease'; document.getElementById('form_action_form').submit();" onmouseover="window.status = 'Re-Release Events';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Re-Release Events" onclick="javascript:if (confirm('Are You Sure You Want To Re-Release These Events?'))
                            return true;
                        else
                            return false;">
                        <div>Re-Release Events</div>
                    </a></li>
                <li id="BtnEvntRemove" class="B"><a href="javascript:document.getElementById('Controller').value='Delete'; document.getElementById('form_action_form').submit();" onmouseover="window.status = 'Remove Events';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Remove Events" onclick="javascript:if (confirm('Are You Sure You Want To Remove These Events? If you select to remove event this event will no longer be available for re-release and will delete all images from the server. Reports will remain for event.'))
                            return true;
                        else
                            return false;">
                        <div>Remove Events</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } else if ($path[0] == "Evnt" && ($path[1] == "Evnt" || $path[1] == "Mrkt") && isset($path[2])) { ?>
            <h1 id="HdrType1" class="EvntToolCntr">
                <div>Event Tool Center</div>
            </h1>
            <ul class="NavStyle1">
                <li id="BtnEvntOver"<? if ($path[1] == "Evnt" && count($path) < 4) echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Event Overview';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Overview">
                        <div>Overview</div>
                    </a></li>
                <li id="BtnImagesGroups"<? if ($path[3] == "ImgsGrps") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,ImgsGrps','view','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Images and Groups';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Images and Groups">
                        <div>Images &amp; Groups</div>
                    </a></li>
                <li id="BtnEvntPresnt"<? if ($path[3] == "Present") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,Present','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Watermark Options';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Watermark Options">
                        <div>Watermark Options</div>
                    </a></li>
                <li id="BtnEvntMarket"<? if ($path[3] == "Market" || $path[1] == "Mrkt") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,Market','edit','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Event Marketing';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Marketing">
                        <div>Event Marketing</div>
                    </a></li>
                <li id="BtnEvntMarket"<? if ($path[3] == "DiscCodes") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,DiscCodes','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Preset Discount Codes';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Preset Discount Codes">
                        <div>Preset Discount Codes</div>
                    </a></li>
                <li id="BtnEvntMarket"<? if ($path[3] == "GiftCerts") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,GiftCerts','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Gift Certificates';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Gift Certificates">
                        <div>Gift Certificates</div>
                    </a></li>
                <li id="BtnEvntMarket"<? if ($path[3] == "Coupns") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,Coupns','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Coupons';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Coupons">
                        <div>Coupons</div>
                    </a></li>
                <li id="BtnEvntGuest"<? if ($path[3] == "Guest") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,Guest','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Event Guest Book';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Guest Book">
                        <div>Guest Book</div>
                    </a></li>
                <li id="BtnEvntEvntMrk"<? if ($path[3] == "Note") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('', '<? echo implode(",", array_slice($path, 0, 3)); ?>,Note', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');" onmouseover="window.status = 'Event Notifications';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Notifications">
                        <div>Event Notifications</div>
                    </a></li>
                <li id="BtnEvntMsgBrd"<? if ($path[3] == "Board") echo ' class="TNavSel"'; ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,Board','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Message Board';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Message Board">
                        <div>Message Board</div>
                    </a></li>
                <!-- <li id="BtnEvntMusic"><a href="#" onmouseover="window.status='Event Music'; return true;" onmouseout="window.status=''; return true;" title="Event Music">
                  <div>Event Music</div>
                  </a></li> -->
                <li id="BtnEvntReprt"<?
                if ($path[3] == "Reprt")
                    echo ' class="BNavSel"';
                else
                    echo ' class="B"';
                ?>><a href="javascript:set_form('','<? echo implode(",", array_slice($path, 0, 3)); ?>,Reprt','query','<? echo $sort; ?>','<? echo $rcrd; ?>');" onmouseover="window.status = 'Event Report and Sales';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Report and Sales">
                        <div>Event Report</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } else if ($path[0] == "Clnt") { ?>
            <h1 id="HdrType1" class="ClntToolCntr">
                <div>Client Manager</div>
            </h1>
            <ul class="NavStyle2">
                <li id="BtnClntClientAdd"<? if ($path[1] == "Clnt" && $cont == "add") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Clnt,Clnt', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Create New Client';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Create New Client">
                        <div>Create New Client</div>
                    </a></li>
                <li id="BtnClntClientList"<? if ($path[1] == "Clnt" && $cont != "add") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Clnt,Clnt', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Client List';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Client List">
                        <div>Client List</div>
                    </a></li>
                <li id="BtnClntClientSrch"<? if ($path[1] == "Search") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Clnt,Search', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Search Clients';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Search Clients">
                        <div>Search Clients</div>
                    </a></li>
                <li id="BtnClntGuest"<? if ($path[1] == "Guest") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Clnt,Guest', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Guest Book';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Guest Book">
                        <div>Guest Book</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } else if ($path[0] == "Busn" || $path[0] == "Photo" || $path[0] == "Pers") { ?>
            <h1 id="HdrType1" class="BsnToolCntr">
                <div>Business Tools</div>
            </h1>
            <ul class="NavStyle3">
                <li id="BtnBsnOpenOrds"<? if ($path[1] == "Open") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Open', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Open Orders';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Open Orders">
                        <div>Open Orders</div>
                    </a></li>
                <li id="BtnBsnAllOrds"<? if ($path[1] == "All") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,All', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Open Orders';
                        return true;" onmouseout="window.status = '';
                        return true;" title="All Orders">
                        <div>All Orders</div>
                    </a></li>
                <li id="BtnBsnEvntRpts"<? if ($path[1] == "Busn") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Busn', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Commission Reports';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Commission Reports">
                        <div>Commission Reports</div>
                    </a></li>
                <li id="BtnBsnDscCods"<? if ($path[1] == "Disc") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Disc', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Custom Discount Codes';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Custom Discount Codes">
                        <div>Custom Discount Codes</div>
                    </a></li>
                <li id="BtnBsnDscCods"<? if ($path[1] == "Gift") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Gift', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Gift Certificates';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Gift Certificates">
                        <div>Gift Certificates</div>
                    </a></li>
                <li id="BtnBsnPstDsnCods"<? if ($path[1] == "Pre") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Pre', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Discount Codes';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Discount Codes">
                        <div>Preset Discount Codes</div>
                    </a></li>
                <li id="BtnBusnEvntMarket"<? if ($path[1] == "Mrkt") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Mrkt', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Event Marketing';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Marketing">
                        <div>Event Marketing</div>
                    </a></li>
                <li id="BtnBsnEvntMrk"<? if ($path[1] == "Note") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Note', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Event Notifications';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Event Notifications">
                        <div>Event Notifications</div>
                    </a></li>
                <li id="BtnBsnPrdPrcing"<? if ($path[1] == "Prcn") echo ' class="BNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Prcn', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Products and Pricing';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Products and Pricing">
                        <div>Products and Pricing</div>
                    </a></li> 
                <li id="BtnBsnMerchant"<? if ($path[1] == "Mrch") echo ' class="BNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Busn,Mrch', 'add', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Process Credit Cards';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Process Credit Cards">
                        <div>Process Credit Cards</div>
                    </a></li>
                <li id="BtnBsnPhotoList"<? if ($path[0] == "Photo") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Photo,Photo', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Photographer List';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Photographer List">
                        <div>Photographer List</div>
                    </a></li>
                <li id="BtnBsnAcntInfo"<?
                if ($path[0] == "Pers")
                    echo ' class="BNavSel"';
                else
                    echo ' class="B"';
                ?>><a href="#" onclick="javascript:set_form('', 'Pers,Info', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Account Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Account Information">
                        <div>Account Information</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } else if ($path[0] == "Web") { ?>
            <h1 id="HdrType1" class="WebToolCntr">
                <div>Website Manager</div>
            </h1>
            <ul class="NavStyle4">
                <li id="BtnWebHome"<? if ($path[1] == "Home") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Web,Home', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Home Page Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Home Page Information">
                        <div>Home Page</div>
                    </a></li>
                <li id="BtnWebBio"<? if ($path[1] == "Bio") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Web,Bio', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Bio Page Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Bio Page Information">
                        <div>Bio Page</div>
                    </a></li>
                <li id="BtnWebFoot"<? if ($path[1] == "Ftr") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Web,Ftr', 'query', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Footer Page Information';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Footer Page Information">
                        <div>Footer Page</div>
                    </a></li>
                <li id="BtnWebHome" class="B"><a href="/<? echo $CHandle; ?>" onmouseover="window.status = 'View Home Page';
                        return true;" onmouseout="window.status = '';
                        return true;" title="View Home Page" target="_blank">
                        <div>View Home Page</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } /* else if($path[0] == "Pers"){ ?>
          <h1 id="HdrType1" class="BsnToolCntr">
          <div>Account Manager</div>
          </h1>
          <ul class="NavStyle3">
          <li id="BtnAccntPersn"<? if($path[1] == "Info")echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('','Pers,Info','view','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false" onmouseover="window.status='Edit Your Personal Information'; return true;" onmouseout="window.status=''; return true;" title="Edit Your Personal Information">
          <div>Personal Information</div>
          </a></li>
          <li id="BtnAccntBill"<? if($path[1] == "Bill")echo ' class="NavSel"'; ?>><a href="#" onclick="javascript:set_form('','Pers,Bill','view','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false" onmouseover="window.status='Edit Your Billing Information'; return true;" onmouseout="window.status=''; return true;" title="Edit Your Billing Information">
          <div>Billing Information</div>
          </a></li>
          <li id="BtnAccntRenew"<? if($path[1] == "Renew")echo ' class="BNavSel"'; else echo ' class="B"'; ?>><a href="#" onclick="javascript:set_form('','Pers,Renew','view','<? echo $sort; ?>','<? echo $rcrd; ?>'); return false" onmouseover="window.status='Renew Your Account'; return true;" onmouseout="window.status=''; return true;" title="Renew Your Account">
          <div>Renew Your Account</div>
          </a></li>
          </ul>
          <div id="NavBottom"></div>
          <br clear="all" />
          <? } */ else if ($path[0] == "Comm") { ?>
            <h1 id="HdrType1" class="EvntToolCntr">
                <div>Community</div>
            </h1>
            <ul class="NavStyle1">
                <li id="BtnEvntNew"<? if ($path[1] == "Update") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Comm,Update', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Pro Image Update List';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Pro Image Update List">
                        <div>Pro
                            Image Update List</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "Help") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Comm,Help', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'General Help';
                        return true;" onmouseout="window.status = '';
                        return true;" title="General Help">
                        <div>General Help</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "DiscHelp") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Comm,DiscHelp', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Discount Help';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Discount Help">
                        <div>Discount Help</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "GuestHelp") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Comm,GuestHelp', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Guestbook Help';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Guestbook Help">
                        <div>Guestbook Help</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "ProdHelp") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Comm,ProdHelp', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Products Help';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Products Help">
                        <div>Products Help</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "WebHelp") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Comm,WebHelp', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Website Help';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Website Help">
                        <div>Website Help</div>
                    </a></li>
                <li id="BtnEvntNew" class="B"><a href="#" onclick="javascript: AEV_new_window('/Bulletin_Board_3', 'Builletin_Board');
                        return false;" onmouseover="window.status = 'Pro Image Forum';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Pro Image Forum">
                        <div>Pro Image Forum</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } else if ($path[0] == "Contact") { ?>
            <h1 id="HdrType1" class="EvntToolCntr">
                <div>Support / Contact</div>
            </h1>
            <ul class="NavStyle1">
                <li id="BtnEvntNew"<? if ($path[1] == "Comment") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Contact,Comment', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Comments / Contact';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Comments / Contact">
                        <div>Comments / Contact</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "Report") echo ' class="TNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Contact,Report', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Report Issues';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Report Issues">
                        <div>Report Issues</div>
                    </a></li>
                <li id="BtnEvntNew"<? if ($path[1] == "Request") echo ' class="BNavSel"'; ?>><a href="#" onclick="javascript:set_form('', 'Contact,Request', 'view', '<? echo $sort; ?>', '<? echo $rcrd; ?>');
                        return false" onmouseover="window.status = 'Request a Feature';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Request a Feature">
                        <div>Request Feature</div>
                    </a></li>
                <li id="BtnEvntNew" class="B"><a href="#" onclick="javascript: AEV_new_window('/Bulletin_Board_3', 'Builletin_Board');
                        return false;" onmouseover="window.status = 'Pro Image Forum';
                        return true;" onmouseout="window.status = '';
                        return true;" title="Pro Image Forum">
                        <div>Pro Image Forum</div>
                    </a></li>
            </ul>
            <div id="NavBottom"></div>
            <br clear="all" />
        <? } ?>
        <h1 id="HdrType1" class="HotMenu">
            <div>Hot Menu</div>
        </h1>
        <ul <?
        switch ($path[0]) {
            case "Clnt": echo 'class="NavStyle2B"';
                break;
            case "Busn": case "Pers": case "Photo": echo 'class="NavStyle3B"';
                break;
            case "Web": echo 'class="NavStyle4B"';
                break;
        }
        ?> id="NavHotMenu">
            <?
            $getInfo = new sql_processor($database_cp_connection, $cp_connection, $gateways_cp_connection);
            $getInfo->mysql("SELECT * FROM `photo_hotmenu` WHERE `cust_id` = '$CustId';");
            $StrArray = $getInfo->Rows();
            if ($getInfo->TotalRows() > 0) {
                $StrArray = unserialize(rawurldecode($StrArray[0]['hot_menu']));
                if (count($StrArray) > 0) {
                    foreach ($StrArray as $r) {
                        $Btn = HotMenuVars($r);
                        $Act = explode(",", $Btn[2]);
                        $Check = false;
                        if (count($path) > 1 && $cont == $Btn[3]) {
                            if ($path[0] == $Act[0] && $path[1] == $Act[1])
                                $Check = true;
                        } else if ($cont == $Btn[3]) {
                            if ($path[0] == $Act[0])
                                $Check = true;
                        }
                        echo '<li' . (($Check) ? ' class="TNavSel"' : '') . '><a href="#" onclick="javascript:set_form(\'\',\'' . $Btn[2] . '\',\'' . $Btn[3] . '\',\'\',\'\'); return false" onmouseover="window.status=\'' . $Btn[1] . '\'; return true;" onmouseout="window.status=\'\'; return true;" title="' . $Btn[1] . '"><div>' . $Btn[0] . '</div></a></li>';
                    }
                }
                else
                    echo '<li id="HotNon">Your Hot Menu is empty</li>';
            }
            else
                echo '<li id="HotNon">Your Hot Menu is empty</li>';
            ?>
        </ul>
        <div id="NavBottom"></div>
        <br clear="all" />
    <? } else { ?>
        <div style="display:none;">
        <? } ?>
        <form action="<? echo $_SERVER['PHP_SELF'] . '?' . $QueryString . $token; ?>" name="action_form" id="action_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="path" id="path" value="<? echo implode(",", $path); ?>" />
            <input type="hidden" name="cont" id="cont" value="<? echo $cont; ?>" />
            <input type="hidden" name="sort" id="sort" value="<? echo $sort; ?>" />
            <input type="hidden" name="rcrd" id="rcrd" value="<? echo $rcrd; ?>" />
        </form>
    </div>
