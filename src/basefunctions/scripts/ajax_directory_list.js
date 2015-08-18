var directory = {
		baseUrl:location.protocol+'//'+location.host+'/',
		fileUploaded: true,
		location: "",
		returnElementId: "",
		xmlHttp: ""
};
//Accessors
directory.getBaseUrl = function () {return this.baseUrl;};
directory.getFileUploaded = function () {return this.fileUploaded;};
directory.getLocation = function() {return this.location;};
directory.getReturnElementId = function () {return this.returnElementId;};
directory.getXmlHttp = function() {return this.xmlHttp;};
directory.setBaseUrl = function (url) {this.baseUrl = url;};
directory.setFileUploaded = function (success) {this.fileUploaded = success;};
directory.setLocation = function(location) {this.location = location;};
directory.setReturnElementId = function(elmId) {this.returnElementId = elmId;};
directory.setXmlHttp = function(xmlHttp) {
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
//Functions
directory.callBack = function () {
	var parent = document.getElementById(directory.getReturnElementId()), xmlhttp = directory.getXmlHttp();
	if (xmlhttp.readyState == 4) {
		directory.disableCheckBoxes(true);
		switch (xmlhttp.responseText) {
		case "File doesn't exist on the server as yet":
		case "I'm sorry but we couldn't delete the file. Please try again later":
			alert(xmlhttp.responseText);
			directory.setFileUploaded(false);
			break;
		case "File delete successful":
			alert(xmlhttp.responseText);
			directory.setFileUploaded(true);
			break;
		case "":
			directory.setFileUploaded(false);
			break;
		default:
			directory.setFileUploaded(true);
			var inpt1 = document.createElement("INPUT"), inpt2 = document.createElement("INPUT");
			inpt1.setAttribute("type", "hidden");
			inpt1.setAttribute("id", "name[]");
			inpt1.setAttribute("name", "name[]");
			inpt1.setAttribute("value", xmlhttp.responseText.substring(xmlhttp.responseText.indexOf("/")+1, xmlhttp.responseText.lastIndexOf(".")));
			inpt2.setAttribute("type", "hidden");
			inpt2.setAttribute("id", "file[]");
			inpt2.setAttribute("name", "file[]");
			inpt2.setAttribute("value", xmlhttp.responseText);
			
			parent.appendChild(inpt1);
			parent.appendChild(inpt2);
			break;
		}
		document.getElementById("upload_process").style.display = "none";
		directory.disableCheckBoxes(false);
	}
};
directory.disableCheckBoxes = function (state) {
	var inputs =document.getElementById("uploaderForm").getElementsByTagName("INPUT"), i;
	for (i=0;i<inputs.length;i++) {
		if (inputs[i].id == "checkAll") {continue;}
		if (inputs[i].getAttribute("type") != "checkbox") {continue;}
		inputs[i].disabled = state;
	}
};
directory.doScan = function(location, elmId) {
	// Preparation
	this.setLocation(location);
	this.setReturnElementId(elmId);
	this.setXmlHttp();
	// the work
	var xmlhttp = this.getXmlHttp(), queryUrl = this.getBaseUrl()+"Maxine/Directory.php?location="+this.urlencode(this.getLocation()), parent = this.getReturnElementId();
	location = this.getLocation();
	if (location.toString().substring(location.length-1, location.length) != "/") {
		location = location+"/";
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			// clear the old elements
			while (document.getElementById(parent).childNodes[0]) {
				document.getElementById(parent).removeChild(document.getElementById(parent).childNodes[0]);
			}
			if (!xmlhttp.responseText) {
				alert("Something went horribly wrong. Please try again later.");
				return false;
			}
			eval(xmlhttp.responseText);
			// append the new elements
			for (var i=0;i<files.length;i++) {
				var br = document.createElement("BR"), input = document.createElement("INPUT"), label = document.createElement("LABEL");
				input.setAttribute("id", "fileItem["+i+"]");
				input.setAttribute("type", "checkbox");
				input.setAttribute("value", location+directory.urldecode(files[i]));
				input.onchange = function () {
					directory.handleUpload(this);
				};
				
				label.setAttribute("for", "fileItem["+i+"]");
				label.appendChild(document.createTextNode(directory.urldecode(files[i])));
				
				document.getElementById(parent).appendChild(label);
				document.getElementById(parent).appendChild(input);
				document.getElementById(parent).appendChild(br);
			}
			if (document.getElementById("checkAll")) {
				document.getElementById("checkAll").disabled = "";
			}
		}
	};
	xmlhttp.open("GET", queryUrl, true);
	xmlhttp.send(null);
};
directory.handleFormInputs = function(formId, elem) {
	var inputs = document.getElementById(formId).getElementsByTagName("INPUT"), i, xmlhttp = this.getXmlHttp(), queryUrl = "", parent = document.getElementById(this.getReturnElementId()), j = 0;
	for (i=0;i<inputs.length;i++) {
		if (inputs[i].id == elem.id) {continue;}
		if (inputs[i].getAttribute("type") != "checkbox") {continue;}
		queryUrl = this.getBaseUrl()+"Maxine/ajax_file_read.php?location="+this.urlencode(inputs[i].value);
		if (!elem.checked) {queryUrl += "&unlink=1";}
		inputs[i].checked = elem.checked;
		xmlhttp.onreadystatechange = directory.callBack;
		xmlhttp.open("GET", queryUrl, true);
		xmlhttp.send(null);
		document.getElementById("upload_process").style.display = "";
	}
};
directory.handleUpload = function (elem) {
	var xmlhttp = this.getXmlHttp(), queryUrl = this.getBaseUrl()+"Maxine/ajax_file_read.php?location="+this.urlencode(elem.value), parent = this.getReturnElementId();
	if (!elem.checked) {queryUrl += "&unlink=1";}
	xmlhttp.onreadystatechange = directory.callBack;
	xmlhttp.open("GET", queryUrl, true);
	xmlhttp.send(null);
	document.getElementById("upload_process").style.display = "";
};
directory.urlencode = function(str) {
    return escape(str).replace(/\+/g, '%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');
};
directory.urldecode = function(str) {
	return str.replace(/\%2B/g, '+').replace(/\+/g, ' ').replace(/\%2A/g, '*').replace(/\%2F/g, '/').replace(/\%40/g, '@').replace(/\%26/, '&');
};