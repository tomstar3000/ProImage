// JavaScript Document
var xmlHttp = null;
var XMLFold = 'xml'; // XML Folder
var XMLSave = XMLFold+'/event_info.php'; // Were to go to find the folders
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
 
    var _getType = function( inp ) {
        var type = typeof inp, match;
        var key;
        if (type == 'object' && !inp) {
            return 'null';
        }
        if (type == "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            if (match = cons.match(/(\w+)\(/)) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';
    
    switch (type) {
        case "function": 
            val = ""; 
            break;
        case "undefined":
            val = "N";
            break;
        case "boolean":
            val = "b:" + (mixed_value ? "1" : "0");
            break;
        case "number":
            val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
            break;
        case "string":
            val = "s:" + mixed_value.length + ":\"" + mixed_value + "\"";
            break;
        case "array":
        case "object":
            val = "a";
            /*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            */
            var count = 0;
            var vals = "";
            var okey;
            var key;
            for (key in mixed_value) {
							var ktype = _getType(mixed_value[key]);
							//alert(key + ' type is ' + ktype);
							if (ktype != "function" && ktype != "object") {
								okey = (key.match(/^[0-9]+$/) ? parseInt(key) : key);
								vals += serialize(okey) +
									serialize(mixed_value[key]);
								count++;
							}
            }
            val += ":" + count + ":{" + vals + "}";
            break;
    }
    if (type != "object" && type != "array") val += ";";
    return val;
}
function Save_Evnt_Shp_Opt(ID){ // Load Folders
	var data = new Array();
	data['ID'] = ID;
	data['toLab'] = ((document.getElementById('SendLab').checked)?document.getElementById('SendLab').value:'n');
	data['pickUp'] = ((document.getElementById('PickUp').checked)?document.getElementById('PickUp').value:'n');
	data['shipTo'] = ((document.getElementById('ShipTo').checked)?document.getElementById('ShipTo').value:'n');
	Org_Initiate(); if(xmlHttp != null){  var url = XMLSave+"?data="+escape(serialize(data));
		xmlHttp.open('get', url); xmlHttp.onreadystatechange = Saved_Evnt_Shp_Opt; xmlHttp.send(''); } }
function Saved_Evnt_Shp_Opt(){ // Start building Folders
	if (xmlHttp.readyState==4){ var xmlDoc=xmlHttp.responseXML.documentElement; // Get XML information
		//alert(xmlHttp.responseText);
	} }

function PhotoInfo(){
	var HTML = '<h1>Add / Edit Photographer Information</h1>'
					+'<form method="post" action="/PhotoCP/includes/save_pop_photographer.php" name="PhotographerForm" id="PhotographerForm" enctype="multipart/form-data" target="HiddenForm">'
					+'<label for="First_Name" class="CstmFrmElmntLabel">Name</label>'
					
					+'<input type="text" name="First Name" id="First_Name" value="" maxlength="75" onfocus="javascript:this.className=\'CstmFrmElmntInputNavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInput\';" onmouseover="window.status=\'First Name\'; return true;" onmouseout="window.status=\'\'; return true;" title="First Name" class="CstmFrmElmntInput" />'
      		+'<input type="text" name="Last Name" id="Last_Name" value="" maxlength="75" onfocus="javascript:this.className=\'CstmFrmElmntInputNavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInput\';" onmouseover="window.status=\'Last Name\'; return true;" onmouseout="window.status=\'\'; return true;" title="Last Name" class="CstmFrmElmntInput" /><br clear="all" />'
					
					+'<label for="Address" class="CstmFrmElmntLabel">Address</label>'
      		+'<input name="Address" type="text" id="Address" value="" maxlength="125" onfocus="javascript:this.className=\'CstmFrmElmntInputNavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInput\';" onmouseover="window.status=\'Address\'; return true;" onmouseout="window.status=\'\'; return true;" title="Address" class="CstmFrmElmntInput" />'
      		+'<strong class="CstmFrmElmntStrong">Suite/Apt</strong>'
      		+'<input type="text" name="Suite Apt" id="Suite_Apt" value="" maxlength="25" size="10" onfocus="javascript:this.className=\'CstmFrmElmntInputi34NavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInputi34\';" onmouseover="window.status=\'Suite/Apt\'; return true;" onmouseout="window.status=\'\'; return true;" title="Suite/Apt" class="CstmFrmElmntInputi34" /><br clear="all" />'
					
					+'<label for="Address_2" class="CstmFrmElmntLabel">Address Second Line</label>'
     			+'<input type="text" name="Address 2" id="Address_2" value="" maxlength="125" onfocus="javascript:this.className=\'CstmFrmElmntInputNavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInput\';" onmouseover="window.status=\'Address Second Line\'; return true;" onmouseout="window.status=\'\'; return true;" title="Address Second Line" class="CstmFrmElmntInput" /><br clear="all" />'
			
					+'<label for="City" class="CstmFrmElmntLabel">City, State, Zip</label>'
					+'<input name="City" type="text" id="City" value="" onfocus="javascript:this.className=\'CstmFrmElmntInputNavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInput\';" onmouseover="window.status=\'City\'; return true;" onmouseout="window.status=\'\'; return true;" title="City" class="CstmFrmElmntInput" />'
      		+'<span style="float:left; clear:none; margin-right:5px;" id="State_Box"></span>'
      		+'<input name="Zip" type="text" id="Zip" value="" onfocus="javascript:this.className=\'CstmFrmElmntInputi117NavSel\';" onblur="javascript:this.className=\'CstmFrmElmntInputi117\';" onmouseover="window.status=\'Zip\'; return true;" onmouseout="window.status=\'\'; return true;" title="Zip" class="CstmFrmElmntInputi117" /><br clear="all" />'
					
					+'<label for="Country" class="CstmFrmElmntLabel">Country</label>'
					+'<select name="Country" id="Country" onchange="javascript:AEV_GetState(document.getElementById(\'Country\').value,false,false,\'\');" class="CstmFrmElmnt" onmouseover="window.status=\'Country\'; return true;" onmouseout="window.status=\'\'; return true;" title="Country">'
        	+'<option value="0" title="Select Country"> -- Select Country -- </option>'
      		+'</select><br clear="all" />'
			
					//+'<div id="BtnImgSbmt" onclick="javascript:document.getElementById(\'InvoiceImageUpdaterForm\').submit();"><input type="submit" name="Submit" id="Submit" value="Submit" /></div>'
					+'<input type="submit" name="Submit" id="Submit" value="Submit" />'
					+'<input type="hidden" name="Controller" id="Controller" value="true" />'
					+'</form>'
	send_Msg(HTML,true,null,null);
	Custom.setElem(document.getElementById('Country'));
	AEV_GetState('USA','CO','','');
}