<?php

		print('<!DOCTYPE html>');
		print('<head>');
		print('<meta charset="utf-8">');
        print('<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">');
 		print('<title>Maxine</title>');
       	print('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />');
        print('<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">');
        print('<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">');
        print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/fonts.css">');
        print('<link rel="stylesheet" href="'.BASE.'Maxine/displaycase/content/site/css/main.css">');
        print('</head><body>');
        print('<div id="root"></div>');
		print("<div id='page'>");
		print("<div id='dashboardMain'>");
		print('<div class="graphLower">');
		// Selector {
			print('<div class="infoBlock table"><div class="inner"><div class="tableWrap headers">');
			
			print("<table class='standard' style='width:350px;'>");
			
			print("<tr class='content1'><td align='center' width=40%>");
			print("Fleet");
			print("</td><td align='center' width=60%>");
			print("<select id=\"conf[fleetid]\" name='conf[fleetid]' value='".$fleetid."' style='width:185px;'>");
			print("<option value=0 ".($fleetid==0?"selected":"").">All</option>");
			foreach ($fleetlist as $fleetkey=>$fleetval) {
				if (!$fleetval)
				{
					continue;
				}
				print("<option value='".$fleetval["id"]."'".($fleetid==$fleetval["id"]?" selected='selected'":"").">".$fleetval["name"]." (".(isset($rows[$fleetval["id"]]) && isset($rows[$fleetval["id"]]["count"]) ? $rows[$fleetval["id"]]["count"]." (".$rows[$fleetval["id"]]["subbie_count"].")" : 1).")</option>");
			}
			print("</select>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center'>");
			print("Start Date");
			print("</td><td align='center'>");
			print("<input id='conf[startdate]' name='conf[startdate]' value='".date("d/m/Y", $startdate)."' readonly style='width: 160px; text-align: center;'>");
			print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[startdate]\", this, \"dmy\", \"\");'>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center'>");
			print("Stop Date");
			print("</td><td align='center'>");
			print("<input id='conf[stopdate]' name='conf[stopdate]' value='".date("d/m/Y", $stopdate)."' readonly style='width: 160px; text-align: center;'>");
			print("<img src='".BASE."/images/calendar.png' onClick='displayDatePicker(\"conf[stopdate]\", this, \"dmy\", \"\");'>");
			print("</td></tr>");
			
			print("<tr class='content1'><td align='center' colspan=2>");
			maxineButton("Submit", "document.getElementById(\"scorecheckform\").submit();");
			maxineButton("Export", "exportFleetScoreData();");
			print("</td></tr>");
			
			print("</table>");
			print('</div></div></div>');
		// }
			
		if($updatelist) {			
			print('<div class="infoBlock table"><div class="inner"><div class="tableWrap headers">');
			print("<table class='standard' style='width:1300px; margin-bottom:20px;'>");
			
			// Headers {
				print("<tr class='heading'><td width=5%>");
				print("ID");
				print("</td><td width=11%>");
				print("Fleet");
				print("</td><td width=7%>");
				print("Date");
				print("</td><td width=12%>");
				print("Updated");
				print("</td><td width=6%>");
				print("Difference");
				print("</td><td width=9%>");
				print("Income");
				print("</td><td width=8%>");
				print("Budget");
				print("</td><td width=5%>");
				print(shortenWord('Subbie Income', 8));
				print("</td><td width=5%>");
				print(shortenWord('Subbie Kms', 8));
				print("</td><td width=8%>");
				print("Budget Contrib");
				print("</td><td width=8%>");
				print("Contrib");
				print("</td><td width=8%>");
				print(shortenWord("Contrib Updated", 8));
				print("</td><td width=5%>");
				print("Kms");
				print("</td><td width=9%>");
				print(shortenWord("Ave. Kms per truck", 4));
				print("</td><td width=9%>");
				print(shortenWord("Budget Ave. Kms per truck", 6));
				print("</td></tr>");
			// }
			$totkmspertruck = (int)0;
			$totbudgetkmspertruck = (int)0;
			foreach ($updatelist as $updatekey=>$updateval) {
				$difference	= (date("U") - $updateval["updated"]);
				
				$diffhours	= $difference / 60 / 60;
				$diffhours	= floor($diffhours);
				
				$diffmins		= $difference - $diffhours * 60 * 60;
				$diffmins		= $diffmins / 60;
				$diffmins		= floor($diffmins);
				
				$day				= date("d", $updateval["date"]);
				
				print("<tr class='".$background."'><td>");
				print($updateval["id"]);
				print("</td><td>");
				print($fleetlist[$updateval["fleetid"]]["name"]);
				print("</td><td>");
				print(date("d m Y", $updateval["date"]));
				print("</td><td>");
				if($updateval["updated"] <> 0) {
				print(date("H:i d-m-Y", $updateval["updated"]));
				} else {
					print("Empty");
				}
				print("</td><td>");
				print($diffhours."h ".$diffmins."m");
				print("</td><td>");
				print($updateval["income"]);
				print("</td><td>");
				print($updateval["budget"]);
				print("</td><td>");
				print($updateval["subbie_income"]);
				print("</td><td>");
				print($updateval["subbie_kms"]);
				print("</td><td>");
				print($updateval["budgetcontrib"]);
				print("</td><td>");
				print($updateval["contrib"]);
				print("</td><td>");
				if($updateval["contribupdated"] <> 0) {
					print(date("H:i d-m-Y", $updateval["contribupdated"]));
				} else {
					print("Empty");
				}
				print("</td><td>");
				print($updateval["kms"]);
				print("</td><td>");
				print(round(($updateval["kms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1)), 2));
				print("</td><td>");
				print(round(($updateval["budkms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1)), 2));
				print("</td></tr>");
				
				$totincome	+= $updateval["income"];
				$totbudget	+= $updateval["budget"];
				$totcontrib	+= $updateval["contrib"];
				$totbudgetcontrib	+= $updateval["budgetcontrib"];
				$totkms	+= $updateval["kms"];
				$totkmspertruck	+= ($updateval["kms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1));
				$totbudgetkmspertruck	+= ($updateval["budkms"]/(isset($rows[$updateval["fleetid"]]) && isset($rows[$updateval["fleetid"]]["count"]) ? $rows[$updateval["fleetid"]]["count"] : 1));
				
				if($background == "content2") {
					$background	= "content1";
				} else {
					$background	= "content2";
				}
			}
			
			print("<tr class='".$background."'><td colspan=5>");
			print("</td><td>");
			print($totincome);
			print("</td><td>");
			print($totbudget);
			print("</td><td>");
			print("</td><td>");
			print("</td><td>");
			print($totbudgetcontrib);
			print("</td><td>");
			print($totcontrib);
			print("</td><td>");
			print("</td><td>");
			print(round($totkms, 2));
			print("</td><td>");
			print(round($totkmspertruck, 2));
			print("</td><td>");
			print(round($totbudgetkmspertruck, 2));
			print("</td></tr>");
			print("</table>");

			print('</div></div></div>');
		}
		print('</div>');
		print('</div>');
		print("</div></body>");
		$js = "<script type=\"text/javascript\">";
		$js .= "function exportFleetScoreData() {";
		$js .= "var u;";
		$js .= "u='/Maxine/index.php?mode=maxine/index&action=exportfleetscoreupdates';";
		$js .= "u+='&fleet='+document.getElementById('conf[fleetid]').value;";
		$js .= "u+='&start='+document.getElementById('conf[startdate]').value;";
		$js .= "u+='&end='+document.getElementById('conf[stopdate]').value;";
		$js .= "document.location=u;";
		$js .= "}";
		$js .= "</script>";
		print($js);