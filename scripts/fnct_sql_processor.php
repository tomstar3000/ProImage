<?
class sql_processor {
	var $cp_connection = "";
	var $database = "";
	var $gateway = "";
	var $query_info = "";
	var $info = "";
	var $row_info = "";
	var $total_info  = 0;
	var $fields_info = "";
	var $fields = array();
	var $results = array();
	function sql_processor($DATABASE,$CONNECT,$GATEWAY){
		$this->database = $DATABASE;
		$this->cp_connection = $CONNECT;
		$this->gateway = $GATEWAY;
		if($this->gateway == "MySQL"){
			mysql_select_db($this->database, $this->cp_connection);
		} else if($this->gateway == "MsSQL"){
			mssql_select_db($this->database, $this->cp_connection);
		}
	}
	function myError($msg){
		$count = count(explode("/",substr($_SERVER['PHP_SELF'], 1)))-1; $r_path = ""; for($n=0;$n<$count;$n++) $r_path .= "../";
		require_once($r_path.'scripts/fnct_send_email.php');
		
		$FormEmail = explode(",",$getFormInfo[0]['form_email']);
		$msg = mb_convert_encoding($msg,"UTF-8","HTML-ENTITIES");
		
		$server = $_SERVER['HTTP_HOST'];
		if(strpos("http:",strtolower($_SERVER['HTTP_HOST'])) != false) $server = substr($_SERVER['HTTP_HOST'],6);
		if(strpos("https:",strtolower($_SERVER['HTTP_HOST'])) != false) $server = substr($_SERVER['HTTP_HOST'],7);
		if(strpos("www",strtolower($_SERVER['HTTP_HOST'])) != false) $server = substr($_SERVER['HTTP_HOST'],4);
		
		$mail = new PHPMailer();
		//$mail -> IsSMTP();
		$mail -> Host = "smtp.".$server;
		$mail -> IsHTML(false);
		$mail -> IsSendMail();
		$mail -> Sender = "info@".$server;
		$mail -> Hostname = $server;
		$mail -> From = "development@proimagesoftware.com";
		$mail -> FromName = "development@proimagesoftware.com";
		$mail -> AddAddress("development@proimagesoftware.com");
		$mail -> Subject = "PHP Error: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
		$mail -> Body = $msg;
		$mail -> Send();
		
		die("We have experienced an internal error, please try again later. If this error persistes please contact us.");
	}
	function TotalRows(){
		return $this->total_info;
	}
	function FieldRows(){
		return $this->fields;
	}
	function Rows(){
		 return $this->results;
	}
	function mysql($STRING){
		if($this->gateway == "MySQL"){
			$this->query_info = $STRING;
			$this->fields = array();
			$this->results = array();
			$this->total_info = 0;
			$this->info = mysql_query($this->query_info, $this->cp_connection) or $this->myError(mysql_error()."\n\r In MySQL statement: ".$STRING);
			@$this->total_info = mysql_num_rows($this->info);
			if($this->total_info > 0){
				while ($this->fields_info = mysql_fetch_field($this->info)) {
					array_push($this->fields,$this->fields_info->name);
				}
				while($this->row_info = mysql_fetch_assoc($this->info)){
					$CNT = count($this->results);
					foreach($this->fields as $v){
						$this->results[$CNT][$v] = trim($this->row_info[$v]);
					}
				}
				mysql_free_result($this->info);
			}
		} else {
			return;
		}
	}
	function mssql($STRING){
		if($this->gateway == "MsSQL"){
			$this->fields = array();
			$this->results = array();
			$this->total_info = 0;
			$this->query_info = $STRING;
			$this->info = mssql_query($this->query_info, $this->cp_connection) or $this->myError(mssql_get_last_message()."\n\r In MSSQL statement: ".$STRING);
			@$this->total_info = mssql_num_rows($this->info);
			if($this->total_info > 0){
				while ($this->fields_info = mssql_fetch_field($this->info)) {
					array_push($this->fields,$this->fields_info->name);
				}
				while($this->row_info = mssql_fetch_assoc($this->info)){
					$CNT = count($this->results);
					foreach($this->fields as $v){
						$this->results[$CNT][$v] = trim($this->row_info[$v]);
					}
				}
				mssql_free_result($this->info);
			}
		} else {
			return;
		}
	}
	function Date($DATE){
		$year = intval(substr($DATE, 0, 4));
		$month = intval(substr($DATE, 5, 2));
		$day = intval(substr($DATE, 8, 2));
		$hour = intval(substr($DATE, 11, 2));
		$minute = intval(substr($DATE, 14, 2));
		$second = intval(substr($DATE, 17, 2));
		if($this->gateway == "MySQL"){
			if($DATE == ""|| $DATE == ''){
				return "'".$DATE."'";
			} else {
				return "'".$DATE."'";
			}
		} else if($this->gateway == "MsSQL"){
			if($DATE == "" || $DATE == ''){
				return "NULL";
			} else {
				return "'".date("m/d/Y h:i:s A", mktime($hour, $minute, $second, $month, $day, $year))."'";
			}
		}
	}
	function children($VAL, $TAB, $PRNT, $FLD, $CHLD = array()){
		$mysqlTS = "SELECT `".$PRNT."`, `".$FLD."` FROM `".$TAB."` WHERE `".$PRNT."` = '".$VAL."'";
		$mssqlTS = "SELECT ".$PRNT." ".$FLD." FROM ".$TAB." WHERE ".$PRNT." = '".$VAL."'";
		$this->mysql($mysqlTS);
		$this->mssql($mssqlTS);
		
		if($this->TotalRows() > 0){
			foreach($this->Rows() as $r){
				array_push($CHLD,$r[$FLD]);
				if($r[$PRNT] != 0)	$this->children($r[$FLD], $TAB, $PRNT, $FLD, $CHLD);
			}
		}
		return $CHLD;
	}
	function children_v2($VAL, $TAB, $PRNT, $FLD, $CHLD = array()){
		$mysqlTS = "SELECT `".$FLD."` FROM `".$TAB."` WHERE `".$PRNT."` = '".$VAL."'";
		$mssqlTS = "SELECT ".$FLD." FROM ".$TAB." WHERE ".$PRNT." = '".$VAL."'";
		$this->mysql($mysqlTS);
		$this->mssql($mssqlTS);
		
		if($this->TotalRows() > 0){
			foreach($this->Rows() as $r){
				$TId = $r[$FLD];
				array_push($CHLD,$TId);
				
				$mysqlTS = "SELECT COUNT(`".$FLD."`) AS `count` FROM `".$TAB."` WHERE `".$PRNT."` = '".$TId."'";
				$mssqlTS = "SELECT COUNT(".$FLD.") AS count FROM ".$TAB." WHERE ".$PRNT." = '".$TId."'";
				$this->mysql($mysqlTS);
				$this->mssql($mssqlTS);
				$Info = $this->Rows();		
				if($Info[0]['count'] != 0)find_children_v2($VAL, $TAB, $PRNT, $FLD, $CHLD);
			}
		}
		return $CHLD;
	}
	function parents($VAL, $TAB, $PRNT, $FLD, $PRNTS = array()){
		$mysqlTS = "SELECT `".$PRNT."` FROM `".$TAB."` WHERE `".$FLD."` = '".$VAL."'";
		$mssqlTS = "SELECT ".$PRNT." FROM ".$TAB." WHERE ".$FLD." = '".$VAL."'";
		$this->mysql($mysqlTS);
		$this->mssql($mssqlTS);
		$Info = $this->Rows();
		array_push($PRNTS,array($Info[0][$PRNT],$VAL));
		
		if(isset($Info[0][$PRNT]) && $Info[0][$PRNT] != 0 && $this->TotalRows() > 0)
			$PRNTS = $this->parents($Info[0][$PRNT], $TAB, $PRNT, $FLD,$PRNTS);
		
		return $PRNTS;
	}
	/*
	function find_parents_v2($VAL,  $TAB, $PRNT, $FLD){
		$query_get_parent = "SELECT `".$PRNT."` FROM `".$TAB."` WHERE `".$FLD."` = '$VAL'";
		$get_parent = mysql_query($query_get_parent, $cp_connection) or die(mysql_error());
		$row_get_parent = mysql_fetch_assoc($get_parent);
		$totalRows_get_parent = mysql_num_rows($get_parent);
		
		$CNT = count($PRNTS);
		
		$PRNTS[$CNT] = $row_get_parent[$PRNT];
		if($row_get_parent[$PRNT] != 0){
			find_parents_v2($row_get_parent[$PRNT], $TAB, $PRNT, $FLD);
		}
		mysql_free_result($get_parent);
	}
	*/
	function parents_v3($VAL, $TAB, $PRNT, $FLD, $PRNTS = array()){
		
		array_push($PRNTS, $VAL);
		
		$mysqlTS = "SELECT `".$PRNT."` FROM `".$TAB."` WHERE `".$FLD."` = '".$VAL."'";
		$mssqlTS = "SELECT ".$PRNT." FROM ".$TAB." WHERE ".$FLD." = '".$VAL."'";
		$this->mysql($mysqlTS);
		$this->mssql($mssqlTS);
		$Info = $this->Rows();
		
		if(isset($Info[0][$PRNT]) && $Info[0][$PRNT] != 0 && $this->TotalRows() > 0)
			$PRNTS = $this->parents_v3($Info[0][$PRNT], $TAB, $PRNT, $FLD, $PRNTS);

		return $PRNTS;
	}
	function sortItems($PRNTS, $IA, $NAME, $TAB, $ID, $PRNT, $offset){
		$PUA = array();
		$PA = array();
		$CUA = array();
		$NUA = array();
		$groups = array();
		$RCDS = array();
		
		foreach($PRNTS as $k => $v){
			$PUA[$k] = $PRNTS[$k][0];
			$CUA[$k] = $PRNTS[$k][1];
		}
		foreach($IA as $k => $v){
			$nk = array_keys($CUA, $v[1]);
			$key = $PUA[$nk[0]];
			$groups[$key][count($groups[$key])] = $v[1];
		}
		foreach($groups as $k => $v){
			foreach($v as $key => $val){
				$pt_array = array();
				$pt_array = $this->parents_v3($val,$TAB,$PRNT,$ID);
				$CNT = count($NUA);
				natcasesort($pt_array);
				$PA[$CNT] = $val;
				foreach($pt_array as $k => $v){
					$mysqlTS = "SELECT `$NAME` FROM `$TAB` WHERE `$ID` = '$v'";
					$mssqlTS = "SELECT $NAME FROM $TAB WHERE $ID = '$v'";
					$this->mysql($mysqlTS);
					$this->mssql($mssqlTS);
					$Info = $this->Rows();
					
					$NUA[$CNT] = ($NUA[$CNT] == "") ? $Info[0][$NAME] : $NUA[$CNT]." - ".$Info[0][$NAME];
				}
			}
		}
		$NA = array();
		$NA = $NUA;
		natcasesort($NA);
		$NA = array_unique($NA);
		foreach ($NA as $k => $v){
			$nk = array_keys($NUA, $v);
			$find = $PA[$nk[0]];
			foreach ($IA as $key => $val){
				if($val[1]==$find){
					$CNT = count($RCDS);
					$temp = $v;
					if($offset == true){
						$pos = strpos($temp, " - ");
						$temp = substr($temp, 0, $pos);
					}
					$RCDS[$CNT][0] = $temp;
					$RCDS[$CNT][1] = $val[0];
					$RCDS[$CNT][2] = $val[2];
				}
			}
		}
		return $RCDS;
	}
	function sortGroups($PRNTS , $IA, $NAME, $TAB, $ID){
		$PUA = array();
		$CUA = array();
		$NUA = array();
		
		foreach($PRNTS as $k => $v){
			$PUA[$key] = $PRNTS[$k][0];
			$CUA[$key] = $PRNTS[$k][1];
			
			$mysqlTS = "SELECT `$NAME` FROM `$TAB` WHERE `$ID` = '".$CUA[$k]."'";
			$mssqlTS = "SELECT $NAME FROM  $TAB WHERE	 $ID  = '".$CUA[$k]."'";
			$this->mysql($mysqlTS);
			$this->mssql($mssqlTS);
			$Info = $this->Rows();
			
			$NUA[$key] = $Info[0][$NAME];
		}
		$NA = array();
		$PA = array();
		$CA = array();
		$VA = array();
		$NA = $NUA;
		natcasesort($NA);
		foreach($NA as $k => $v){
			$nk = array_keys($NUA, $v);
			$CA[$key] = $CUA[$nk[0]];
			$PA[$key] = $PUA[$nk[0]];
			$ok = array_keys($IA[1], $CA[$k]);
			if($IA[0][$ok[0]] == ""){ $VA[$k][1] = 0;
			} else { $VA[$k][1] = $IA[0][$ok[0]]; }
			$VA[$k][0] = $v;
		}
		$RCDS = array();
		$CHLD = array_diff($CA, $PA);
		$PRNTS = array_unique(array_diff($CA, $CHLD));
		$TEMP = array();
		$n = 0;
		foreach($PRNTS as $k => $v){
			foreach($CA as $k2 => $v2){
				if($v == $v2){
					if($PA[$k2]!=0){
						$TEMP[$n] = array_shift($PRNTS);
						$n++; } 
				} } }
		$temp = array();
		foreach($CHLD as $k => $v){
			$nk = array_keys($CA, $v);
			$temp[$k] = $PA[$nk[0]];
		}
		$NUsed = array_diff($PRNTS, $temp);
		$th = array();
		$st = array();
		$n = 0;
		foreach($PRNTS as $k => $v){
			$add = true;	
			foreach($NUsed as $k2 => $v2){
				if($v == $v2){
					$add = false;
					break;
				}	}
			if($add){
				$nk = array_keys($CA, $v);
				$th[$n] = $VA[$nk[0]][0];
				$st[$n] = $v;
				$n++;
			}
			foreach($TEMP as $k2 => $v2){
				$nk = array_keys($CA, $v2);
				$ok = array_keys($CA, $v);
				$TST = $PA[$nk[0]];
				if($TST == $v){
					$th[$n] = $VA[$ok[0]][0]." - ".$VA[$nk[0]][0];
					$st[$n] = $v2;
					$n++;
				}	} }
		$n = 0;
		foreach($st as $k => $v){
			$nk = array_keys($PA, $v);
			foreach($CHLD as $k2 => $v2){
				$ok = array_keys($CA, $v2);
				if($PA[$ok[0]] == $PA[$nk[0]]){
					$RCDS[$n][0] = $th[$k];
					$RCDS[$n][1] = $VA[$ok[0]][1];
					$RCDS[$n][2] = $VA[$ok[0]][0];
					$n++;
				}
			}	}	return $RCDS;
	}
}
?>
