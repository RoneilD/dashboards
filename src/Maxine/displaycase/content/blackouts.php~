<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>Maxine</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link href="favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/fonts.css">
<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/main.css">

<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/cgraph.css">



<!--
<link href='<?php echo BASE;?>/basefunctions/scripts/manline.css' media='all' rel='stylesheet' type='text/css' />
-->

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/modernizr-2.6.2.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script> 

<!--[if lt IE 9]>
<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
<![endif]-->

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.js"></script>
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.time.js"></script>
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.tooltip.js"></script>

</head>
<body ontouchstart="">

<div id="root"></div>

<div id="page">

<header>

<div class="controlsWrapper">


</div><!-- controlsWrapper -->

</header>


<div id="canvassdiv">


</div><!-- canvassdiv -->

<div id="fakecanvasdiv">

</div><!-- canvassdiv -->

</div><!-- end page -->

<script>window.jQuery || document.write('<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js"></script>

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/plugins.js"></script>     
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/styling.js"></script>     
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/main.js"></script>

<script type='text/javascript' language='javascript' src='<?php echo BASE;?>/basefunctions/scripts/manline.js'></script>

<script>
var cyclecount	= 0;
var fleetcount	= 0;
var screenwidth	= screen.width;
var interval1	= setInterval('ajaxTicker()', 30000);
ajaxTicker();

function ajaxTicker() {
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	// Rip the records variables into a string for Posting {
	var params	= '&maxwidth='+screenwidth;
	// }
	
	try {
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e) {
		// Internet Explorer Browsers
		try {
			ajaxRequest = new ActiveXObject('Msxml2.XMLHTTP');
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject('Microsoft.XMLHTTP');
			} catch (e){
				// Something went wrong
				alert('Your browser broke!');
				return false;
			}
		}
	}
	
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var response	= ajaxRequest.responseText;
			
			document.getElementById('canvassdiv').innerHTML = response;
			response=null;
		}
	}
	
	if(cyclecount == 1) {
		ajaxRequest.open('POST', './Maxine/displaycase/displayFleetPositions.php', true);
	} else {
		ajaxRequest.open('POST', './Maxine/displaycase/displayblackouts.php', true);
	}
	
	//Send the proper header information along with the request
	ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	ajaxRequest.setRequestHeader('Content-length', params.length);
	ajaxRequest.setRequestHeader('Connection', 'close');
	
	ajaxRequest.send(params);
	
	cyclecount++;
	if(cyclecount > 1) {
		cyclecount	= 0;
	}
	params=null;
}
window.onunload = function() {
	cyclecount=null;
	fleetcount=null;
	screenwidth=null;
	window.clearInterval(interval1);
	interval1=null;
};
</script>


</body>
</html>        