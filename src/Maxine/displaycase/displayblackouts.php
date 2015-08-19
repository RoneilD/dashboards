<?PHP
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
				
				$link			= mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA) or die(mysqli_error($link));
				
				$fleetdayobj = new fleetDayHandler;
			// }
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
		$today			= date("j");
		//$today = 1404165600; *TEST
		$totalblackouts = (float)0.00;
		foreach ($fleetlist as $fleetkey=>$fleetval)
		{
			if(array_key_exists('displayblackouts', $fleetval) && ($fleetval["displayblackouts"]==1))
			{
				$fleetdetails	= $fleetdayobj->getFleetScoreDay($fleetval["id"]);
					
				$blackoutlist[$fleetval["id"]]["name"] = $fleetlist[$fleetkey]["name"];
				$blackoutlist[$fleetval["id"]]["blackouts"] = $fleetdetails[$today]["blackouts"];
				//$blackoutlist[$fleetval["id"]]["blackouts"] = $fleetdetails[1]["blackouts"]; *TEST
				$blackoutlist[$fleetval["id"]]["maxid"] = $fleetval["maxid"];
				
				if(($fleetval["maxid"]!=29) && ($fleetval["maxid"]!=42) && ($fleetval["maxid"]!=47) && ($fleetval["maxid"]!=53) && ($fleetval["maxid"]!=32) && ($fleetval["maxid"]!=60) && ($fleetval["maxid"]!=33))
				{
					
					
					if($fleetdetails[$today]["blackouts"] > 0)
					//if($fleetdetails[1]["blackouts"] > 0)*TEST
					{
						$totalblackouts	+= $fleetdetails[$today]["blackouts"];
						//$totalblackouts	+= $fleetdetails[1]["blackouts"]; *TEST
					}
				}
			}
		}
		$count	= 0;
	// }
	
	/*
	print("<table width=100% height=100% cellpadding=0 cellspacing=0 border=0>");
	
	print("<tr height=1px><td align='center'>");
	print("<embed src='".BASE."/images/Heading_Blackouts.swf'
		FlashVars=''
		quality='high'
		width='".(640 * $factor)."px'
		height='".(450 * $factor)."px'
		name='number'
		wmode='transparent'
		allowScriptAccess='sameDomain'
		allowFullScreen='false'
		type='application/x-shockwave-flash'
		pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
	
	print("</td><td align='center'>");
	
	if($totalblackouts > 0) {
		print("<embed src='".BASE."/images/Blackouts_Total.swf'
			FlashVars='b_title=Total&blackouts_total=".$totalblackouts."'
			quality='high'
			width='".(690 * $factor)."px'
			height='".(510 * $factor)."px'
			name='number'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
	} else {
		print("<embed src='".BASE."/images/Blackouts_Bell.swf'
			FlashVars=''
			quality='high'
			width='".(690 * $factor)."px'
			height='".(510 * $factor)."px'
			name='number'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
	}
	print("</td></tr>");
	
	print("<tr><td colspan=2>");
	print("<table width=100% cellpadding=0 cellspacing=0 border=0><tr>");
	foreach ($blackoutlist as $boutkey=>$boutval) {
		if(($boutkey!=29) && ($boutkey!=42) && ($boutkey!=47) && ($boutkey!=53) && ($boutkey!=32) && ($boutkey!=60) && ($boutkey!=33)) {
			print("<td align='center'>");
			
			print("<embed src='".BASE."/images/Blackouts.swf'
				FlashVars='b_title=".$boutval["name"]."&blackouts=".$boutval["blackouts"]."'
				quality='high'
				width='".(220 * $factor)."px'
				height='".(200 * $factor)."px'
				name='number'
				wmode='transparent'
				allowScriptAccess='sameDomain'
				allowFullScreen='false'
				type='application/x-shockwave-flash'
				pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
			print("</td>");
		}
	}
	print("</tr></table>");
	print("</td></tr>");
	print("</table>");
	*/
	

	date_default_timezone_set('Africa/Johannesburg');
?>


<div id="blackouts">
	
	<div class="upper">
		
		<div class="title">
			
			<h2>Blackouts</h2>
			
			<p class="datetime"><?php echo date("d F H:i");?></p>
			
		</div><!-- title -->
		
		<div class="large">
			
			<p class="largeText"><?php echo (!empty($totalblackouts)) ? $totalblackouts : "0";?></p>
			
			<p class="caption">Total Blackouts</p>
			
		
		</div><!-- large -->
		
	</div><!-- -->
	
	<div class="fleetWrapper">
		
		<ul>
			
			<?php
			
			foreach ($blackoutlist as $boutkey=>$boutval):


			
				if(($boutkey!=29) && ($boutkey!=42) && ($boutkey!=47) && ($boutkey!=53) && ($boutkey!=32) && ($boutkey!=60) && ($boutkey!=33)):
			
			
					$blackouts = (isset($boutval["blackouts"]) && !empty($boutval["blackouts"])) ? $boutval["blackouts"] : "0";
			?>
		
			<li>
				
				<span class="value"><?php echo $blackouts;?></span>
				
				<span class="label"><?php echo $boutval["name"];?></span>
				
			</li>
			
			<?php
				
				endif;
				
			endforeach;
			
			
			?>

		
		</ul>
		
		<div class="clear"></div>
		
	</div><!-- fleet wrapper -->
	
</div><!-- blackouts -->

<?php


