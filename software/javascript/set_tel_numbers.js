function set_tel_number(field,code_val){
	document.getElementById(field).value = document.getElementById(code_val+'1').value+document.getElementById(code_val+'2').value+document.getElementById(code_val+'3').value;
}
function move_tel_number(field, code_val){
	if(document.getElementById(field+code_val).value.length == 3 && code_val < 3){
		document.getElementById(field+(parseInt(code_val)+1)).focus();
	}
}