<? function rmdirtree($dirname) {
	if (is_dir($dirname)) {
		if (substr($dirname,-1)!='/')$dirname.='/';    //Append slash if necessary
    $handle = opendir($dirname);
		while (false !== ($file = readdir($handle))) {
			if ($file!='.' && $file!= '..') {
				$path = $dirname.$file;
				if (is_dir($path)) {
					rmdirtree($path);
				} else {
					unlink($path);
				}
			}
		}
		closedir($handle);
		rmdir($dirname);
	}
}
?>