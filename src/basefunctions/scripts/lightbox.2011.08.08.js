var lightbox={baseUrl:"",colour:"",current:0,closeImage:"",imageList:[],lightboxClass:"",loadingImage:"",nextImage:"",previousImage:"",opacity:0.6};lightbox.getBaseUrl=function(){if(!this.baseUrl){this.setBaseUrl();}
return this.baseUrl;};lightbox.getColour=function(){if(!this.colour){this.setColour();}
return this.colour;};lightbox.getCurrent=function(){return this.current?this.current:0;};lightbox.getCloseImage=function(){if(!this.closeImage){this.setCloseImage();}
return this.closeImage;};lightbox.getImageList=function(){return this.imageList;};lightbox.getLightboxClass=function(){if(!this.lightboxClass){this.setLightboxClass();}
return this.lightboxClass;};lightbox.getLoadingImage=function(){if(!this.loadingImage){this.setLoadingImage();}
return this.loadingImage;};lightbox.getNextImage=function(){if(!this.nextImage){this.setNextImage();}
return this.nextImage;};lightbox.getPreviousImage=function(){if(!this.previousImage){this.setPreviousImage();}
return this.previousImage;};lightbox.getOpacity=function(){if(!this.opacity){this.setOpacity();}
return this.opacity;};lightbox.setBaseUrl=function(baseUri){if(!baseUri){baseUri=location.protocol+'//'+location.host+'/';}
this.baseUrl=baseUri;};lightbox.setColour=function(color){if(!color){color="#000";}
this.colour=color;};lightbox.setCurrent=function(curr){this.current=parseInt(curr);};lightbox.setCloseImage=function(img){if(!img){img=this.getBaseUrl()+"images/new/photo-close.png";}
var close=document.createElement("IMG");close.setAttribute("id","lightboxCloseImage");close.setAttribute("src",img);close.setAttribute("style","cursor:pointer;height:63px;position:absolute;right:5px;top:5px;width:63px");close.onclick=function(){lightbox.hide();};this.closeImage=close;};lightbox.setImageList=function(imageList){this.imageList=imageList;};lightbox.setLightboxClass=function(className){if(!className){className="lightbox";}
this.lightboxClass=className;};lightbox.setLoadingImage=function(image){if(!image){image=this.getBaseUrl()+"images/new/loading.png";}
var img=document.createElement("IMG"),left,size=this.getPageSize(),top;img.setAttribute("id","lightboxLoadingImage");img.setAttribute("src",image);left=parseInt((size[2]-16)/2);top=parseInt((size[3]-16)/2);img.setAttribute("style","height:16px;left:"+left+"px;position:absolute;top:"+top+"px;width:16px");this.loadingImage=img;};lightbox.setNextImage=function(image){if(!image){image=this.getBaseUrl()+"images/new/photo-next.png";}
var img=document.createElement("IMG"),size=this.getPageSize();img.setAttribute("id","lightboxNextImage");img.setAttribute("src",image);img.setAttribute("style","height:63px;width:63px");if(this.getCurrent()<(this.getImageList().length-1)){img.style.cursor="pointer";img.onclick=function(){lightbox.displayItem(lightbox.getCurrent()+1);};}
this.nextImage=img;};lightbox.setPreviousImage=function(image){if(!image){image=this.getBaseUrl()+"images/new/photo-previous.png";}
var img=document.createElement("IMG"),size=this.getPageSize(),left;img.setAttribute("id","lightboxPreviousImage");img.setAttribute("src",image);left=parseInt(((size[2])/2)-63);img.setAttribute("style","height:63px;width:63px");if(this.getCurrent()>0){img.style.cursor="pointer";img.onclick=function(){lightbox.displayItem(lightbox.getCurrent()-1);};}
this.previousImage=img;};lightbox.setOpacity=function(opacity){if(!opacity){opacity=0.6;}
this.opacity=opacity;};lightbox.bindKeyPress=function(e){if(e==null){keycode=window.event.keyCode;}else{keycode=e.which?e.which:e.keyCode;}
if(keycode==27||keycode==88){lightbox.hide();}
if(keycode==37){lightbox.displayItem(lightbox.getCurrent()-1);}
if(keycode==39){lightbox.displayItem(lightbox.getCurrent()+1);}};lightbox.displayItem=function(arrayElem){var list=this.getImageList();if(arrayElem<0||arrayElem>this.getImageList().length||typeof list[arrayElem]==undefined){return false;}
if(!list[arrayElem]){alert("I'm sorry, but this gallery hasn't got any other images to display");return false;}
this.setCurrent(arrayElem);document.getElementById("lightBoxImage").style.display="none";document.getElementById("lightboxLoadingImage").style.display="";if(document.getElementById("lightboxRelativeWrapper")){if(document.getElementById("lightboxNextImage")){document.getElementById("lightboxNextImage").parentNode.removeChild(document.getElementById("lightboxNextImage"));}
if(document.getElementById("lightboxPreviousImage")){document.getElementById("lightboxPreviousImage").parentNode.removeChild(document.getElementById("lightboxPreviousImage"));}
this.setNextImage();this.setPreviousImage();document.getElementById("nextPreviousContainer").appendChild(this.getPreviousImage());document.getElementById("nextPreviousContainer").appendChild(this.getNextImage());}
document.getElementById("lightBoxImage").setAttribute("src",list[arrayElem].toString().replace(/\"/g,""));};lightbox.doOnLoad=function(elem){document.getElementById("lightboxLoadingImage").style.display="none";var size=this.getPageSize(),top;if(elem.height>size[3]){elem.style.height=(size[3]-50)+"px";}else{elem.style.height=elem.height+"px";}
top=parseInt((size[3]-parseInt(elem.style.height))/2);elem.style.paddingTop=top?top+"px":"0px";elem.style.display="";elem.style.margin="auto";};lightbox.getPageScroll=function(){var yScroll;if(self.pageYOffset){yScroll=self.pageYOffset;}else if(document.documentElement&&document.documentElement.scrollTop){yScroll=document.documentElement.scrollTop;}else if(document.body){yScroll=document.body.scrollTop;}
arrayPageScroll=['',yScroll];return arrayPageScroll;};lightbox.getPageSize=function(){var xScroll,yScroll,windowWidth,windowHeight;if(window.innerHeight&&window.scrollMaxY){xScroll=document.body.scrollWidth;yScroll=window.innerHeight+window.scrollMaxY;}else if(document.body.scrollHeight>document.body.offsetHeight){xScroll=document.body.scrollWidth;yScroll=document.body.scrollHeight;}else{xScroll=document.body.offsetWidth;yScroll=document.body.offsetHeight;}
if(self.innerHeight){windowWidth=self.innerWidth;windowHeight=self.innerHeight;}else if(document.documentElement&&document.documentElement.clientHeight){windowWidth=document.documentElement.clientWidth;windowHeight=document.documentElement.clientHeight;}else if(document.body){windowWidth=document.body.clientWidth;windowHeight=document.body.clientHeight;}
if(yScroll<windowHeight){pageHeight=windowHeight;}else{pageHeight=yScroll;}
if(xScroll<windowWidth){pageWidth=windowWidth;}else{pageWidth=xScroll;}
arrayPageSize=[pageWidth,pageHeight,windowWidth,windowHeight];return arrayPageSize;};lightbox.handleScroll=function(e){var scroll=lightbox.getPageScroll(),scrollBy;if(document.getElementById("lightBoxWrapper")){if(parseInt(document.getElementById("lightBoxWrapper").style.top)<scroll[1]){scrollBy=window.setInterval(function(){if(!document.getElementById("lightBoxWrapper")){return false;}
document.getElementById("lightBoxWrapper").style.top=(parseInt(document.getElementById("lightBoxWrapper").style.top)+1)+"px";if(parseInt(document.getElementById("lightBoxWrapper").style.top)==scroll[1]){window.clearInterval(scrollBy);}},10);}else{scrollBy=window.setInterval(function(){if(!document.getElementById("lightBoxWrapper")){return false;}
document.getElementById("lightBoxWrapper").style.top=(parseInt(document.getElementById("lightBoxWrapper").style.top)-1)+"px";if(parseInt(document.getElementById("lightBoxWrapper").style.top)==scroll[1]){window.clearInterval(scrollBy);}},10);}}};lightbox.hide=function(){var parent=document.getElementById("lightBoxWrapper").parentNode;parent.removeChild(document.getElementById("lightBoxWrapper"));};lightbox.search=function(){var i,images=document.getElementsByTagName("IMG"),parent;for(i=0;i<images.length;i++){if(images[i].className!=this.getLightboxClass()){continue;}
parent=images[i].parentNode;this.imageList.push('"'+parent+'"');parent.setAttribute("lightboxImage",parent.getAttribute("href"));parent.setAttribute("href","#");}};lightbox.show=function(elem){var imgToLoad=elem.getAttribute("lightboxImage"),i,img=document.createElement("IMG"),opaque=document.createElement("DIV"),scroll=this.getPageScroll(),size=this.getPageSize(),wrapper=document.createElement("DIV"),wrapper2=document.createElement("DIV"),wrapper3=document.createElement("DIV");if(!imgToLoad){imgToLoad=elem.href;}
if(!imgToLoad){return false;}
wrapper.setAttribute("id","lightBoxWrapper");wrapper.setAttribute("style","background-color:transparent;height:"+size[3]+"px;left:0px;position:absolute;top:"+(scroll[1]?scroll[1]:0)+"px;width:100%;z-index:35000;");wrapper2.setAttribute("id","lightboxRelativeWrapper");wrapper2.setAttribute("style","height:"+size[3]+"px;position:relative;width:100%");wrapper.appendChild(wrapper2);wrapper2.appendChild(this.getCloseImage());wrapper2.appendChild(this.getLoadingImage());opaque.setAttribute("style","background-color:"+this.getColour()+";height:"+size[3]+"px;left:0px;position:absolute;opacity:"+this.getOpacity()+";top:0px;width:100%;z-index:-1;");wrapper2.appendChild(opaque);wrapper3.setAttribute("id","nextPreviousContainer");wrapper3.setAttribute("style","bottom:5px;left:0px;position:absolute;text-align:center;width:100%;z-index:12500;");wrapper2.appendChild(wrapper3);if(this.getImageList()&&this.getImageList().length>0){for(i=0;i<this.getImageList().length;i++){if(lightbox.getBaseUrl()+"Maxine/"+imgToLoad==this.getImageList()[i].toString().replace(/\"/g,"")){this.setCurrent(i);break;}else if(this.urldecode(imgToLoad)==lightbox.getBaseUrl()+"Maxine/"+this.getImageList()[i].toString().replace(/\"/g,"")){this.setCurrent(i);break;}}
wrapper3.appendChild(this.getPreviousImage());wrapper3.appendChild(this.getNextImage());}
wrapper2.appendChild(img);img.onload=function(){lightbox.doOnLoad(this);};img.setAttribute("id","lightBoxImage");img.setAttribute("src",imgToLoad);img.setAttribute("style","display:none;");document.body.appendChild(wrapper);return false;};lightbox.urldecode=function(str){return decodeURIComponent((str+'').replace(/\+/g,'%20'));};window.onkeydown=lightbox.bindKeyPress;window.onscroll=lightbox.handleScroll;