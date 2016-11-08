/*

CUSTOM FORM ELEMENTS

Created by Ryan Fait
www.ryanfait.com

The only thing you need to change in this file is the following
variables: checkboxHeight, radioHeight and selectWidth.

Replace the first two numbers with the height of the checkbox and
radio button. The actual height of both the checkbox and radio
images should be 4 times the height of these two variables. The
selectWidth value should be the width of your select list image.

You may need to adjust your images a bit if there is a slight
vertical movement during the different stages of the button
activation.

Visit http://ryanfait.com/ for more information.

*/

var checkboxHeight = "19";
var radioHeight = "19";
var selectWidth = "175";

var radioHeight2 = "27";

var selectWidth88 = "88";
var selectWidth53 = "53";
var selectWidth64 = "64";
var selectWidth117 = "117";

/* No need to change anything after this */

document.write('<style type="text/css"  media="screen">input.CstmFrmElmnt { display: none; } select.CstmFrmElmnt { position: relative; width: ' + selectWidth + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; margin-bottom: 7px; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmntAM { display: none; } select.CstmFrmElmntAM { position: relative; width: ' + selectWidth + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmntPM { display: none; } select.CstmFrmElmntPM { position: relative; width: ' + selectWidth + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmntPvt { display: none; } select.CstmFrmElmntPvt { position: relative; width: ' + radioHeight2 + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmntPub { display: none; } select.CstmFrmElmntPub { position: relative; width: ' + radioHeight2 + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmnt117 { display: none; } select.CstmFrmElmnt117 { position: relative; width: ' + selectWidth117 + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmnt88 { display: none; } select.CstmFrmElmnt88 { position: relative; width: ' + selectWidth88 + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmnt64 { display: none; } select.CstmFrmElmnt64 { position: relative; width: ' + selectWidth64 + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

document.write('<style type="text/css"  media="screen">input.CstmFrmElmnt53 { display: none; } select.CstmFrmElmnt53 { position: relative; width: ' + selectWidth53 + 'px; opacity: 0; filter: alpha(opacity=0); z-index: 5; }</style>');

var Custom = {
	init: function() { 
		var inputs = document.getElementsByTagName("input"), span = Array(), textnode, option, active;
		for(a = 0; a < inputs.length; a++) {
			if((inputs[a].type == "checkbox" || inputs[a].type == "radio") && (inputs[a].className == "CstmFrmElmnt" || inputs[a].className == "CstmFrmElmntAM" || inputs[a].className == "CstmFrmElmntPM" || inputs[a].className == "CstmFrmElmntPvt" || inputs[a].className == "CstmFrmElmntPub")) {
				span[a] = document.createElement("span");
				if(inputs[a].className == "CstmFrmElmntAM") span[a].className = inputs[a].type+"AM";
				else if(inputs[a].className == "CstmFrmElmntPM") span[a].className = inputs[a].type+"PM";
				else if(inputs[a].className == "CstmFrmElmntPvt") span[a].className = inputs[a].type+"Pvt";
				else if(inputs[a].className == "CstmFrmElmntPub") span[a].className = inputs[a].type+"Pub";
				else span[a].className = inputs[a].type;
				
				if(inputs[a].checked == true) {
					if(inputs[a].type == "checkbox") {
						position = "0 -" + (checkboxHeight*2) + "px";
						span[a].style.backgroundPosition = position;
					} else {
						if(inputs[a].className == "CstmFrmElmntPvt" || inputs[a].className == "CstmFrmElmntPub") position = "0 -" + (radioHeight2*2) + "px";
						else position = "0 -" + (radioHeight*2) + "px";
						span[a].style.backgroundPosition = position;
					}
				}
				
				span[a].id = "select" + inputs[a].name;
				inputs[a].parentNode.insertBefore(span[a], inputs[a]);
				if(inputs[a].onchange != null) span[a].onchange = inputs[a].onchange;
				if(inputs[a].onclick != null) span[a].onclick = inputs[a].onclick;
				if(inputs[a].onblur != null) span[a].onblur = inputs[a].onblur;
				if(inputs[a].onmouseover != null) span[a].onmouseover = inputs[a].onmouseover;
				if(inputs[a].onmouseout != null) span[a].onmouseout = inputs[a].onmouseout;
				if(inputs[a].title != null) span[a].title = inputs[a].title;
				
				inputs[a].onchange = Custom.clear;
				span[a].onmousedown = Custom.pushed;
				span[a].onmouseup = Custom.check;
			}
		}
		inputs = document.getElementsByTagName("select");
		for(a = 0; a < inputs.length; a++) {
			if(inputs[a].className == "CstmFrmElmnt" || inputs[a].className == "CstmFrmElmnt117" || inputs[a].className == "CstmFrmElmnt88" || inputs[a].className == "CstmFrmElmnt64" || inputs[a].className == "CstmFrmElmnt53") {
				option = inputs[a].getElementsByTagName("option");
				active = option[0].childNodes[0].nodeValue;
				textnode = document.createTextNode(active);
				for(b = 0; b < option.length; b++) {
					if(option[b].selected == true) {
						textnode = document.createTextNode(option[b].childNodes[0].nodeValue);
					}
				}
				if(document.getElementById("select" + inputs[a].name)){
					try{inputs[a].parentNode.removeChild(document.getElementById("select" + inputs[a].name));}
					catch(err){ }
				}
				span[a] = document.createElement("span");
				if(inputs[a].className == "CstmFrmElmnt117") span[a].className = "select117";
				else if(inputs[a].className == "CstmFrmElmnt88") span[a].className = "select88";
				else if(inputs[a].className == "CstmFrmElmnt64") span[a].className = "select64";
				else if(inputs[a].className == "CstmFrmElmnt53") span[a].className = "select53";
				else span[a].className = "select";
				
				if(inputs[a].onchange != null) span[a].onchange = inputs[a].onchange;
				
				span[a].id = "select" + inputs[a].name;
				span[a].appendChild(textnode);
				inputs[a].parentNode.insertBefore(span[a], inputs[a]);
				inputs[a].onchange = Custom.choose;
			}
		}
	},
	setElem: function(Elem) {
		var span = Array(), textnode, option, active, a = document.getElementsByTagName("input").length;
		var a = document.getElementsByTagName("select").length;
		if((Elem.type == "checkbox" || Elem.type == "radio") && (Elem.className == "CstmFrmElmnt" || Elem.className == "CstmFrmElmntAM" || Elem.className == "CstmFrmElmntPM" || Elem.className == "CstmFrmElmntPvt" || Elem.className == "CstmFrmElmntPub")) {
			span[a] = document.createElement("span");
			if(Elem.className == "CstmFrmElmntAM") span[a].className = Elem.type+"AM";
			else if(Elem.className == "CstmFrmElmntPM") span[a].className = Elem.type+"PM";
			else if(Elem.className == "CstmFrmElmntPvt") span[a].className = Elem.type+"Pvt";
			else if(Elem.className == "CstmFrmElmntPub") span[a].className = Elem.type+"Pub";
			else span[a].className = Elem.type;
			
			if(Elem.checked == true) {
				if(Elem.type == "checkbox") {
					position = "0 -" + (checkboxHeight*2) + "px";
					span[a].style.backgroundPosition = position;
				} else {
					if(Elem.className == "CstmFrmElmntPvt" || Elem.className == "CstmFrmElmntPub") position = "0 -" + (radioHeight2*2) + "px";
					else position = "0 -" + (radioHeight*2) + "px";
					span[a].style.backgroundPosition = position;
				}
			}
			
			span[a].id = "select" + Elem.name;
			Elem.parentNode.insertBefore(span[a], Elem);
			if(Elem.onchange != null) span[a].onchange = Elem.onchange;
			if(Elem.onclick != null) span[a].onclick = Elem.onclick;
			if(Elem.onblur != null) span[a].onblur = Elem.onblur;
			if(Elem.onmouseover != null) span[a].onmouseover = Elem.onmouseover;
			if(Elem.onmouseout != null) span[a].onmouseout = Elem.onmouseout;
			if(Elem.title != null) span[a].title = Elem.title;
			
			Elem.onchange = Custom.clear;
			span[a].onmousedown = Custom.pushed;
			span[a].onmouseup = Custom.check;
		} else if((Elem.type == "select" || Elem.type == "select-one") && (Elem.className == "CstmFrmElmnt" || Elem.className == "CstmFrmElmnt117" || Elem.className == "CstmFrmElmnt88" || Elem.className == "CstmFrmElmnt64" || Elem.className == "CstmFrmElmnt53")) {
			option = Elem.getElementsByTagName("option");
			active = option[0].childNodes[0].nodeValue;
			textnode = document.createTextNode(active);
			for(b = 0; b < option.length; b++) {
				if(option[b].selected == true) {
					textnode = document.createTextNode(option[b].childNodes[0].nodeValue);
				}
			}
			span[a] = document.createElement("span");
			if(Elem.className == "CstmFrmElmnt117") span[a].className = "select117";
			else if(Elem.className == "CstmFrmElmnt88") span[a].className = "select88";
			else if(Elem.className == "CstmFrmElmnt64") span[a].className = "select64";
			else if(Elem.className == "CstmFrmElmnt53") span[a].className = "select53";
			else span[a].className = "select";
			
			if(Elem.onchange != null) span[a].onchange = Elem.onchange;
			
			span[a].id = "select" + Elem.name;
			span[a].appendChild(textnode);
			Elem.parentNode.insertBefore(span[a], Elem);
			Elem.onchange = Custom.choose;
		}
	},
	pushed: function() {
		document.onmouseup = Custom.clear;
		element = this.nextSibling;
		if(element.checked == true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 -" + checkboxHeight*3 + "px";
		} else if(element.checked == true && element.type == "radio") {
			if(element.className == "CstmFrmElmntPvt" || element.className == "CstmFrmElmntPub") this.style.backgroundPosition = "0 -" + radioHeight2*3 + "px";
			else this.style.backgroundPosition = "0 -" + radioHeight*3 + "px";
		} else if(element.checked != true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 -" + checkboxHeight + "px";
		} else {
			if(element.className == "CstmFrmElmntPvt" || element.className == "CstmFrmElmntPub") this.style.backgroundPosition = "0 -" + radioHeight2 + "px";
			else this.style.backgroundPosition = "0 -" + radioHeight + "px";
		}
		
	},
	check: function() {
		element = this.nextSibling;
		if(element.checked == true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 0";
			element.checked = false;
		} else {
			if(element.type == "checkbox") {
				this.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
			} else {
				if(element.className == "CstmFrmElmntPvt" || element.className == "CstmFrmElmntPub") this.style.backgroundPosition = "0 -" + radioHeight2*2 + "px";
				else this.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
				group = this.nextSibling.name;
				inputs = document.getElementsByTagName("input");
				for(a = 0; a < inputs.length; a++) {
					if(inputs[a].name == group && inputs[a] != this.nextSibling) {
						inputs[a].previousSibling.style.backgroundPosition = "0 0";
					}
				}
			}
			element.checked = true;
		}
		if(this.onchange != null) this.onchange();
	},
	clear: function() {
		document.onmouseup = '';
		inputs = document.getElementsByTagName("input");
		for(var b = 0; b < inputs.length; b++) {
			if(inputs[b].type == "checkbox" && inputs[b].checked == true && inputs[b].className == "CstmFrmElmnt") {
				inputs[b].previousSibling.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
			} else if(inputs[b].type == "checkbox" && inputs[b].className == "CstmFrmElmnt") {
				inputs[b].previousSibling.style.backgroundPosition = "0 0";
			} else if(inputs[b].type == "radio" && inputs[b].checked == true && (inputs[b].className == "CstmFrmElmnt" || inputs[b].className == "CstmFrmElmntAM" || inputs[b].className == "CstmFrmElmntPM")) {
				inputs[b].previousSibling.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
			} else if(inputs[b].type == "radio" && inputs[b].checked == true && (inputs[b].className == "CstmFrmElmntPvt" || inputs[b].className == "CstmFrmElmntPub")) {
				inputs[b].previousSibling.style.backgroundPosition = "0 -" + radioHeight2*2 + "px";
			} else if(inputs[b].type == "radio" && (inputs[b].className == "CstmFrmElmnt" || inputs[b].className == "CstmFrmElmntAM" || inputs[b].className == "CstmFrmElmntPM" || inputs[b].className == "CstmFrmElmntPvt" || inputs[b].className == "CstmFrmElmntPub")) {
				inputs[b].previousSibling.style.backgroundPosition = "0 0";
			}
		}
	},
	choose: function() {
		option = this.getElementsByTagName("option");
		for(d = 0; d < option.length; d++) { 
			if(option[d].selected == true) {
				document.getElementById("select" + this.name).childNodes[0].nodeValue = option[d].childNodes[0].nodeValue;
				if(document.getElementById("select" + this.name).onchange) document.getElementById("select" + this.name).onchange();
			}
		}
	}
}
window.onload = Custom.init;