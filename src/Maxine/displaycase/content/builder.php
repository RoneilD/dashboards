<?php
/* print('<pre>');
print_r($fleetlist);
print('</pre>'); */
?>

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

<div id="page" class="builder">

<div id="dashboardBuilder">

<form  action='/Maxine/?updatemydashdetails' method="post" class="builderForm">

<div class="dashboardOptions">

<div class="inner">

<div class="control overview">

<div class="button" id="addOverview">Add Overview Graph <i class="fa fa-caret-down"></i></div>

<div class="dropdown">

<ul class="overviewFleetSelector">

<?php

//loop through fleet list
foreach ($fleetlist as $fleetkey=>$fleetval):

//display income and contribution graphs
if ((isset($_SESSION["isit"]) && $_SESSION["isit"]>0) || (isset($user["ismanager"]) && $user["ismanager"]>0)):

?>      

<li data-id="20<?php echo $fleetkey;?>" data-name="<?php echo $fleetval;?>" data-type="Overview"><?php echo $fleetval;?> - Contribution</li>
<li data-id="<?php echo $fleetkey;?>" data-name="<?php echo $fleetval;?>" data-type="Overview"><?php echo $fleetval;?> - Income</li>




<?php
//incomegraph only
else:
?>

<li data-id="<?php echo $fleetkey;?>" data-name="<?php echo $fleetval;?>" data-type="Overview"><?php echo $fleetval;?></li>


<?php
endif;
endforeach;
?>

</ul>


</div><!-- dropdown -->

</div><!-- overview -->

<div class="control overview">

<div class="button" id="addOverview">Add OCD dashboard <i class="fa fa-caret-down"></i></div>

<div class="dropdown">

<ul class="overviewFleetSelector">
<?php
foreach ($fleetlist as $fleetkey=>$fleetval):
?>

<li data-id="-<?php echo $fleetkey;?>" data-name="<?php echo $fleetval;?>" data-type="OCD Dashboard"><?php echo $fleetval;?></li>

<?php  endforeach;

?>

</ul>


</div><!-- dropdown -->

</div><!-- overview -->

<div class="control comparison">

<div class="button" id="addComparison">Add Comparison <i class="fa fa-caret-down"></i></div>


<div class="dropdown">

<ul class="groupSelector">


<?php
//loop through fleet groups
foreach($fleet_groups as $group):
$groups = explode(" - ",$group);
?>

<li class="normal"><?php echo $group;?></li>

<?php
break;
endforeach;

?>


</ul>


</div><!-- dropdown -->

</div><!-- overview -->

<div class="control blackout">

<div class="button" id="addBlackout" data-id="1040" data-name="All Fleet" data-type="Blackout">Add Blackout</div>

</div><!-- overview -->

<div class="control duration">

<div class="button white" id="changeDuration"><?php echo $userdash["duration"];?> sec <i class="fa fa-caret-down"></i></div>

<div class="dropdown">

<ul class="durationSelector">

<li data-value="5">5 sec</li>

<li data-value="10">10 sec</li>

<li data-value="15">15 sec</li>

<li data-value="30">30 sec</li>

<li data-value="45">45 sec</li>

<li data-value="60">60 sec</li>

<li data-value="75">75 sec</li>

<li data-value="90">90 sec</li>

<li data-value="105">105 sec</li>

<li data-value="120">120 sec</li>

</ul>


</div><!-- dropdown -->

</div>


<div class="control">

<input type="hidden" name="conf[duration]" class="inpDuration" value="<?php echo $userdash["duration"];?>"/>

<input type="submit" class="btn button submit" id="saveDashboard" value="Save / Exit"/>

</div>



<div class="clear"></div>

</div><!-- inner -->

</div><!-- dashboardOptions -->

<div class="dashboardBuilderMain">

<div class="comparisonSelector">

<a href="#" class="closeComparisonSelector"></a>

<div class="container">

<div class="column">

<p class="title">Slider Name</p>

<input type="text" name="sliderName" class="" value="My fleet name" onclick="this.value='';" maxlength="20"/>

</div>

<div class="column">

<p class="title selectedTitle">Select <span class="totalSelections">0</span>/9</p>

<div class="fleetsDropdown">



<ul class="fleetsSelector">

<?php

foreach($fleetlist as $key=>$fleet):
// $group_name = (isset($fleet["structure"][1])) ? $fleet["structure"][0]." - ".$fleet["structure"][1] : $fleet["structure"][0];
?>


<li data-id="<?php echo $key;?>" data-group="<?php echo $fleet_groups[0] ?>" class="hide"><?php echo $fleet;?></li>

<?php

endforeach;

?>

</ul>

</div><!-- fleetsDropdown -->

</div>


<div class="column">

<a class="btn button submit">Save / Add</a>

</div>

</div><!-- container -->

</div><!--comparisonSelector -->

<div class="dashboardArea">

<h2>My Dashboard</h2>

<div class="dashboardList">

<div class="inner">

