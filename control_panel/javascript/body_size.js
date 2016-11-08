function checkDiv(checkedagain){
	if (typeof(window.innerHeight) == 'number') {
		win_height = window.innerHeight;
		win_width = window.innerWidth;
	}	else {
		if (document.documentElement && document.documentElement.clientHeight) {
			win_height = document.documentElement.clientHeight;
			win_width = document.documentElement.clientWidth;
		}	else {
			if (document.body && document.body.clientHeight) {
				win_height = document.body.clientHeight;
				win_width = document.body.clientWidth;
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
	var limit = 1200;
	var differance = 201;
	if(win_width<1401 && win_width > limit){
		document.getElementById('Header').style.width = win_width+"px";
		document.getElementById('Content').style.width = win_width-differance+"px";
	} else if (win_width <= limit){
		document.getElementById('Header').style.width = limit+"px";
		document.getElementById('Content').style.width = limit-differance+"px";
	} else {
		document.getElementById('Header').style.width = 1401+"px";
		document.getElementById('Content').style.width = 1401-differance+"px";
	}

  var table = document.getElementsByTagName( 'table' )[ 0 ];

  //adjust font size for firefox users
  if( navigator.userAgent.toLowerCase().indexOf('firefox') > -1 && table.style.fontSize != '9px' ){
    table.style.fontSize = '9px';
  }

	if(!checkedagain){
		checkDiv(true);
	}
}