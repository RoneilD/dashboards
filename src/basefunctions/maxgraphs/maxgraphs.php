<?PHP
	function drawLineGraphX($config) {
		$graph = new drawXLineGraph($config);
		print("<img src='".BASE."/images/graphs/".$config["meta"]["name"].".png' onClick='".$config["meta"]["onclick"]."' alt='graph'>");
	}
	
	function drawBarGraphX($config) {
		$graph = new drawXBarGraph($config);
		print($config["onclick"]);
		
		print("<img src='".BASE."/images/graphs/".$config["meta"]["name"].".png' onClick='".$config["meta"]["onclick"]."' alt='graph'>");
	}
?>
