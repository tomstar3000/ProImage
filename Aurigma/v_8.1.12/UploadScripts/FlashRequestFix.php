<?php

// sys_get_temp_dir function exists in PHP 5 >= 5.2.1
if ( !function_exists('sys_get_temp_dir')) {
  function sys_get_temp_dir() {
    if ($temp = ini_get('upload_tmp_dir')) {
      return $temp;
    }
    if ($temp = getenv('TMP')) {
      return $temp;
    }
    if ($temp = getenv('TEMP')) {
      return $temp;
    }
    if ($temp = getenv('TMPDIR')){
      return $temp;
    }
    $temp = tempnam(dirname(__FILE__), '');
    if (file_exists($temp)) {
      unlink($temp);
      return dirname($temp);
    }
    return null;
  }
}

/**
 * Manual add received files into $_FILES array
 */
function preProcessRequest(){
  // possible file fields
  $fileFields = array(
  array("File0_0", "File0Name_0"),
  array("File1_0", "File1Name_0"),
  array("File2_0", "File2Name_0")
  );

  foreach ($fileFields as $fileField) {
    // field exist in request array
    if (array_key_exists($fileField[0], $_REQUEST) && isset($_REQUEST[$fileField[0]])){

      // save field content to temp file
      $tmp_name = tempnam(sys_get_temp_dir(), 'fiu');
      $handle = fopen($tmp_name, "w");
      fwrite($handle, $_REQUEST[$fileField[0]]);
      fclose($handle);

      // add temp file to file array
      $_FILES[$fileField[0]] = array(
			'name'=>$_REQUEST[$fileField[1]],
			'manual_remove'=>true,
			'tmp_name'=>$tmp_name,
			'error'=>0,
			'size'=>filesize($tmp_name),
			'type'=>'application/octet-stream'
			);

    }
  }
}

/**
 * Use this function insteand of move_uploaded_file to save uploaded files.
 * Otherwise files from flash uploader wouldn't be saved.
 */
function moveFile($source, $destination){
  // common way for uploaded files
  if (is_uploaded_file($source)){
    $result = move_uploaded_file($source, $destination);
  } else {
    // try to move with rename function
    $result=@rename($source, $destination);

    if (!$result){
      // copy-remove otherwise
      $result = copy($source, $destination);
      unlink($source);
    }
  }
  return $result;
}

/**
 * Delete temp files
 */
function postProcessRequest(){
  //clean up
  foreach ($_FILES as $file) {
    if (isset($file['manual_remove']) && file_exists($file['tmp_name'])){
      // remove file
      unlink($file['tmp_name']);
    }
  }
}