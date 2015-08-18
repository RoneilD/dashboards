<?php
	// Prep {
		// Groundwork {
			$conf		= $_POST;
			
			// Defines and includes {
				$times				= substr_count($_SERVER['PHP_SELF'],"/");
				$rootaccess		= "";
				$i						= 1;
				
				while ($i < $times) {
					$rootaccess .= "../";
					$i++;
				}
				
				define("BASE", $rootaccess);
				
				include_once(BASE."/basefunctions/localdefines.php");
				include_once(BASE."/basefunctions/dbcontrols.php");
				include_once(BASE."/basefunctions/baseapis/manapi.php");
				include_once(BASE."Maxine/api/maxineapi.php");
				
				require_once(BASE."basefunctions/baseapis/fleetDayHandler.php");
				
				$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
				$db_selected	= mysql_select_db(DB_SCHEMA, $link);
				
				$fleetdayobj = new fleetDayHandler;
			// }
		// }
		
		// Sort functions {
			function cmpbudgetcontribs($a, $b) {
				if ($a["Date"] == $b["Date"]) {
					return 0;
				}
				return ($a["Date"] < $b["Date"]) ? -1 : 1;
			}
			
			function cmpScores($a, $b) {
				if ($a["score"] == $b["score"]) {
					return 0;
				}
				return ($a["score"] > $b["score"]) ? -1 : 1;
			}
		// }
		
		// Create date strings for query {
			$startday			= date("d");
			$startmonth		= date("m");
			$startyear		= date("Y");
			
			$startstring	= $startyear."-".$startmonth."-".$startday;
			
			$stopdate		= mktime(0, 0, 0, $startmonth, (date("d") + 1), $startyear);
			$stopday		= date("d", $stopdate);
			$stopmonth	= date("m", $stopdate);
			$stopyear		= date("Y", $stopdate);
			
			$stopstring	= $stopyear."-".$stopmonth."-".$stopday;
			
			$count		= 0;
			$rowcount	= 0;
		// }
		
		if($conf["maxwidth"]) {
			$maxwidth	= $conf["maxwidth"];
			
			if($maxwidth < 1000) {
				$factor = 0.8;
			} else if($maxwidth < 1300) {
				$factor	= 0.94;
			} else if($maxwidth > 1600) {
				$factor	= 1.4;
			} else {
				$factor	= 1;
			}
		} else {
			$factor	= 1;
		}
		
		$fleetlist	= $fleetdayobj->getIncomeFleets();
		
		// Pull the details for each fleet {
			foreach ($fleetlist as $fleetkey=>$fleetval) {
				if($fleetval["displayrasta"] == 1) {
					$fleetdetails	= $fleetdayobj->getFleetScoreMonth($fleetval["id"]);
					$fleetid			= $fleetval["id"];
					
					$fleetscore[$fleetid]["name"]	= $fleetval["name"];
					
					foreach ($fleetdetails as $daykey=>$dayval) {
						$fleetscore[$fleetid]["contrib"]	+= $dayval["contrib"];
						$fleetscore[$fleetid]["budkms"]		+= $dayval["budkms"];
						$fleetscore[$fleetid]["budgetcontrib"]	+= $dayval["budgetcontrib"];
					}
					if($fleetscore[$fleetid]["budgetcontrib"] > 0) {
						$fleetscore[$fleetid]["score"]	= round($fleetscore[$fleetid]["contrib"] / $fleetscore[$fleetid]["budgetcontrib"] * 100, 0);
					} else {
						$fleetscore[$fleetid]["score"]	= 0;
					}
					$fleetscore[$fleetid]["contrib"]	= round($fleetscore[$fleetid]["contrib"], 0);
					$fleetscore[$fleetid]["budgetcontrib"]	= round($fleetscore[$fleetid]["budgetcontrib"], 0);
					$rowcount++;
				}
			}
			
			uasort($fleetscore, "cmpScores");
			$slidertop	= $fleetdayobj->calcSliderTop($fleetscore[29]["budgetcontrib"]);
		// }
	// }
	/*
	print("<table width=100% cellpadding=0 cellspacing=0 border=0>");
	
	print("<tr><td align=center colspan=2>");
	print("<embed src='".BASE."/images/Comp_Heading.swf'
		FlashVars='heading=MTD Fleet Contrib. Budget Comparison'
		quality='high'
		width='".(1170 * $factor)."px'
		height='".(89 * $factor)."px'
		name='slider'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	print("</td></tr>");
	
	print("<tr><td rowspan=".$rowcount." align=right>");
	print("<embed src='".BASE."/images/Comp_Slider_MTD.swf'
		FlashVars='min=0&max=".$slidertop."&slide=".$fleetscore[29]["contrib"]."&budget=".$fleetscore[29]["budgetcontrib"]."&graph_title=Entire Active Fleet'
		quality='high'
		width='".(225 * $factor)."px'
		height='".(650 * $factor)."px'
		name='slider'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	print("</td>");
	
	foreach ($fleetscore as $fskey=>$fsval) {
		if(($fsval["budgetcontrib"] > 0) && ($fskey != 29)) {
			if($count > 0) {
				print("<tr>");
			}
			
			print("<td align=left height=1px>");
			print("<embed src='".BASE."/images/rasta_narrow.swf'
				FlashVars='fleet=".$fsval["name"]."&income=".$fsval["contrib"]."&target=".$fsval["budgetcontrib"]."'
				quality='high'
				width='".(990 * $factor)."px'
				height='".(58 * $factor)."px'
				name='graph'
				wmode='transparent'
				allowScriptAccess='sameDomain'
				allowFullScreen='false'
				type='application/x-shockwave-flash'
				pluginspage='http://www.macromedia.com/go/getflashplayer' />");
			print("</td></tr>");
			$count++;
		}
	}
	
	print("</table>");
	*/
	
