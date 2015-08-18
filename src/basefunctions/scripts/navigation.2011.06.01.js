var navigation = {
        className: "menu"
};
//: Accessors
navigation.getClassName = function () {
        return this.className;
};
navigation.setClassName = function (name) {
        this.className = name;
};

//: Functions
navigation.hideAll = function () {
        var i, ul = document.getElementsByTagName("UL");
        for (i=0;i<ul.length;i++) {
                if (ul[i].className != navigation.getClassName()) {continue;}
                navigation.hide(ul[i]);
        }
};
navigation.hide = function (elem, assoc) {
        var i, li = elem.getElementsByTagName("LI");
        for (i=0; i<li.length; i++) {
                if (i > 0) {
                        li[i].style.display = "none";
                }
        }
};
navigation.hideSubMenu = function (elem, tag) {
        var i, taggedItem = elem.getElementsByTagName(tag);
        for (i=0; i<taggedItem.length; i++) {
                taggedItem[i].setAttribute("style", "display:none;");
                break;
        }
};
navigation.toggle = function (elem, assoc) {
        var i, li = elem.getElementsByTagName("LI");
        for (i=0; i<li.length; i++) {
                if (i > 0) {
                        li[i].style.display = (li[i].style.display == "" ? "none" : "");
                }
        }
};
navigation.showSubMenu = function (elem, tag) {
        var i, taggedItem = elem.getElementsByTagName(tag);
        for (i=0; i<taggedItem.length; i++) {
                taggedItem[i].setAttribute("style", "display:inline-block;list-style-type:none;margin-left:150px;margin-top:-22px;width:190px;");
                break;
        }
};