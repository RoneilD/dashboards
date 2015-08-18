var ajaxFileUpload = {
        //: Variables
        baseUrl:location.protocol+'//'+location.host+'/', /* base url */
        location:"",
        fileElementId:"",
        returnElementId:""
};

//: Accessors
ajaxFileUpload.getBaseUrl = function () {return this.baseUrl;};
ajaxFileUpload.getLocation = function () {return this.location;};
ajaxFileUpload.getFileElementId = function () {return this.fileElementId;};
ajaxFileUpload.getReturnElementId = function () {return this.returnElementId;};
ajaxFileUpload.setBaseUrl = function (url) {this.baseUrl = url;};
ajaxFileUpload.setLocation = function (loc) {this.location = loc;};
ajaxFileUpload.setFileElementId = function (elemId) {this.fileElementId = elemId;};
ajaxFileUpload.setReturnElementId = function (elemId) {this.returnElementId = elemId;};

//: Functions
ajaxFileUpload.doUpload = function (loc, elemId, fileElemId) {
        this.setLocation(loc);
        this.setFileElementId(fileElemId);
        this.setReturnElementId(elemId);
        this.startUpload();
        document.getElementById("uploaderForm").action = this.getBaseUrl()+"Maxine/ajax_file_upload.php?location="+this.urlencode(this.getLocation());
        document.getElementById("uploaderForm").enctype = "multipart/form-data";
        document.getElementById("uploaderForm").target = "targetFrame";
        document.getElementById("uploaderForm").submit();
};

ajaxFileUpload.startUpload = function () {
        document.getElementById("upload_process").style.display = "";
        return true;
};

ajaxFileUpload.stopUpload = function (success) {
        switch (success) {
        case 1:
                alert("File upload successful");
                break;
        default:
                alert("I'm sorry but something went horribly wrong. Please try again a bit later.");
                break;
        }
        
        document.getElementById(this.getReturnElementId()).value = this.getLocation()+document.getElementById(this.getFileElementId()).value;
        document.getElementById("uploaderForm").action = "";
        document.getElementById("uploaderForm").target = "_self";
        document.getElementById("upload_process").style.display = "none";
};

ajaxFileUpload.urlencode = function(str) {
        return escape(str).replace(/\+/g, '%2B').replace(/%20/g, '+').replace(/\*/g, '%2A').replace(/\//g, '%2F').replace(/@/g, '%40');
};
