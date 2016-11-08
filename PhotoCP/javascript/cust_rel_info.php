<? $count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-2; $r_path = ""; for($n=0;$n<$count;$n++)$r_path .= "../";
define("PhotoExpress Pro", true);
define('Allow Scripts',true);
include $r_path.'security.php';
require_once($r_path.'../Connections/cp_connection.php');
require_once($r_path.'includes/get_user_information.php');
ob_start(); ?>
<script type="text/javascript">
<? ob_end_clean(); ?>
// JavaScript Document
var xmlHttp = null;
var XMLFold = 'xml'; // XML Folder
var IncFold = 'includes'; // XML Folder
var XMLClntList = XMLFold+'/client_list.php'; // Were to go to save Event Information
var XMLClntInfo = XMLFold+'/client_list.php'; // Were to go to save Event Information
var PrssGraph = '<p align="center"><img src="/PhotoCP/images/Processing.gif" alt="Processing" width="147" height="105" vspace="50" /> </p>';
function Org_Initiate(){ // Initiate the xmlHTTP gateway
	xmlHttp=GetXmlHttpObject(); // Create a new Object
	if (xmlHttp==null) { alert ("Your browser does not support AJAX!"); return; } // Alert people that their browswer doesn't support Ajax
}
function GetXmlHttpObject(){ // Set up our gateway
	try { Gateway=new ActiveXObject("Microsoft.XMLHTTP"); Gateway.async="false"; } // Internet Explorer
	catch (e) { try{ Gateway = new XMLHttpRequest(); } // Firefox, Opera 8.0+, Safari
							catch(e){ Gateway=new ActiveXObject('MSXML2.XMLHTTP.3.0');} } // Internet Explorer 5.5 and 6
	return Gateway; }
function serialize( mixed_value ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var _getType = function( inp ) { var type = typeof inp, match; var key;
			if (type == 'object' && !inp) { return 'null'; }
			if (type == "object") {
				if (!inp.constructor) { return 'object'; }
				var cons = inp.constructor.toString();
				if (match = cons.match(/(\w+)\(/)) { cons = match[1].toLowerCase(); }
				var types = ["boolean", "number", "string", "array"];
				for (key in types) { if (cons == types[key]) { type = types[key]; break; } }
			} return type;
    }; var type = _getType(mixed_value); var val, ktype = '';
    switch (type) { case "function": val = ""; break;
			case "undefined": val = "N"; break;
			case "boolean": val = "b:" + (mixed_value ? "1" : "0"); break;
			case "number": val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value; break;
			case "string": val = "s:" + mixed_value.length + ":\"" + mixed_value + "\""; break;
			case "array":
			case "object": val = "a";
				/* if (type == "object") { var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
						if (objname == undefined) { return; }
						objname[1] = serialize(objname[1]); val = "O" + objname[1].substring(1, objname[1].length - 1); } */
				var count = 0; var vals = ""; var okey; var key;
				for (key in mixed_value) { var ktype = _getType(mixed_value[key]);
					//alert(key + ' type is ' + ktype);
					if (ktype != "function" && ktype != "object") { okey = (key.match(/^[0-9]+$/) ? parseInt(key) : key);
						vals += serialize(okey) + serialize(mixed_value[key]); count++;
					} } val += ":" + count + ":{" + vals + "}"; break;
    } if (type != "object" && type != "array") val += ";"; return val; }	
// *****************************************************************************************************
// ***                                         Client List                                           ***
// *****************************************************************************************************
function ClntList(){ send_Msg(PrssGraph,true,null,null); Org_Initiate(); if(xmlHttp != null){ var url = XMLClntList+"?user=<? echo $CustId; ?>&act=1";
		xmlHttp.open('get', url); xmlHttp.onreadystatechange = ClntListLd; xmlHttp.send(''); } }
function ClntListLd(){ if (xmlHttp.readyState==4){ var HTML = '<h1>Client List</h1></span><br clear="all" /><div style="height:250px; overflow:auto;">';
		var xmlDoc=xmlHttp.responseXML.documentElement; // Get XML information
		for(var n = 0; n<xmlDoc.childNodes.length; n++){ var Node = xmlDoc.childNodes[n]; // For Each XML Node
			if(Node.nodeType==1) { HTML += '<p>'
      				+ '	<input type="radio" name="id" id="id" title="Select '+Node.getAttribute("name")+'" onmouseover="window.status=\'Select '+Node.getAttribute("name")+'\'; return true;" onmouseout="window.status=\'\'; return true;" value="'+Node.getAttribute("type")+'.'+Node.getAttribute("id")+'" onclick="javascript: ClntListSel(\''+Node.getAttribute("type")+'.'+Node.getAttribute("id")+'\')" />'+Node.getAttribute("name")
							+ ' - '+Node.getAttribute("email")+' - '+Node.getAttribute("city")+' '+Node.getAttribute("state")+' '+Node.getAttribute("zip")
      				+ '	<br clear="all" />'
    					+ '</p>'; } }
			HTML += '</div>'; send_Msg(HTML,true,null,null); } }
function ClntListSel(ID){ send_Msg(PrssGraph,true,null,null); document.getElementById('ClientID').value = ID;
	Org_Initiate(); if(xmlHttp != null){ var url = XMLClntList+"?user=<? echo $CustId; ?>&act=2&id="+ID;
		xmlHttp.open('get', url); xmlHttp.onreadystatechange = ClntInfoLd; xmlHttp.send(''); }
}
function ClntInfoLd(){ if (xmlHttp.readyState==4){ var xmlDoc=xmlHttp.responseXML.documentElement;
	send_Msg('',false,null,null);
	for(var n = 0; n<xmlDoc.childNodes.length; n++){ var Node = xmlDoc.childNodes[n]; // For Each XML Node
			if(Node.nodeType==1) { document.getElementById('First_Name').value=Node.getAttribute("fname");
			document.getElementById('Middle_Initial').value =	Node.getAttribute("mint"); document.getElementById('Last_Name').value = Node.getAttribute("lname");
			document.getElementById('Address').value = Node.getAttribute("add"); document.getElementById('Suite_Apt').value = Node.getAttribute("suite");
			document.getElementById('Address_2').value = Node.getAttribute("add2"); document.getElementById('City').value = Node.getAttribute("city");
			document.getElementById('State').value = Node.getAttribute("state"); document.getElementById('Zip').value = Node.getAttribute("zip");
			document.getElementById('Country').value = Node.getAttribute("country"); document.getElementById('Country').onchange();
			document.getElementById('P1').value	= Node.getAttribute("p1"); document.getElementById('P2').value	= Node.getAttribute("p2");
			document.getElementById('P3').value	= Node.getAttribute("p3"); document.getElementById('Email').value	= Node.getAttribute("email");
	} } } }
<? ob_start(); ?>
</script>
<? ob_end_clean(); ?>