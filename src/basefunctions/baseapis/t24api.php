<?PHP
	function listDbData() {
		$link = mysql_connect('localhost', 'root', '') or die(mysql_error());
		$db_list = mysql_list_dbs($link);
		
		print("<table width=100% bgcolor='BLACK' cellspacing=1 cellpadding=0>");
		print("<tr bgcolor=#BBBBBB><td align='center'>");
		print("<b>Database list</b>");
		print("</td></tr>");
		while ($row = mysql_fetch_object($db_list)) {
			print("<tr bgcolor='WHITE'><td align='center'>");
			print($row->Database);
			//echo $row->Database . "<br>";
			print("</td></tr>");
		}
		
		print("<tr bgcolor=#BBBBBB><td align='center'>");
		print("<b>Tables</b>");
		print("</td></tr>");
		
		$db_selected = mysql_select_db('t24db', $link);
		
		$sql = "SHOW TABLES";
		$result = mysql_query($sql);
		
		if (!$result) {
			echo "DB Error, could not list tables\n";
			echo 'MySQL Error: ' . mysql_error();
			exit;
		}
		
		while ($row = mysql_fetch_row($result)) {
			print("<tr bgcolor='WHITE'><td align='center'>");
			print($row[0]."");
			print("</td></tr>");
		}
		print("</table>");
	}
	
	// SQL{
		function sqlPull($config) {
			$table = $config["table"];
			if($config["sort"]) {
				$sort	= $config["sort"];
			}
			
			if($config["select"]) {
				$sql	= "SELECT ".$config["select"]." FROM ";
			} else {
				$sql	= "SELECT * FROM ";
			}
			
			$sql	.= $table;
			
			if($config["where"]) {
				$sql	.= " WHERE ".$config["where"];
			}
			
			if($sort) {
				$sql	.= " ORDER BY ".$sort;
			}
			
			if($config["group"]) {
				$sql	.= " GROUP BY ".$config["group"];
			}
			
			if($config["limit"]) {
				$sql	.= " LIMIT ".$config["limit"];
			}
			
			if($config["customkey"]){
				$keycontrol	= $config["customkey"];
			} else {
				$keycontrol	= "id";
			}
			
			$count	= 0;
			if ($resultset = mysql_query($sql)) {
				while ($line = mysql_fetch_array($resultset, MYSQL_ASSOC)) {
					$key = "";
					
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
				if($config["onerow"]==1) {
					$oneresult = $resultlist[$key];
					return $oneresult;
				} else {
					return $resultlist;
				}
			} else {
				echo 'Error pulling from SQL Database: ' . mysql_error() . "\n";
			}
		}
		
		function sqlCreate($config) {
			$fieldlist = "";
			$fieldcount = 0;
			
			foreach ($config["fields"] as $fieldname=>$fieldval) {
				if($fieldcount > 0) {
					$fieldlist .= ", ";
				}
				$fieldval = $fieldval;
				$fieldlist .= $fieldname." = '".$fieldval."'";
				$fieldcount++;
			}
			
			$sql = "INSERT INTO ".$config["table"]." SET ".$fieldlist;
			if (mysql_query($sql)) {
				$newid = mysql_insert_id();
				return $newid;
			} else {
				echo 'Error inserting into SQL Database: ' . mysql_error() . "\n";
				exit;
			}
		}
		
		function sqlCommit($config) {
			$fieldlist = "";
			$fieldcount = 0;
			foreach ($config["fields"] as $fieldname=>$fieldval) {
				if($fieldcount > 0) {
					$fieldlist .= ", ";
				}
				$fieldval = $fieldval;
				$fieldlist .= $fieldname." = '".$fieldval."'";
				$fieldcount++;
			}
			//print($fieldlist."<br>");
			
			$sql = "UPDATE ".$config["table"]." SET ".$fieldlist." WHERE ".$config["where"];
			
			if (mysql_query($sql)) {
				return 1;
			} else {
				echo 'Error committing to SQL Database: ' . mysql_error() . "\n";
				exit;
			}
		}
		
		function sqlDelete($config) {
			$sql = "DELETE FROM ".$config["table"]." WHERE ".$config["where"];
			if (mysql_query($sql)) {
				return 1;
			} else {
				echo 'Error deleting from SQL Database: ' . mysql_error() . "\n";
			}
		}
	// }
	
	function goHere($config) {
		// Jscript {
			print("<script>
			wantedLocation = '".$config."';
			window.location.href = wantedLocation;
			</script>");
		// }
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
?>
