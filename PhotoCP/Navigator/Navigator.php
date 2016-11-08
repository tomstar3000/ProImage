<?
class Navigator{
	var $r_path = "";
	var $Folder = "";
	var $NavFolder = "Navigator";
	var $NavIndex = "includes/index.php";
	var $Style = "";
	var $Sendto = array();
	var $Uploader = false;
	
	function Navigator(){
		$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; for($n=0;$n<$count;$n++) $this->r_path .= "../";
		$this->Folder = $_SERVER['PHP_SELF'];
		$this->Folder = pathinfo($this->Folder);
		$this->Folder = $this->Folder['dirname'];
		$this->Sendto['NavFolder'] = $this->Folder.'/'.$this->NavFolder;
	}
	function setSize($Width = 0, $Height = 0){
		$Width = intval($Width); $Height = intval($Height);
		if($Width > 0 || $Height > 0){
			if($Width > 0) $this->Sendto['Width'] = $Width;
			if($Height > 0) $this->Sendto['Height'] = $Height;
			$this->Style = ' style="'.(($Width>0)?' width:'.$Width.'px;':'').(($Height>0)?' height:'.$Height.'px;':'').'"';
		}
	}
	function PassVar($TAG,$VAL,$ENC=false){ $this->Sendto[$TAG] = ($ENC==true)?$this->runencrypt($VAL):$VAL; }
	function exportHTML(){
		if($this->Uploader == true){
			$URL = $this->Folder.'/'.$this->NavFolder.'/includes/uploader.php?data='.urlencode(base64_encode(serialize($this->Sendto))).'&fid=0';
		} else {
			$URL = $this->Folder.'/'.$this->NavFolder.'/'.$this->NavIndex.'?data='.urlencode(base64_encode(serialize($this->Sendto)));
		}
		echo '<iframe id="Navigator" name="Navigator" src="'.$URL.'"'.$this->Style.' marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" allowtransparency="true" ></iframe>';	
	}
	function runencrypt($VAL,$DE=false){
		require_once 'scripts/encrypt.php';
		if($DE == true) return decrypt_data($VAL);
		else return encrypt_data($VAL);
	}
}
?>