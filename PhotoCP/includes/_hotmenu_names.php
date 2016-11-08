<?

function HotMenuVars($Btn) {
    $Btn = explode(":", $Btn);
    switch ($Btn[0]) {
        case "Evnt,Evnt":
            if ($Btn[1] == "add")
                return array("Create New Event", "Create a New Event", $Btn[0], $Btn[1]);
            if ($Btn[1] == "query")
                return array("Event Manager", "Event Manager", $Btn[0], $Btn[1]);
            break;
        case "Evnt,Mrkt":
        case "Busn,Mrkt":
            if ($Btn[1] == "query")
                return array("Event Marketing", "Event Marketing", $Btn[0], $Btn[1]);
            break;
        case "Evnt,Note":
        case "Busn,Note":
            if ($Btn[1] == "query")
                return array("Event Notifications", "Event Notifications", $Btn[0], $Btn[1]);
            break;
        case "Clnt,Clnt":
            if ($Btn[1] == "query")
                return array("Customer List", "Customer List", $Btn[0], $Btn[1]);
            break;
        case "Clnt,Search":
            if ($Btn[1] == "query")
                return array("Customer Search", "Search Customer List", $Btn[0], $Btn[1]);
            break;
        case "Clnt,Guest":
            if ($Btn[1] == "query")
                return array("Guest Book", "Guest Book", $Btn[0], $Btn[1]);
            break;
        case "Clnt,Board":
            if ($Btn[1] == "query")
                return array("Message Board", "Message Board", $Btn[0], $Btn[1]);
            break;
        case "Clnt,SrchSvd":
            if ($Btn[1] == "query")
                return array("Saved Searches", "Saved Searches", $Btn[0], $Btn[1]);
            break;
        case "Busn,Open":
            if ($Btn[1] == "query")
                return array("Open Orders", "Open Orders", $Btn[0], $Btn[1]);
            break;
        case "Busn,All":
            if ($Btn[1] == "query")
                return array("All Orders", "All Orders", $Btn[0], $Btn[1]);
            break;
        case "Busn,Busn":
            if ($Btn[1] == "query")
                return array("Commission Reports", "Commission Reports", $Btn[0], $Btn[1]);
            break;
        case "Busn,Disc":
            if ($Btn[1] == "query")
                return array("Custom Discount Codes", "Custom Discount Codes", $Btn[0], $Btn[1]);
            break;
        case "Busn,Gift":
            if ($Btn[1] == "query")
                return array("Gift Certificates", "Gift Certificates", $Btn[0], $Btn[1]);
            break;
        case "Busn,Pre":
            if ($Btn[1] == "query")
                return array("Preset Discount Codes", "Preset Discount Codes", $Btn[0], $Btn[1]);
            break;
        case "Busn,Prcn":
            if ($Btn[1] == "query")
                return array("Products and Pricing", "Products and Pricing", $Btn[0], $Btn[1]);
            break;
        case "Busn,Mrch":
            if ($Btn[1] == "add")
                return array("Process New Card", "Precess New Credit Cards", $Btn[0], $Btn[1]);
            if ($Btn[1] == "query")
                return array("Pending Credit Cards", "Pending Credit Card Transactions", $Btn[0], $Btn[1]);
            break;
        case "Busn,Past":
            if ($Btn[1] == "query")
                return array("Past Credit Cards", "Past Credit Card Transactions", $Btn[0], $Btn[1]);
            break;
        case "Photo,Photo":
            if ($Btn[1] == "query")
                return array("Photographer List", "Photographer List", $Btn[0], $Btn[1]);
            break;
        case "Pers,Info":
            if ($Btn[1] == "view")
                return array("Personal Information", "Personal Information", $Btn[0], $Btn[1]);
            break;
        case "Pers,Bill":
            if ($Btn[1] == "view")
                return array("Billing Information", "Billing Information", $Btn[0], $Btn[1]);
            break;
        case "Pers,Renew":
            if ($Btn[1] == "view")
                return array("Renew Account", "Renew Account", $Btn[0], $Btn[1]);
            break;
        case "Web,Home":
            if ($Btn[1] == "query")
                return array("Home Page", "Home Page", $Btn[0], $Btn[1]);
            break;
        case "Web,Bio":
            if ($Btn[1] == "query")
                return array("Bio Page", "Bio Page", $Btn[0], $Btn[1]);
            break;
        case "Web,Ftr":
            if ($Btn[1] == "query")
                return array("Footer Page", "Footer Page", $Btn[0], $Btn[1]);
            break;
        default: return array();
            break;
    }
}

?>