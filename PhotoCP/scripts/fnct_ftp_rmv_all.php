<?
function ftp_rmAll($conn_id,$path){
	if (!(@ftp_rmdir($conn_id, $path) || @ftp_delete($conn_id, $path))){
	 $list = ftp_nlist($conn_id, $path);
	 if (!empty($list)){
		 foreach($list as $value){
			 ftp_rmAll($conn_id, $value);
			}
		}
	}
 @ftp_rmdir($conn_id, $path);
}
?>