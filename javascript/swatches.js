var mouseX, mouseY;
function getMousePos(e){
	tabheight = 16;
	if (!e) var e = window.event || window.Event;
	
	if('undefined'!=typeof e.pageX){
		Xpos = e.pageX; Ypos = e.pageY;
	} else {
		if(document.documentElement.scrollTop){
			Xpos = e.clientX + document.documentElement.scrollLeft;
			Ypos = e.clientY + document.documentElement.scrollTop;			
		} else {
			Xpos = e.clientX + document.body.scrollLeft;
			Ypos = e.clientY + document.body.scrollTop;
		}
	}
	testheight = Number.NaN;
	document.getElementById("Bubble_left_2").style.height = "auto";
	if (document.getElementById) {
		var checkbubble = document.getElementById("Bubble_text");
		if (checkbubble && typeof checkbubble.offsetHeight != 'undefined') {
			testheight = checkbubble.offsetHeight;
		}
	}
	Xpos += 10;
	Ypos = Ypos-(tabheight/2)-5;
	
	var browserName=navigator.appName;
	document.getElementById("Bubble_left").style.height = tabheight+"px";
	document.getElementById("Bubble_left_2").style.height = (testheight-tabheight)+"px";
	
	if(document.getElementById("Bubble").style.visibility == 'visible'){
		document.getElementById("Bubble").style.top = Ypos+"px";
		document.getElementById("Bubble").style.left = Xpos+"px";
	} else {
		document.getElementById("Bubble").style.top = "0px";
		document.getElementById("Bubble").style.left = "0px";
	}
}

if(window.Event && document.captureEvents)
 	document.captureEvents(Event.MOUSEMOVE);
	document.onmousemove = getMousePos;
	
function popBubble(image,imagename){
	document.getElementById("Bubble").style.visibility = 'visible';
	if(image != "none"){
		document.getElementById("Bubble_text").innerHTML = "<img src=\"/images/attributes/"+image+"\" align=\"left\" hspace=\"5\">"+imagename;
	} else {
		document.getElementById("Bubble_text").innerHTML = imagename;	
	}
}
function hideBubble(){
	document.getElementById("Bubble").style.visibility = 'hidden';
}

function swatch_click(swatch_id,field_id){
	n = 1;
	while(document.getElementById("swatch_"+field_id+"_"+n)){
		if(document.getElementById("swatch_"+field_id+"_"+n).id == swatch_id){
			document.getElementById("swatch_"+field_id+"_"+n).className = "swatchborder";
			document.getElementById("spec_"+field_id).value = document.getElementById("swatch_id_"+field_id+"_"+n).value;
		} else {
			document.getElementById("swatch_"+field_id+"_"+n).className = "swatchnoborder";
		}
		n++;
	}
}
function select_click(selvalue, field_id){
	document.getElementById("spec_"+field_id).value = selvalue;
}