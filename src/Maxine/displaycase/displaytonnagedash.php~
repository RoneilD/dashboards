<?PHP
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
			//define("BASE", substr(__DIR__, 0, strrpos(__DIR__, "T24")));
			
			include_once(BASE."/basefunctions/localdefines.php");
			include_once(BASE."/basefunctions/dbcontrols.php");
			include_once(BASE."/basefunctions/baseapis/manapi.php");
			
			require_once(BASE."basefunctions/baseapis/FileParser/FileParser.php");
			
			$link					= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
			$db_selected	= mysql_select_db(DB_SCHEMA, $link);
		// }
		
		$t24fleets	= array(
			array("id"=>1, "name"=>"Sappi Express Howick", "budget"=>896),
			array("id"=>8, "name"=>"Sappi Express Mandini", "budget"=>448),
			array("id"=>18, "name"=>"Mandini ADHOC", "budget"=>112),
			array("id"=>16, "name"=>"Howick ADHOC", "budget"=>99),
			array("id"=>12, "name"=>"Howick PBS", "budget"=>66.5),
			array("id"=>7, "name"=>"Long Distance", "budget"=>1120)
			);
		
		$rawstartdate	= mktime(0,0,0,date("m"),(date("d")-15),date("Y"));
		$startdate		= date("Y-m-d", $rawstartdate);
		
		$rawstopdate	= mktime(0,0,0,date("m"),(date("d")+1),date("Y"));;
		$stopdate			= date("Y-m-d", $rawstopdate);
		
		$maincounter	= $conf["cyclecount"];
		$fleetcounter	= $conf["fleetcount"];
	// }
	
	print("<p style='color:WHITE;'>Main counter ".$maincounter.", fleet counter ".$fleetcounter.".</p>");
	
	if($maincounter==0) {
		$today				= mktime(0,0,0,date("m"),date("d"),date("Y"));
		
		$fleetscores	= sqlPull(array("table"=>"fleet_scores", "where"=>"date=".$today, "customkey"=>"fleetid"));
		
		print("<embed src='".BASE."/images/Heading.swf'
			FlashVars='heading=Daily Fleet Expected Tonnage Comparison'
			quality='high'
			name='number'
			width='1880px'
			height='150px'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
		
		foreach ($t24fleets as $fleetkey=>$fleetval) {
			$fleetid	= $fleetval["id"];
			
			if(($fleetscores[$fleetid]["tonnage"]) && ($fleetval["budget"])) {
				print("<embed src='".BASE."/images/Rasta.swf'
					FlashVars='fleet=".$fleetval["name"]."&income=".$fleetscores[$fleetid]["tonnage"]."&target=".$fleetval["budget"]."'
					quality='high'
					name='number'
					width='1880px'
					height='150px'
					wmode='transparent'
					allowScriptAccess='sameDomain'
					allowFullScreen='false'
					type='application/x-shockwave-flash'
					pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
			}
		}
	} else {
		$overweights	= 0;
		$fleetid			= $t24fleets[$fleetcounter]["id"];
		$startdate		= mktime(0,0,0,date("m"),1,date("Y"));
		$stopdate			= mktime(0,0,0,(date("m")+1),1,date("Y"));
		
		$fleetscores	= sqlPull(array("table"=>"fleet_scores", "where"=>"fleetid=".$fleetid." AND date >= ".$startdate." AND date <= ".$stopdate, "sort"=>"date"));
		
		$flashstring	= "green=Great&yellow=Average&orange=Bad&red=Extremely Bad&count=".count($fleetscores);
		if($fleetscores) {
			$daycount	= 1;
			foreach ($fleetscores as $daykey=>$dayval) {
				$averagetons	= round($dayval["tonnage"] / $dayval["tripcount"], 2);
				$flashstring	.= "&ginput".$daycount."=".$averagetons;
				$overweights	+= $dayval["overweightcount"];
				$daycount++;
			}
		}
		
		print("<div style='margin-top:40px; margin-bottom:20px;'>");
		print("<embed src='".BASE."/images/t24tonnageheading.swf'
			FlashVars='heading=".$t24fleets[$fleetcounter]["name"]."&overloads=".$overweights."'
			quality='high'
			name='number'
			width='1880px'
			height='150px'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
		print("</div>");
		
		print("<div style=''>");
		print("<embed src='".BASE."/images/t24dailytonnage.swf'
			FlashVars='".$flashstring."'
			quality='high'
			name='number'
			width='1880px'
			height='860px'
			wmode='transparent'
			allowScriptAccess='sameDomain'
			allowFullScreen='false'
			type='application/x-shockwave-flash'
			pluginspage='http://www.macromedia.com/go/getflashplayer' / >");
		print("</div>");
	}
?>
