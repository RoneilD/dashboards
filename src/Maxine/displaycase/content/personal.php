<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>Dashboards - Barloworld Transport</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link href="favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" href="<?php echo BASE;?>/basefunctions/scripts/bootstrap.min.css">
<link href="<?php echo BASE;?>/basefunctions/scripts/font-awesome.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/fonts.css">
<link rel="stylesheet" href="<?php echo BASE;?>Maxine/displaycase/content/site/css/main.css">


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


<span class="rewind" onClick='rewindSequence();'></span>

<span class="play" id="playbutton" onClick='playPressed();'></span>

<span class="pause" id="pausebutton" onClick='pausePressed();'><i class="fa fa-pause"></i></span>

<span class="forward" onClick='fasttrackSequence();'></span>

<a href="#" class="menu"></a>

</div><!-- controlsWrapper -->

</header>

<nav>

<ul>

<li><a href="/?mydashdetails">Dashboard Builder</a></li>

<li><a href="/?importfleetday">Import Day</a></li>

<li><a href="/?checkfleetscoreupdates">Fleet Scores</a></li>

<li><a href="/?ocddata">OCD Data</a></li>

<li><a href="/?logout">Logout</a></li>

</ul>

</nav>

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
<?php echo $scriptstr;?>


var cnt=0,cycleCounter = 0, cycleduration = "<?php echo ($duration * 1000);?>",hbto, max = <?php echo $patterncount;?>, screenwidth = screen.width, interval1 = setInterval('ajaxTicker()', cycleduration);
ajaxTicker();

function ajaxTicker() {
	// Rip the records variables into a string for Posting {
	var uri, params	= 'fleetcount='+(patternscript[cycleCounter] >= 200 ? patternscript[cycleCounter].toString().substring(2) : patternscript[cycleCounter]);
	params		+= '&maxwidth='+screenwidth+"&dashid="+patternscript[cycleCounter];
	//params		+= '&scrheight='+screenheight;
	//params		+= '&test=A';
	// }
	
	if(patternscript[cycleCounter] == 1000) {
		uri='./Maxine/displaycase/displayFleetCompByDay.php';
	} else if(patternscript[cycleCounter] == 1010) {
		uri='./Maxine/displaycase/displayFleetCompByMonth.php';
	} else if(patternscript[cycleCounter] == 1020) {
		uri='./Maxine/displaycase/displayGreenmile.php';
	} else if(patternscript[cycleCounter] == 1030) {
		uri='./Maxine/displaycase/displayGreenmileMinor.php';
	} else if(patternscript[cycleCounter] == 1040) {
		uri='./Maxine/displaycase/displayblackouts.php';
	} else if(patternscript[cycleCounter] == 1050) {
		uri='./Maxine/displaycase/displayFleetPositions.php';
	} else if(patternscript[cycleCounter] == 1500) {
		uri='./Maxine/displaycase/displayFleetContribCompByDay.php';
	} else if(patternscript[cycleCounter] == 1510) {
		uri='./Maxine/displaycase/displayFleetContribCompByMonth.php';
	} else if (patternscript[cycleCounter] < 0) {
		uri='./Maxine/displaycase/refuel.php';
	} else if(patternscript[cycleCounter] >= 200 && patternscript[cycleCounter] < 25000) {
		uri='./Maxine/displaycase/displayFleetContrib.php';
	} else if(patternscript[cycleCounter] >= 25000) {/* custom comparison graph */
		uri='./Maxine/displaycase/displayComparison.php';
	} else {
		uri='./Maxine/displaycase/displayFleetDetails.php';
	}
	$.post(uri, params, function (data) {
			$("#canvassdiv").fadeOut(1000, "linear", function(){
					$("#canvassdiv").html(data).fadeIn(1000);
					//release variable
					data=null;					
			});
	});
	cycleCounter++;
	if(cycleCounter >= max) {
		cycleCounter	= 0;
	}
	
	params=null;
}

function rewindSequence() {
	if(document.getElementById('playbutton').style.display	== 'none') {
		pauseSequence();
	}
	
	cycleCounter -= 2;
	
	if(cycleCounter < 0) {
		cycleCounter	+= max;
	}
	
	ajaxTicker();
	
	if(document.getElementById('playbutton').style.display	== 'none') {
		playSequence();
	}
}

function fasttrackSequence() {
	if(document.getElementById('playbutton').style.display	== 'none') {
		pauseSequence();
	}
	
	ajaxTicker();
	
	if(document.getElementById('playbutton').style.display	== 'none') {
		playSequence();
	}
}

function playSequence() {
	interval1	= setInterval('ajaxTicker()', cycleduration);
}

function pauseSequence() {
	window.clearInterval(interval1);
}

function playPressed() {
	document.getElementById('pausebutton').style.display	= 'inline-block';
	document.getElementById('playbutton').style.display	= 'none';
	
	playSequence();
}

function pausePressed() {
	document.getElementById('pausebutton').style.display	= 'none';
	document.getElementById('playbutton').style.display	= 'inline-block';
	
	pauseSequence();
}

function returnElement(el) {
	return typeof el === "object" ? el : document.getElementById(el);
}
function fadeIn(e) {
	if (e === null) {return false;}
	var t;
	e.style.opacity = 0;
	e.style.display = "\\";
	t=window.setInterval(function() {
			e.style.opacity = parseFloat(e.style.opacity, 10)+0.1;
			if (parseFloat(e.style.opacity, 10) === 1) {
				window.clearInterval(t);
			}
	} ,30);
}
function fadeOut(e, r) {
	if (e === null) {return false;}
	var t;
	e.style.opacity = 1;
	t=window.setInterval(function() {
			e.style.opacity = parseFloat(e.style.opacity, 10)-0.1;
			if ((parseFloat(e.style.opacity, 10) === 0) || (parseFloat(e.style.opacity, 10) === 0.10000000000000014)) {
				e.style.display = "none";
				if (r === true) {
					if (e.parentNode !== null) {e.parentNode.removeChild(e);}
				}
				window.clearInterval(t);
			}
	} ,30);
}
function fadeHeaderBar() {
	hbto=window.setTimeout(function () {
			fadeOut(returnElement('h1HeaderBar'));
	},2000);
}
function checkIfHeaderBarHidden() {
	var h=returnElement('h1HeaderBar');
	if (h.style.display === 'none') {
		fadeIn(h);
		fadeHeaderBar();
	}
}
function checkIfHeaderBarVisible() {
	var h=returnElement('h1HeaderBar');
	if (h.style.display === '') {
		window.clearTimeout(hbto);
	}
}

//window.onkeypress = function(event) {
window.onkeydown = function(event){
	if (event.which === 32) {
		if (cnt === 0) {
			cnt++;
			pausePressed();
		} else {
			cnt=0;
			playPressed();
		}
	}
	if (event.which === 99) { /* Options */
		goTo("index.php?mode=maxine/index&action=mydashdetails");
	}
	if (event.which === 104) { /* Home */
		goTo("index.php?action=home");
	}
	if (event.keyCode === 39) { /* Right Arrow Pressed */
		fasttrackSequence();
	}
	if (event.keyCode === 37) { /* Left Arrow Pressed */
		rewindSequence();
	}
}
window.onload=fadeHeaderBar;
window.onunload = function() {
	patternscript=null;
	cycleCounter=null;
	cycleduration=null;
	max=null;
	screenwidth=null;
	window.clearInterval(interval1);
	interval1=null;
};
</script>
</body>
</html>