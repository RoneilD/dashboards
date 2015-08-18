<?php
$filename = $_GET["filename"]; // change your file name 
$filepath = $_GET["filepath"].$filename; // change your dir path;

downloadFile($filename,$filepath);
function downloadFile($filename,$filepath)
{
	header("Content-type: text/x-csv");
	header("Content-disposition: attachment; filename=".$filename); 
	header("Content-Length: " . filesize($filepath));
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	readfile($filepath); 
	return;
}
?>