<?php
$count = 0;
foreach ($pattern as $patternkey=>$patternval):
if($patternval < 25000){
	foreach ($fleetlist as $fleetkey=>$fleetval):
	if($fleetkey==$patternval || "20".$fleetkey==$patternval || (substr($patternval, 0, 1) === '-' && $fleetkey==substr($patternval, 1)) || in_array($patternval, (array)array('1040', '1010', '1000', '1500', '1510'))):
	
	$fleetname = $fleetval;
	$fleethead = "Overview";
	
	if (substr($patternval,0, 1) == "-")
	{
		$fleetname = $fleetval;
		$fleethead = "OCD Dashboard";
	}
	if($patternval == "1040") {
		$fleetname = "All Fleet";
		$fleethead = "Blackout";
	}
	if($patternval == "1010"){
		$fleetname = "Fleet Comparison by Month";
		$fleethead = "Comparison";
	}
	if($patternval == "1000"){
		$fleetname = "Fleet Comparison by Day";
		$fleethead = "Comparison";
	}
	if($patternval == "1500"){
		$fleetname = "Fleet Comparison by Day";
		$fleethead = "Comparison";
	}
	if($patternval == "1510"){
		$fleetname = "Fleet Comparison by Month";
		$fleethead = "Comparison";
	}
	?>
	
	
	<div class="dashboard" id="d<?php echo $count;?>">
	
	
	
	<p class="type"><?php echo $fleethead;?></p>
	
	<a href="#" class="drag"></a>
	<a href="#" class="delete" data-id="<?php echo $count;?>"></a>
	
	<div class="dTitle">
	<input type=hidden id='status<?php echo $count;?>' class="status" name='conf[pattern][<?php echo $count;?>][status]' value='1'>
	<input type=hidden class="fleetid"  name='conf[pattern][<?php echo $count;?>][fleetid]' value='<?php echo $patternval;?>'>
	
	
	<p><?php echo $fleetname;?></p>
	
	
	</div><!-- dTitle -->
	
	</div><!-- dashboard -->
	
	
	<?php
	break;
	endif;
	endforeach;
	?>
	
	<?php
}
else
{
	//custom slide
	$patternval = (int)$patternval;
	
	$slidedata = $fleetdayobj->getSlideFleets($patternval);
	$slidefleetsdata = $slidedata["fleets"];
	$fleets = "";
	// $count = 0;
	$cnt = (int)0;
	foreach($slidefleetsdata as $f){
		
		$fleets .= $f["fleet_id"].",";
		$count++;
		$cnt++;
	}
	
	?>
	
	<div class="dashboard comparison" id="d<?php echo $count;?>">
	
	<p class="type">Comparison</p>
	
	<a href="#" class="drag"></a>
	<a href="#" class="delete" data-id="<?php echo $count;?>"></a>
	
	<div class="dTitle">
	
	<input type="hidden" class="slidefleets" value="<?php echo rtrim($fleets,",");?>"/>
	
	<input type=hidden id='status<?php echo $count;?>' class="status" name='conf[pattern][<?php echo $count;?>][status]' value='1'>
	<input type=hidden class="fleetid"  name='conf[pattern][<?php echo $count;?>][fleetid]' value='<?php echo $patternval;?>'>
	
	
	<p><?php echo $fleetdayobj->getSliderName($patternval);?></p>
	
	<span class="fleetcount"><?php echo $cnt;?></span>
	
	</div><!-- dTitle -->
	
	</div><!-- dashboard -->
	
	<?php
	
}

$count++;

endforeach;

?>

<div class="clear"></div>


</div><!-- inner -->



</div><!-- dashboards -->

</div><!-- dashboardArea -->

</div><!-- dashboardBuilderMain -->

</form>

<!-- hidden post form that contains user selected fleets -->
<form  action='/Maxine/?savecustomslide' method="post" class="customPostForm">

<input type="hidden" name="conf[slide_name]" value=""/>
<input type="hidden" name="conf[fleet_ids]" value=""/>
<input type="hidden" name="conf[slide_id]" value=""/>

</form>


<div class="hidden">

<div class="dashboard">

<p class="type">Overview</p>

<a href="#" class="drag"></a>
<a href="#" class="delete" data-id=""></a>

<div class="dTitle">


<input type=hidden class="status" name='conf[pattern][][status]' value='1'>
<input type=hidden  class="fleetid" name='conf[pattern][][fleetid]' value=''>


<p></p>


</div><!-- dTitle -->

</div><!-- dashboard -->

</div><!-- hidden -->


</div><!-- dashboardBuilder -->

</div><!-- end page -->

<script>window.jQuery || document.write('<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js"></script>
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/plugins.js"></script>     
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/styling.js"></script>     
<script src="<?php echo BASE;?>Maxine/displaycase/content/site/js/main.js"></script>
<script type='text/javascript' language='javascript' src='<?php echo BASE;?>/basefunctions/scripts/manline.js'></script>
</body>
</html> 