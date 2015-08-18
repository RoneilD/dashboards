var ajaxFileUpload = {
  baseUrl:location.protocol+"//"+location.host+"/",
  loadingImgFile:this.baseUrl+"images/new/loading.png",
  loadingImg:"",
  location:this.baseUrl,
  returnDataFormat: {}
};
//: Accessors
ajaxFileUpload.getBaseUrl = function () {return this.baseUrl;};
ajaxFileUpload.getLoadingImgFile = function() {return this.loadingImgFile;};
ajaxFileUpload.getLoadingImg = function() {if (!this.loadingImg) {this.setLoadingImg();}return this.loadingImg;};
ajaxFileUpload.getLocation = function() {return this.location;};
ajaxFileUpload.getReturnDataFormat = function() {return this.returnDataFormat;};
ajaxFileUpload.setBaseUrl = function (url) {this.baseUrl = url;};
ajaxFileUpload.setLoadingImgFile = function(loadingImgFile) {this.loadingImgFile = loadingImgFile;};
ajaxFileUpload.setLoadingImg = function(img) {
  if (typeof img != "object") {img = false;}
  if (!img) {
    var img = document.createElement("IMG");
    img.setAttribute("alt", "Loading...");
    img.setAttribute("id", "LoadingImage");
    img.setAttribute("name", "LoadingImage");
    img.setAttribute("src", ajaxFileUpload.getLoadingImgFile());
    img.setAttribute("style", "display:inline;float:left;height:16px;padding:0px 5px;width:16px;");
  }
  this.loadingImg = img;
};
ajaxFileUpload.setLocation = function(location) {this.location = location;};
ajaxFileUpload.setReturnDataFormat = function(format) {
  if (typeof format  != "object") {return false;}
  this.returnDataFormat = format;
};
//: Functions
ajaxFileUpload.appendNodes = function(returnData) {
  var br = document.createElement("BR"), img = document.createElement("IMG"), input = document.createElement("INPUT"), label = document.createElement("LABEL"), span = document.createElement("SPAN");
  span.setAttribute("style", "float:left;width:200px;");
  
  br.setAttribute("style", "clear:both;");
  
  input.setAttribute("id", returnData.name);
  input.setAttribute("name", returnData.name);
  input.setAttribute("type", "hidden");
  input.setAttribute("value", returnData.value);
  document.getElementById("uploadedFiles").appendChild(input);
  
  switch (returnData.mimetype) {
    case "image/jpeg":
    case "image/gif":
    case "image/png":
      img.setAttribute("src", this.getBaseUrl()+"images/icons/mimetypes/image-x-generic.png");
      break;
    case "application/x-gzip":
    case "application/zip":
    case "application/x-tar":
      img.setAttribute("src", this.getBaseUrl()+"images/icons/mimetypes/application-x-tar.png");
      break;
    case "application/pdf":
      img.setAttribute("src", this.getBaseUrl()+"images/icons/mimetypes/application-pdf.png");
      break;
    case "text/plain":
    case "text/csv":
      img.setAttribute("src", this.getBaseUrl()+"images/icons/mimetypes/text-plain.png");
      break;
    case "application/msword":
    case "application/msaccess":
      img.setAttribute("src", this.getBaseUrl()+"images/icons/mimetypes/wordprocessing.png");
      break;
    default:
      img.setAttribute("src", this.getBaseUrl()+"images/icons/mimetypes/unknown.png");
      break;
  }
  
  img.setAttribute("alt", "mime type image");
  img.setAttribute("id", "mimeImg"+returnData.value);
  img.setAttribute("style", "display:block;height:16px;margin:5px auto;width:16px;");
  span.appendChild(img);
  
  label.setAttribute("for", "mimeImg"+returnData.value);
  label.setAttribute("style", "color:#000;");
  label.appendChild(document.createTextNode(returnData.value));
  
  span.appendChild(label);
  
  span.appendChild(br);
  
  document.getElementById("uploadedFiles").appendChild(span);
}
ajaxFileUpload.enableForm = function(formId) {
  var div = document.createElement("DIV"), iframe=document.createElement("IFRAME");
  iframe.setAttribute("id", "uploadIframe");
  iframe.setAttribute("name", "uploadIframe");
  iframe.setAttribute("src", "#");
  iframe.setAttribute("style", "border:1px solid;display:none;height:600px;width:100%;");
  div.setAttribute("id", "uploadedFiles");
  div.setAttribute("style", "margin:5px 2px;padding:5px;");
  if (document.getElementById('uploadFile').nextSibling.nextSibling) {
    document.getElementById(formId).insertBefore(iframe, document.getElementById('uploadFile').nextSibling.nextSibling);
    document.getElementById(formId).insertBefore(div, document.getElementById('uploadFile').nextSibling.nextSibling);
  } else {
    document.getElementById('uploadFile').parentNode.appendChild(iframe);
    document.getElementById('uploadFile').parentNode.appendChild(div);
  }
}
ajaxFileUpload.startUpload = function(formId, elem) {
  document.getElementById(formId).action = this.getBaseUrl()+"Maxine/ajax_file_upload.php?formId="+formId+"&location="+urlencode(this.getLocation());
  document.getElementById(formId).target = "uploadIframe";
  document.getElementById("uploadedFiles").parentNode.insertBefore(ajaxFileUpload.getLoadingImg(), document.getElementById("uploadedFiles"));
  if (document.getElementById("uploadButton")) {
    document.getElementById("uploadButton").style.visibility = "hidden";
  }
  document.getElementById("uploadFile").style.visibility = "hidden";
  document.getElementById(formId).submit();
  return false;
};
ajaxFileUpload.stopUpload = function(success, formId, returnData) {
  document.getElementById("LoadingImage").parentNode.removeChild(document.getElementById("LoadingImage"));
  if (document.getElementById("uploadButton")) {document.getElementById("uploadButton").style.visibility = "inherit";}
  document.getElementById("uploadFile").style.visibility = "inherit";
  document.getElementById(formId).action = "";
  document.getElementById(formId).target = "";
  if (!success) {alert("I'm sorry but file uploads don't seem to be working currently. Please try again later.");return false;}
  if (returnData) {ajaxFileUpload.appendNodes(returnData);}
};
function urlencode (str) {
  // URL-encodes string  
  // 
  // version: 1103.1210
  // discuss at: http://phpjs.org/functions/urlencode    // +   original by: Philip Peterson
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: AJ
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: travc
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Lars Fischer    // +      input by: Ratheous
  // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Joris
  // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
  // %          note 1: This reflects PHP 5.3/6.0+ behavior    // %        note 2: Please be aware that this function expects to encode into UTF-8 encoded strings, as found on
  // %        note 2: pages served as UTF-8
  // *     example 1: urlencode('Kevin van Zonneveld!');
  // *     returns 1: 'Kevin+van+Zonneveld%21'
  // *     example 2: urlencode('http://kevin.vanzonneveld.net/');    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
  // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
  // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
  str = (str + '').toString();
  // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
  // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
  return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').
    replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
