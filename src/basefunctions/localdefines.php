<?PHP
$mysql_file = $_SERVER['HOME'].'/.my.cnf';
if (file_exists($mysql_file) && (is_readable($mysql_file) === TRUE))
{
	$file = file($mysql_file);
	$user = substr(preg_replace('/"/', '', $file[1]), 5);
	$password = substr(preg_replace('/"/', '', $file[2]), 9);
}
else
{
	$user = (string)'';
	$password = (string)'';
}
//: Database defines
define("DB_HOST", "localhost");
define("DB_USER", trim($user));
define("DB_PASS", trim($password));
define("DB_SCHEMA", "maxinedb");
//: End Database defines

define("FIRSTBASE", BASE."Maxine");
define("BIGBUTTONS", BASE."/images/mainbuttons");
define("DISPLAYCASE", BASE."images/displaycase");
define("TOPBUTTONS", BASE."/images/topbuttons");
define("ROLLOVER", "onmouseover=\"this.style.backgroundColor='#F2F7F1';\" onmouseout=\"this.style.backgroundColor='WHITE';\"");

define("FONT1", "<font style='font-family:verdana; font-size:12; height:11;'>");
define("FONT2", "<font style='font-family:verdana; font-size:16; height:11;'>");
define("YELFONT1", "<font color=#FFDE00 style='font-family:verdana; font-size:12; height:11;'>");
define("YELFONT2", "<font color=#FFDE00 style='font-family:verdana; font-size:16; height:11;'>");
define("MAXINETOP", "#D3DFC7");
define("MANGREEN", "#065728");
define("MAXINEBACK", "#F7F9F4");
define("MAXINEBACKALT", "#EDF3E9");
define("MANYELLOW", "#FFDE00");

define("FLASHGRAPH", BASE."/basefunctions/flashcharts/charts.swf");
define("FLASHGRAPHLIB", BASE."/basefunctions/flashcharts/charts_library");