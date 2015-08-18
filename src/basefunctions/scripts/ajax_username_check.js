var check = {
  baseUrl:location.protocol+'//'+location.host+'/',
  xmlHttp: ""
};
//: accessors
check.getBaseUrl = function () {return this.baseUrl;};
check.getXmlHttp = function() {return this.xmlHttp;};
check.setBaseUrl = function (url) {this.baseUrl = url;};
check.setXmlHttp = function(xmlHttp) {
	if (!xmlHttp) {
		if (window.XMLHttpRequest) {
			xmlHttp = new XMLHttpRequest();
		} else if (window.ActiveXObject) { 
			try {
				xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e){
				try{
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){}
			}
		} else {
			return false;
		}
	}
	this.xmlHttp = xmlHttp;
};
//: End
check.checkUsernameAvailability = function(elem) {
  this.setXmlHttp();
  var xmlhttp = this.getXmlHttp(), queryUrl = this.getBaseUrl()+"Maxine/ajax_username_check.php?username="+elem.value;
  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4) {
      if (!xmlhttp.responseText) {
				alert("Something went horribly wrong. Please try again later.");
				return false;
			}
			if (xmlhttp.responseText == "Username already exists in the database. Please choose another.") {
			  alert(xmlhttp.responseText);
			  elem.value="";
			}
    }
  };
  xmlhttp.open("GET", queryUrl, true);
	xmlhttp.send(null);
};
