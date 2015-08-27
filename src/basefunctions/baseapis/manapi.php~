<?PHP
	require_once(BASE."basefunctions/baseapis/man_exception.class.php");
	
	function listDbData() {
		$link = mysqli_connect('localhost', 'root', '') or die(mysql_error());
		$db_list = mysql_list_dbs($link);
		
		print("<table width=100% bgcolor='BLACK' cellspacing=1 cellpadding=0>");
		print("<tr bgcolor=#BBBBBB><td align='center'>");
		print("<b>Database list</b>");
		print("</td></tr>");
		while ($row = mysqli_fetch_object($link, $db_list)) {
			print("<tr bgcolor='WHITE'><td align='center'>");
			print($row->Database);
			//echo $row->Database . "<br>";
			print("</td></tr>");
		}
		
		print("<tr bgcolor=#BBBBBB><td align='center'>");
		print("<b>Tables</b>");
		print("</td></tr>");
		
		$db_selected = mysqli_select_db('maxinedb', $link);
		
		$sql = "SHOW TABLES";
		$result = mysqli_query($link, $sql);
		
		if (!$result) {throw new man_exception('MySQL Error: '.mysql_error());}
		
		while ($row = mysqli_fetch_row($link, $result)) {
			print("<tr bgcolor='WHITE'><td align='center'>");
			print($row[0]."");
			print("</td></tr>");
		}
		print("</table>");
	}
	
	// SQL{
		function sqlPull($config) {
			$table = $config["table"];
			if(isset($config["sort"]) && $config["sort"]) {
				$sort	= $config["sort"];
			}
			
			if(isset($config["select"]) && $config["select"]) {
				$sql	= "SELECT ".$config["select"]." FROM ";
			} else {
				$sql	= "SELECT * FROM ";
			}
			
			$sql	.= $table;
			
			if(isset($config["where"]) && $config["where"]) {
				$sql	.= " WHERE ".$config["where"];
			}
			
			if(isset($sort) && $sort) {
				$sql	.= " ORDER BY ".$sort;
			}
			
			if(isset($config["group"]) && $config["group"]) {
				$sql	.= " GROUP BY ".$config["group"];
			}
			
			if(isset($config["limit"]) && $config["limit"]) {
				$sql	.= " LIMIT ".$config["limit"];
			}
			
			if(isset($config["customkey"]) && $config["customkey"]){
				$keycontrol	= $config["customkey"];
			} else {
				$keycontrol	= "id";
			}
			$count	= 0;
			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
			if ($resultset = mysqli_query($link, $sql)) {
				$resultlist = (array)array();
				while ($line = mysqli_fetch_array($resultset, MYSQLI_ASSOC)) {
					$key = 0;
					
					if($keycontrol == "count") {
						$key	= $count;
					} else if($line[$keycontrol]) {
						$key = $line[$keycontrol];
					} else if ($line["personid"]) {
						$key = $line["personid"];
					}
					$resultlist[$key] = $line;
					
					$count++;
				}
				
				if(isset($config["onerow"]) && ($config["onerow"] == 1)) {
					if (!isset($key)) {
						return FALSE;
					}
					$oneresult = $resultlist[$key];
					return $oneresult;
				} else {
					return $resultlist;
				}
			} else {
				throw new man_exception("Error pulling from SQL Database: ".mysqli_error($link)."\n");
			}
		}
		
		function sqlCreate($config) {
			$fieldlist = "";
			$fieldcount = 0;
			
			foreach ($config["fields"] as $fieldname=>$fieldval) {
				if($fieldcount > 0) {
					$fieldlist .= ", ";
				}
				$fieldval = htmlspecialchars($fieldval, ENT_QUOTES);
				$fieldlist .= $fieldname." = '".$fieldval."'";
				$fieldcount++;
			}
			
			$sql = "INSERT INTO ".$config["table"]." SET ".$fieldlist;
			//print($sql);
			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
			if (mysqli_query($link, $sql)) {
				$newid = mysqli_insert_id($link);
				return $newid;
			} else {
				throw new man_exception("Error inserting into SQL Database: ".mysql_error()."\n");
			}
		}
		
		function sqlCommit($config) {
			$fieldlist = "";
			$fieldcount = 0;
			foreach ($config["fields"] as $fieldname=>$fieldval) {
				if($fieldcount > 0) {
					$fieldlist .= ", ";
				}
				$fieldval = is_array($fieldval) ? htmlspecialchars(implode(' ', $fieldval), ENT_QUOTES) : htmlspecialchars($fieldval, ENT_QUOTES);
				$fieldlist .= $fieldname." = '".$fieldval."'";
				$fieldcount++;
			}
			//print($fieldlist."<br>");
			
			$sql = "UPDATE ".$config["table"]." SET ".$fieldlist." WHERE ".$config["where"];
			//print($sql);
			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
			if (mysqli_query($link, $sql)) {
				return 1;
			} else {
				// throw new man_exception("Error committing to SQL Database: ".mysqli_error($link)."\n");
			}
		}
		
		function sqlDelete($config) {
			$sql = "DELETE FROM ".$config["table"]." WHERE ".$config["where"];
			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
			if (mysqli_query($link, $sql)) {
				return 1;
			} else {
				throw new man_exception("Error deleting from SQL Database: ".mysqli_error($link)."\n");
			}
		}
		
		/** sqlQuery($query)
			* run an unbuffered SQL statement
			* @param $query string SQL query
			* @return array data on success false otherwise
			* @example sqlQuery('select kcu.* from information_schema.key_column_usage as kcu, information_schema.table_constraints as tc where tc.constraint_type="FOREIGN_KEY"')
		*/
		function sqlQuery($query) {
			$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
			if (($results = mysqli_query($link, $query)) === false) {
				return false;
			}
			$returnArray = (array)array();
			while ($line = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				$returnArray[] = $line;
			}
			return $returnArray;
		}
	// }
	
	function goHere($config,$search=0) {
		if($search!=0) {
			print("<form id='backform' action='".$config."' method='post'>");
			foreach ($search as $searchkey=>$searchval) {
				print("<input type='hidden' name=conf[search][".$searchkey."] value='".$searchval."'>");
			}
			print(".");
			print("</form>");
			
			print("<script>
			backform.submit();
			</script>");
		} else {
			// Jscript {
				print("<script>
				wantedLocation = '".$config."';
				window.location.href = wantedLocation;
				</script>");
			// }
		}
	}
	
	function splitString($text, $maxletters) {
		$textarray	= array();
		$wordarray	= explode(" ", $text);
		$wordcount	= count($wordarray);
		$linelength	= 0;
		$linecount	= 1;
		
		for($i =0; $i < $wordcount; $i++) {
			$wordlength							= strlen($wordarray[$i]); // The length of the curent word.
			if($wordlength != 0) { // Stripping out points at which double-space has been used.
				$linelength							= $linelength + $wordlength; // The length of the current sentence + the current word.
				if($linelength < $maxletters) { // If length is less than maximum letters, tack space and word on end.
					$textarray[$linecount]	.= " ".$wordarray[$i];
					$linelength++; // Add one to $linelength to account for the space.
				} else { // If length is greater than maximum letters, start new line with word as first text.
					$linecount++;
					$linelength							= $wordlength;
					$textarray[$linecount]	= $wordarray[$i];
				}
			}
		}
		return($textarray);
	}
	
	function phpPmt($r,$np,$pv,$fv) {
		$r = $r/1200;
		if (!$fv) $fv = 0;
		$pmt = -($r * ($fv+pow((1+$r),$np)*$pv)/(-1+pow((1+$r),$np)));
		$finalpmt=round($pmt,2);
		return $finalpmt;
	}
	
	function twoDArraySum($array,$index) {
		$total	= 0;
		foreach ($array as $arrkey=>$arrval) {
			$total	+= $arrval[$index];
		}
		return $total;
	}
	
	function unixDate($strdate) {
		if(strlen($strdate) > 1) {
			$tempday		= substr($strdate, 0, 2);
			$tempmonth	= substr($strdate, 3, 2);
			$tempyear		= substr($strdate, 6, 4);
			
			$unixdate		= mktime(0, 0, 0, $tempmonth, $tempday, $tempyear);
		} else {
			$unixdate	= 0;
		}
		
		return($unixdate);
	}
	
	function uniqueRand($n, $min = 0, $max = null) {
		if($max === null)
			$max = getrandmax();
		$array = range($min, $max);
		$return = array();
		$keys = array_rand($array, $n);
		foreach($keys as $key)
			$return[] = $array[$key];
		return $return;
	}
	
	//: Encoding and decoding text
	/** maxEncode($string)
	    * Encode a string
	    * @param string $string what do you need encoded
	    * @return string encoded text OR FALSE on failure
	    */
	function maxEncode($string)
	{
	    if (!is_string($string)) {return FALSE;}    ## Test for a string
	    if (function_exists("openssl_encrypt")) {
	        $file = (string)dirname(realpath(__FILE__)).DS.".key";
	        $stats = (array)stat($file);
	        if ($stats["size"] > 0) {
	            $key = file_get_contents($file);
	        } else {
	            $alnum = (string)"abcdefghijlkmnopqrstuvwxyz";
	            $alnum .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	            $alnum .= "0123456789";
	            $key = (string)"";
	            for ($i=0; $i<$iv_size;$i++) {
	                $key .= $alnum[mt_rand(0, strlen($alnum)-1)];
	            }
	            $fp = fopen($file, "a+");
	            fwrite($fp, $key);
	            fclose($fp);
	        }
	        return openssl_encrypt(str_rot13($string), "aes-128-cbc", $key);
	    } else {
	        return str_rot13($string);
	    }
	}
	
	/** maxDecode($string)
	    * Decode a string
	    * @param string $string what do you need decoded
	    * @return string decoded text OR FALSE on failure
	    */
	function maxDecode($string)
	{
	    if (!is_string($string)) {return FALSE;}    ## Test for a string
	    if (function_exists("openssl_decrypt")) {
	        $file = (string)dirname(realpath(__FILE__)).DS.".key";
	        $stats = (array)stat($file);
	        if ($stats["size"] > 0) {
	            $key = file_get_contents($file);
	        } else {
	            return FALSE;
	        }
	        return str_rot13(openssl_decrypt($string, "aes-128-cbc", $key));
	    } else {
	        return str_rot13($string);
	    }
	}
	//: End
	//: Security
	/** __sanitizeData(array $data)
	 * use filter_var to sanitize an array
	 * @param ARRAY $data an array of data to sanitize
	 * @return ARRAY $return
	 */
	function __sanitizeData(array $data) {
		$return = (array)array();
		foreach ($data as $key=>$value) {
			$return[filter_var($key, FILTER_SANITIZE_FULL_SPECIAL_CHARS)] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
		return $return;
	}
	//: End
?>