<?php
$dir = dirname(realpath(__FILE__)).DIRECTORY_SEPARATOR."razzledazzle".DIRECTORY_SEPARATOR;
/* print("<pre>");
print_r($_SERVER);
print("</pre>"); */
if (isset($dir) && $dir && is_dir($dir)) {
	$files = scandir($dir);
	foreach ($files as $key => $value) {
		if (in_array($value, array(".", ".."))) {continue;}
		print("<img alt=\"Razzle Dazzle\" src=\"/Maxine/displaycase/razzledazzle/".$value."\" style=\"width:100%;\" />");
	}
}