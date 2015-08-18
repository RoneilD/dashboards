<?PHP
	function runFleetDayImport() {
		print("<body style='margin:0px; padding:0px;'>");
		print("<table style='width:100%; height:100%;' cellspacing=0 cellpadding=0 border=0>");
		
		print("<tr><td id='canvasstd' align='center' valign='top'>");
		print("Running... ".date("h:i:s", 1272894029));
		print("</td></tr>");
		
		print("<tr><td>");
		print("</td></tr>");
		
		print("</table>");
		print("</body>");
	
		// Javascript {
			print("<script>
				var count	 = 0;
				//var importInterval	= setInterval('ajaxTicker()', 300000 );
				ajaxTicker();
				
				function ajaxTicker() {
					var ajaxRequest;  // The variable that makes Ajax possible!
					
					// Rip the records variables into a string for Posting {
						var params		= 'count='+count;
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
					
					function return(){
						
						
					}
					
					// Create a function that will receive data sent from the server
					ajaxRequest.onreadystatechange = function(){
						if(ajaxRequest.readyState == 4){
							var response	= ajaxRequest.responseText;
							
							document.getElementById('canvasstd').innerHTML = response;
						}
					}
					
					ajaxRequest.open('POST', './displaycase/fleetDayImporter.php', true);
					
					//Send the proper header information along with the request
					ajaxRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					ajaxRequest.setRequestHeader('Content-length', params.length);
					ajaxRequest.setRequestHeader('Connection', 'close');
					
					ajaxRequest.send(params);
					count++;
					if(count > 10) {
						count	= 0;
					}
				}
				
			</script>");
		// }
	}
?>
