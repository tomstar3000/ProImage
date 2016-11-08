//<![CDATA[	
var imageUploader1 = null;
var uniqueId = 0;
var prevUploadFileCount = 0;
var dragAndDropEnabled = true;
var allowDrag = false;

function fullPageLoad(){
	imageUploader1 = getImageUploader("ImageUploader1");
	// Quata Information
	document.getElementById("spanMaxFileCount").innerHTML = imageUploader1.getMaxFileCount();
	document.getElementById("spanMaxTotalFileSize").innerHTML = formatFileSize(parseInt(imageUploader1.getMaxTotalFileSize()));
}

function processDragDrop(){
	alert("Adding files with drag & drop can not be implemented in standard version due security reasons. However it can be enabled in private-label version."+
		"\r\n\r\nFor more information please contact us at sales@aurigma.com");
	if (imageUploader1){
		//imageUploader1.AddToUploadList();
	}
}

//To identify items in upload list, GUID are used. However it would work 
//too slow if we use GUIDs directly. To increase performance, we will use 
//hash table which will map the guid to the index in upload list. 

//This function builds and returns the hash table which will be used for
//fast item search.
function getGuidIndexHash(){
	var uploadFileCount = imageUploader1.getUploadFileCount();
	var guidIndexHash = new Object();
	for (var i = 1; i <= uploadFileCount; i++){
		guidIndexHash[imageUploader1.getUploadFileGuid(i)] = i;
	}
	return guidIndexHash;
}

function selectView_change(){
	if ($au.uploader('ImageUploader1')){
		var selectView = document.getElementById("selectView").value;
		$au.uploader('ImageUploader1').folderPane().viewMode( selectView );
	}
}
function ImageUploader_ViewChange(){
	if ($au.uploader('ImageUploader1')){
		setTimeout("Timer_ViewChange()", 500);
	}
}
function Timer_ViewChange(){
	document.getElementById("selectView").value = $au.uploader('ImageUploader1').folderPane().viewMode();
}


//Synchronize custom upload pane with Image Uploader upload list when 
//some files are added or removed.
function ImageUploader_UploadFileCountChange(){
	if ($au.uploader('ImageUploader1')){
		var count = $au.uploader('ImageUploader1').files().count();

		// Quata Information
		document.getElementById("spanUploadFileCount").innerHTML = parseInt(count);
		//var imgWidth = parseInt(imageUploader1.getUploadFileCount()) / parseInt(imageUploader1.getMaxFileCount()) * 132;	
		//document.getElementById("imgUploadFileCount").style.width = Math.round(imgWidth) + "px";
		//document.getElementById("spanTotalFileSize").innerHTML = formatFileSize(parseInt(imageUploader1.getTotalFileSize()));
		//imgWidth = parseInt(imageUploader1.getTotalFileSize()) / parseInt(imageUploader1.getMaxTotalFileSize()) * 132;
		//document.getElementById("imgTotalFileSize").style.width = Math.round(imgWidth) + "px";
	}
}


//This function is used to handle Remove link click. It removes an item 
//from the custom upload pane by specified GUID.
function Remove_click(guid){
	var guidIndexHash = getGuidIndexHash();
	imageUploader1.UploadFileRemove(guidIndexHash[guid]);
}

//This function posts data on server.
function UploadButton_click(){
	imageUploader1.Send();
}
//]]>