package asFiles.ProImage{
	import flash.display.*;
	import flash.events.*;
	import flash.text.*;
	import fl.controls.ComboBox;
	import fl.controls.TextInput;
	public class ProImage extends MovieClip {
		private var $Elmnts:Array = new Array();// Array of elements to call the resize function on
		public var ENUM:String='';
		public var PHOTO:String='';
		public var EMAIL:String='';
		public var ROOTURL:String="/";
		public var GrpObj:Object;
		public var WDTH:Number=925;
		public var HGHT:Number=600;
		/* -------------------- File Stack --------------- */
		public var FLDxml:String="xml/";// XML Folder
		public var FLDflsh:String="flash/";// Flash Folder
		public var FLDimg:String="images/";// Image Folder
		public var XMLgrp:String="ProImage/get_groups.php";// Load groups for selected event
		public var XMLimgs:String="ProImage/get_images.php";// Load groups for selected event
		public var IMGfl:String="flash_image.php";// Flash Image file

		public var CheckOut="checkout.php";// Check Out Script
		public var SavCart="save_cart_cs3.php";
		public var SavFavs="save_favorites_cs3.php";
		public var SavFavsMsg="save_favorites_msg_cs3.php";
		public var GretTool="Borders_v3.swf";
		public function ProImage() {
			WDTH=stage.stageWidth;
			HGHT=stage.stageHeight;
			
			stage.align=StageAlign.TOP_LEFT;// Set our scale mode to the top left
			stage.scaleMode=StageScaleMode.NO_SCALE;// Set the flash piece not to scale
			stage.addEventListener(Event.RESIZE, hdlrResizeState);// Add our listener
			this.addChild(new Header(this));
			this.addChild(new Footer(this));
			this.addChild(new Images(this));

			ENUM=root.loaderInfo.parameters.code;// Get Event Code from Loaded Vars
			PHOTO=root.loaderInfo.parameters.photographer;// Get Photographer from Loaded Vars
			EMAIL=root.loaderInfo.parameters.email;// Get Email from loaded Vars
			ROOTURL="/";// Root Directores

			/* -------------------- Comment Out On Publish --------------- */
			//ROOTURL = "http://testing.proimagesoftware.com/"; // Testing Server
			ROOTURL="http://www.proimagesoftware.com/";// Live Server
			ENUM="adlfinger";
			PHOTO="ryanserpan";
			PHOTO="cpmphoto";
			EMAIL="development@proimagesoftware.com";
			/* -------------------- Comment Out On Publish --------------- */
			FLDxml=ROOTURL+FLDxml;
			FLDflsh=ROOTURL+FLDflsh;
			FLDimg=ROOTURL+FLDimg;

			GrpObj=new Groups(this);
			GrpObj.go();
		}
		public function get StageHeight() {
			return stage.stageHeight;
		}
		public function get StageWidth() {
			return stage.stageWidth;
		}
		public function getChild($VAL:String) {
			for (var $n=0; $n<this.$Elmnts.length; $n++) {
				if (this.$Elmnts[$n].name==$VAL) {
					return this.$Elmnts[$n];
					break;
				}
			}
			return null;
		}
		public function setCombo($ELEM:ComboBox) {
			//$ELEM.setStyle("textPadding",0);// Set the style of the ComboBox elements.
			//$ELEM.textField.setStyle("textFormat", new TextFormat("ArialMT", 10));// Set the font to arial that we we can see the ComboBox behind a mask
			//$ELEM.textField.setStyle("embedFonts", true);// Embed the fonts
		}
		/* -------------------- Add elements to an Array so we can work with them dynamically --------------- */
		public function addElement($MC:Object) {
			this.$Elmnts.push($MC);// Push a movie clip to our array for stage resizing
		}
		private function hdlrResizeState($E:Event) {
			WDTH=stage.stageWidth;
			HGHT=stage.stageHeight;
			for (var $n=0; $n<$Elmnts.length; $n++) {
				this.$Elmnts[$n].Resize(WDTH,HGHT);
			}
		}
	}
}