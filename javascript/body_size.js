function checkDiv(checkedagain){
	if (typeof(window.innerHeight) == 'number') {
		win_height = window.innerHeight;
	}	else {
		if (document.documentElement && document.documentElement.clientHeight) {
			win_height = document.documentElement.clientHeight;
		}
		else {
			if (document.body && document.body.clientHeight) {
				win_height = document.body.clientHeight;
			}
		}
	}
	if (document.all) {
		doc_height = document.body.offsetHeight;
	} else if (document.layers) {
		doc_height = document.body.document.height;
	} else {
		doc_height = document.body.offsetHeight;
	}
	if(doc_height>win_height){
		document.body.style.height = "auto";
		document.getElementById('Log_in_Container').style.height = "auto";
		if(!checkedagain){
			checkDiv(true);
		}
	} else {
		document.body.style.height = win_height+"px";
		if(document.getElementById('Log_in_Container')){
			document.getElementById('Log_in_Container').style.height = win_height+"px";
		}
		//document.getElementById('Content').style.height = win_height-496+"px";
		//document.getElementById('Text').style.height = win_height-533+"px";
		if(!checkedagain){
			checkDiv(true);
		}
	}
	if(document.getElementById('Content')){
		document.getElementById('Content').style.height = doc_height-document.getElementById('Header').offsetHeight+"px";
		document.getElementById('Nav').style.height = document.getElementById('Content').offsetHeight-document.getElementById('Bread_Crumbs').offsetHeight-3+"px";
		document.getElementById('Content_Container').style.height = document.getElementById('Content').offsetHeight-document.getElementById('Bread_Crumbs').offsetHeight-5+"px";
		document.getElementById('Action_Div').style.height = document.getElementById('Content_Container').offsetHeight-document.getElementById('Section_Header').offsetHeight+"px";
	}
}