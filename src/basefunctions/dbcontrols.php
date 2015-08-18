<?PHP
//: Includes
include_once 'localdefines.php';
//: End
// Preparation {
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
// }

defined("ROLLOVER") || define("ROLLOVER", "onmouseover=\"this.style.backgroundColor='#BBBBBB';\" onmouseout=\"this.style.backgroundColor='WHITE';\"");

function dbMenu() {
	print("<form id=tableform action='../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbedittable' method=post>");
	print("<table width=100%>");
	
	$sql = "SHOW TABLES";
	$result = mysql_query($sql);
	
	if (!$result) {
		echo "DB Error, could not list tables\n";
		echo 'MySQL Error: ' . mysql_error();
		exit;
	}
	
	print("<tr><td align='center'>");
	print("<table bgcolor='BLACK' width=60% cellspacing=1 cellpadding=0>");
	print("<tr bgcolor=#AAAAAA><td align='center'>");
	print("<b>Tables</b>");
	print("</td></tr>");
	print("<input type=hidden id=tablename name=conf[tablename]>");
	while ($row = mysql_fetch_row($result)) {
		print("<tr bgcolor='WHITE' ".ROLLOVER." onClick='tablename.value=\"".$row[0]."\"; tableform.submit();'><td align='center'>");
		print($row[0]);
		print("</td></tr>");
	}
	print("</table>");
	print("</td></tr>");
	
	print("<tr><td align='center'>");
	print("<input type=button value='Add Table' onClick=goTo('../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbaddtable') style='width:120px'");
	print("</td></tr>");
	
	print("</table>");
}

// Adding a new table {
	function dbAddTable() {
		print("<form action='../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbcreatetable')' method='post'>");
		print("<table width=100%>");
		
		print("<tr><td align='center'>");
		print("<table bgcolor='BLACK' width=40% cellspacing=1 cellpadding=0>");
		
		print("<tr bgcolor=#AAAAAA><td align='center'>");
		print("<b>Table Name</b>");
		print("</td></tr>");
		
		print("<tr><td>");
		print("<input name=conf[tablename] style='width:100%;'>");
		print("</td></tr>");
		
		print("</table>");
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<input type=submit value='Add Table' style='width:120px;'>");
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<input type=button value='Back' onClick=goTo('../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbcontrols') onclick style='width:120px;'>");
		print("</td></tr>");
		
		print("</table>");
		print("</form>");
	}
	
	function dbCreateTable() {
		$link = mysql_connect('localhost', 'root', '') or die(mysql_error());
		$db_selected = mysql_select_db('maxinedb', $link);
		
		
		
		if($_POST["conf"]) {
			$conf = $_POST["conf"];
		}
		
		$sql = "CREATE TABLE ".$conf["tablename"]." (id int(50) not null auto_increment primary key)";
		if (mysql_query($sql, $link)) {
			echo "Table ".$conf["tablename"]." created successfully\n";
		} else {
			echo 'Error creating table: ' . mysql_error() . "\n";
		}
	}
// }

function dbEditTable() {
	if($_POST["conf"]) {
		$conf = $_POST["conf"];
	}
	
	$sql = "SHOW COLUMNS FROM ".$conf["tablename"];
	$result = mysql_query($sql);
	
	print("<table width=100%>");
	
	print("<tr><td align='center'>");
	print("<table bgcolor='BLACK' width=60% cellspacing=1 cellpadding=0>");
	print("<tr bgcolor=#AAAAAA><td align='center'>");
	print("<b>Table Columns</b>");
	print("</td></tr>");
	while ($row = mysql_fetch_row($result)) {
		print("<tr bgcolor='WHITE' ".ROLLOVER." onClick='tablename.value=\"".$row[0]."\"; tableform.submit();'><td align='center'>");
		print($row[0]);
		print("</td></tr>");
	}
	print("</table>");
	print("</td></tr>");
	
	print("<tr><td align='center'>");
	print("<input type=button value='Add Column' onClick=goTo('../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbaddcolumn&tablename=".$conf["tablename"]."') style='width:120px;'>");
	print("</td></tr>");
	
	print("<tr><td align='center'>");
	print("<input type=button value='Back' onClick=goTo('../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbcontrols') style='width:120px;'>");
	print("</td></tr>");
	
	print("</table>");
}

// Adding a column to an existing table {
	function dbAddColumn() {
		if($_GET["tablename"]) {
			$tablename = $_GET["tablename"];
		}
		
		print("<form action='../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbcreatecolumn')' method=post>");
		print("<table width=100%>");
		
		print("<tr><td align='center'>");
		
		print("<table bgcolor='BLACK' width=60% cellspacing=1 cellpadding=0>");
		
		print("<tr bgcolor=#AAAAAA><td align='center'>");
		print("<b>Adding Column to '<font color='GREEN'>".$tablename."'</font></b>");
		print("</td></tr>");
		
		print("<input type=hidden name=conf[tablename] value='".$tablename."'>");
		
		print("<tr bgcolor=#AAAAAA><td align='center'>");
		print("<b>Column Name</b>");
		print("</td></tr>");
		print("<tr><td>");
		print("<input name=conf[colname] style='width:100%;'>");
		print("</td></tr>");
		
		print("<tr bgcolor=#AAAAAA><td align='center'>");
		print("<b>Column Type</b>");
		print("</td></tr>");
		print("<tr bgcolor='WHITE'><td>");
		print("<select name=conf[coltype] style='width:100%;'>");
		print("<option>varchar(30)</option");
		print("<option>varchar(255)</option");
		print("<option>int(1)</option");
		print("<option>int(50)</option");
		print("</select>");
		print("</td></tr>");
		
		print("</table>");
		
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<input type=submit value='Add Column' style='width:120px;'>");
		print("</td></tr>");
		
		print("<tr><td align='center'>");
		print("<input type=button value='Back' onClick=goTo('../basefunctions/dbcontrols.php?mode=basefunctions/dbcontrols&action=dbcontrols') style='width:120px;'>");
		print("</td></tr>");
		
		print("</table>");
		print("</form>");
	}
	
	function dbCreateColumn() {
		if($_POST["conf"]) {
			$conf = $_POST["conf"];
		}
		
		$sql = "ALTER TABLE ".$conf["tablename"]." ADD COLUMN ".$conf["colname"]." ".$conf["coltype"];
		
		print($sql."<br>");
		
		if (mysql_query($sql)) {
			echo "Added Column '".$conf["colname"]."' to Table ".$conf["tablename"]." successfully\n";
		} else {
			echo 'Error amending table: ' . mysql_error() . "\n";
		}
	}
// }

// Switch $action {
$action = (array_key_exists("action", $_GET) ? $_GET["action"]: "");

if($action) {
	switch ($action) {
	case "dbcontrols"			: dbMenu(); break;
		
	case "dbaddtable"			: dbAddTable(); break;
	case "dbcreatetable"	: dbCreateTable(); break;
		
	case "dbedittable"		: dbEditTable(); break;
		
	case "dbaddcolumn"		: dbAddColumn(); break;
	case "dbcreatecolumn"	: dbCreateColumn(); break;
	}
}
// }