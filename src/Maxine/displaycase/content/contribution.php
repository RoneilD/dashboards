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

<link rel="stylesheet" href="<?php echo BASE;?>/basefunctions/scripts/bootstrap.min.css">
<link href="<?php echo BASE;?>/basefunctions/scripts/font-awesome.min.css" rel="stylesheet">
<link href="favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/fonts.css">
<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/main.css">

<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/cgraph.css">


<!--
<link href='<?php echo BASE;?>/basefunctions/scripts/manline.css' media='all' rel='stylesheet' type='text/css' />
-->

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/modernizr-2.6.2.min.js"></script>

<script src="<?php echo BASE;?>/basefunctions/scripts/jquery.min.js"></script>

<script src="<?php echo BASE;?>/basefunctions/scripts/jquery.ui.touch-punch.min.js"></script>

<!--[if lt IE 9]>
<script src="<?php echo BASE;?>/basefunctions/scripts/html5shiv.min.js"></script>
<script src="<?php echo BASE;?>/basefunctions/scripts/respond.js"></script>
<![endif]-->

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jcircle.js"></script>

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.js"></script>
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.time.js"></script>
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery.flot/jquery.flot.tooltip.js"></script>

</head>
<body>

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

<script src="<?php echo BASE;?>/basefunctions/scripts/jquery.color.min.js"></script>

<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/plugins.js"></script>     
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/styling.js"></script>     
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/main.js"></script>

<script type='text/javascript' language='javascript' src='<?php echo BASE;?>/basefunctions/scripts/manline.js'></script>

<script>
var cyclecount	= 0;
var fleetcount	= 0;
var greencount	= 0;
var screenwidth	= screen.width;
var interval1	= setInterval('ajaxTicker()', 30000);

ajaxTicker();

function ajaxTicker() {
	// Rip the records variables into a string for Posting {
	var params	= 'fleetcount='+fleetcount;
	params += '&maxwidth='+screenwidth;
	//params		+= '&test=A';
	// }
	
	$.post('./displaycase/displayFleetContrib.php', params, function (data){
			$("#canvassdiv").fadeOut(1000, "linear", function(){
					$("#canvassdiv").html(data).fadeIn(1000);
					//release
					data=null;
			});	
	});
	
	fleetcount++;
	cyclecount++;
	if(cyclecount > 1) {
		cyclecount	= 0;
	}
	if(fleetcount > 14) {
		fleetcount = 0;
	}
	params=null;
}
window.onunload = function() {
	cyclecount=null;
	fleetcount=null;
	greencount=null;
	screenwidth=null;
	window.clearInterval(interval1);
	interval1=null;
};
</script


</body>
</html>