?>

<div id="comparison">
	
	<div class="upper">
			
			<div class="left">
					
					<div id="comparisonRoundGraph">
							
							<p class="comparisonTotal">R<?php echo number_format($fleetscore[1]["contrib"]);?></p>
							
						<?php
				
							$perc = ((round($fleetscore[1]["contrib"], 0)) / (round($fleetscore[1]["budgetcontrib"], 0))) * 100;
						
							$perc = ($perc <= 100) ? $perc : 100;
						
						?>
							
							<div id="dailygraph" class="graph large">
								
								<div class="percentage"><?php echo (round($perc, 0));?>%</div>	
								
							</div>

							<script>
									$('#dailygraph').circleProgress({
											value: <?php echo $perc / 100;?>,
											size: 240,
											fill: {
												color: "#21a9e1"
											},
											thickness: 25,
											startAngle: -4.75,
											emptyFill: "#353e54",
											animation: {
													duration: 0
											}
									});
							</script>	
																
							
					</div><!-- comparisonRoundGraph -->
					
					<div class="title">
		
							<h2>MTD Fleet Contrib. Budget Comparison</h2>
					
							<p class="datetime"><?php echo date("d F H:i");?></p>
					
					</div><!-- title -->
					
			</div><!-- left -->
			
			<div class="right">
					
					<div id="comparisonGraph">
							
								
					</div><!-- comparisonGraph -->			
					
			</div><!-- right -->
			
			<div class="clear"></div>
			
	</div><!-- upper -->
	
	<div class="lower">
			
			<div class="inner">
					
					<ul>
						
						<?php
																		
						foreach ($fleetscore as $fskey=>$fsval):
							if(($fsval["budget"] > 0) && ($fskey != 29)) :
							
						?>
							
							<li>
									
									<div class="percBar" style="width: <?php echo $fsval["score"];?>%;"></div>
									
									<span class="name"><?php echo $fsval["name"];?></span>
									
									<span class="percValue"><?php echo $fsval["score"];?>%</span>
									
									<div class="clear"></div>
									
							</li>
							
						<?php
							endif;
							
						endforeach;	
							
						?>
							
					</ul>
					
			</div><!-- inner -->
			
	</div><!-- lower -->
	
	<script>

			var chart_border_color = "#323a4f";
			var chart_color = "#21a9e1";
			var data ,options,chart;
				
				
				var actual_data = [
													 
													 
					];
			

				data = [
						{
							data:actual_data,
							lines:{
								show:true,
								lineWidth : 1,
								fill : false,
								fillColor: '#b2cb35'
							},
							points: {
								show: true,
								fillColor: '#21a9e1'
							}
						}];
				
				var options = {
					xaxis: {
						mode: "time",
						timeformat: "%d/%m",
						minTickSize: [1, "day"],
						tickColor: "#293042",
						timezone: "browser"
					},
					grid : {
						hoverable : false,
						clickable : true,
						tickColor : chart_border_color,
						borderWidth : 1,
						borderColor : "#293042",
					},
					colors: ["#b2cb35", "#21a9e1"],
				};
				
				var plot3 = $.plot($("#comparisonGraph"), data, options);
										
		</script>
	
</div><!-- comparison -->
