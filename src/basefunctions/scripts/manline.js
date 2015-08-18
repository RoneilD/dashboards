var baseUrl = location.protocol+'//'+location.host+'/'; /* base url */
/* calendar variables */
var datePickerDivID = 'datepicker', iFrameDivID = 'datepickeriframe';
var dayArrayShort = new Array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
var dayArrayMed = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
var dayArrayLong = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthArrayShort = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
var monthArrayMed = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
var monthArrayLong = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
var defaultDateSeparator = '/';			// common values would be '/' or '.'
var defaultDateFormat = 'dmy';				// valid values are 'mdy', 'dmy', and 'ymd'
var dateSeparator = defaultDateSeparator, dateFormat = defaultDateFormat;
/* Cavtitle variables */
var g_iCavTimer, g_CarEle = null, g_iCavDivLeft, g_iCavDivTop;

function submitenter(myfield,e) {
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	
	if (keycode == 13){
		myfield.form.submit();
	}
}


function buttonJump(id) {
	document.getElementById(id+'button').src = baseUrl+'images/mainbuttons/'+id+'over.png';
}
function buttonStandard(id) {document.getElementById(id+'button').src = baseUrl+'images/mainbuttons/'+id+'.png';}
function buttonPress(id) {document.getElementById(id+'button').src = baseUrl+'images/mainbuttons/'+id+'down.png';}

function saveDown(id) {
	document.getElementById('savebutton'+id).src = baseUrl+'/images/saveclickableover.png';
}

function saveUp(id) {
	document.getElementById('savebutton'+id).src = baseUrl+'/images/saveclickable.png';
}

function savePress(id) {
	document.getElementById('savebutton'+id).src = baseUrl+'/images/saveclickabledown.png';
}

function saveRelease(id) {
	document.getElementById('savebutton'+id).src = baseUrl+'/images/saveclickableover.png';
}

function deleteRow(rowno) {
	document.getElementById('actionrow'+rowno).style.display	= 'none';
	document.getElementById('deletecontrol'+rowno).value = 1;
}

function goTo(url){window.location.href = url; return false;}

function postForm(elmId, act) {
	if (act) {document.getElementById(elmId).action = act;}
	document.getElementById(elmId).submit();
}
function toggle(id, tag) {
	var elem = tag ? document.getElementsByTagName(id) : document.getElementById(id);
	if (tag) {
		for (var i=0; i<elem.length; i++) {elem[i].style.display = elem[i].style.display == '' ? 'none' : '';}
	} else {
		elem.style.display = elem.style.display == '' ? 'none' : ''; 
	}
}
function wait(msecs) {
	var start = new Date().getTime();
	var cur = start;
	while(cur - start < msecs) {
		cur = new Date().getTime();
	}
}

function decimalRound(number, points) {
	var precision	= Math.pow(10,points);
	var result		= Math.round(number * precision) / precision;
	return result;
}

// calendar script {
	function displayDatePicker(dateFieldName, displayBelowThisObject, dtFormat, dtSep) {
		wait(500);
		var targetDateField = document.getElementsByName (dateFieldName).item(0);
		
		// if we weren't told what node to display the datepicker beneath, just display it
		// beneath the date field we're updating
		if (!displayBelowThisObject)
			displayBelowThisObject = targetDateField;
		
		// if a date separator character was given, update the dateSeparator variable
		if (dtSep)
			dateSeparator = dtSep;
		else
			dateSeparator = defaultDateSeparator;
		
		// if a date format was given, update the dateFormat variable
		if (dtFormat)
			dateFormat = dtFormat;
		else
			dateFormat = defaultDateFormat;
		
		var x = 90;
		var y = 45;
		
		//var x = displayBelowThisObject.offsetLeft;
		//var y = displayBelowThisObject.offsetTop + displayBelowThisObject.offsetHeight ;
		
		// deal with elements inside tables and such
		var parent = displayBelowThisObject;
		while (parent.offsetParent) {
			parent = parent.offsetParent;
			x += parent.offsetLeft;
			y += parent.offsetTop ;
		}
		drawDatePicker(targetDateField, x, y);
	}
	
	function drawDatePicker(targetDateField, x, y) {
		var dt = getFieldDate(targetDateField.value );
		
		// the datepicker table will be drawn inside of a <div> with an ID defined by the
		// global datePickerDivID variable. If such a div doesn't yet exist on the HTML
		// document we're working with, add one.
		if (!document.getElementById(datePickerDivID)) {
			// don't use innerHTML to update the body, because it can cause global variables
			// that are currently pointing to objects on the page to have bad references
			//document.body.innerHTML += '<div id='' + datePickerDivID + '' class='dpDiv'></div>';
			var newNode = document.createElement('div');
			newNode.setAttribute('id', datePickerDivID);
			newNode.setAttribute('class', 'dpDiv');
			newNode.setAttribute('style', 'visibility: hidden;');
			document.body.appendChild(newNode);
		}
		// move the datepicker div to the proper x,y coordinate and toggle the visiblity
		var pickerDiv = document.getElementById(datePickerDivID);
		pickerDiv.style.position = 'absolute';
		pickerDiv.style.left = x + 'px';
		pickerDiv.style.top = y + 'px';
		pickerDiv.style.visibility = (pickerDiv.style.visibility == 'visible' ? 'hidden' : 'visible');
		pickerDiv.style.display = (pickerDiv.style.display == 'block' ? 'none' : 'block');
		pickerDiv.style.zIndex = 10000;
		
		// draw the datepicker table
		refreshDatePicker(targetDateField.name, dt.getFullYear(), dt.getMonth(), dt.getDate());
	}
	
	function refreshDatePicker(dateFieldName, year, month, day) {
		// if no arguments are passed, use today's date; otherwise, month and year
		// are required (if a day is passed, it will be highlighted later)
		var thisDay = new Date();
		if ((month >= 0) && (year > 0)) {
			thisDay = new Date(year, month, 1);
		} else {
			day = thisDay.getDate();
			thisDay.setDate(1);
		}
		
		var TABLE = '<table cols=7 class="dpTable">';
		var xTABLE = '</table>';
		var TR = '<tr class="dpTR">';
		var TR_title = '<tr class="dpTitleTR">';
		var TR_days = '<tr class="dpDayTR">';
		var TR_todaybutton = '<tr class="dpTodayButtonTR">';
		var xTR = '</tr>';
		
		var TD = '<td class="dpTD"';    // leave this tag open, because we'll be adding an onClick event
		var TD_title = '<td colspan=5 class="dpTitleTD">';
		var TD_buttons = '<td class="dpButtonTD">';
		
		var TD_todaybutton = '<td colspan=7 class="dpTodayButtonTD">';
		var TD_days = '<td class="dpDayTD">';
		var TD_selected = '<td class="dpDayHighlightTD"';     // leave this tag open, because we'll be adding an onClick event
		var xTD = '</td>';
		var DIV_title = '<div class="dpTitleText">';
		var DIV_selected = '<div class="dpDayHighlight">';
		var xDIV = '</div>';
		TD_onclick = ' onclick=\'alert("10");\'>';
		
		
		// start generating the code for the calendar table
		var html = TABLE;
		
		// this is the title bar, which displays the month and the buttons to
		// go back to a previous month or forward to the next month
		html += TR_title;
		html += TD_buttons + getButtonCode(dateFieldName, thisDay, -1, '&lt;') + xTD;
		
		html += TD_title + DIV_title + monthArrayLong[ thisDay.getMonth()] + ' ' + thisDay.getFullYear() + xDIV + xTD;
		html += TD_buttons + getButtonCode(dateFieldName, thisDay, 1, '&gt;') + xTD;
		html += xTR;
		
		
		// this is the row that indicates which day of the week we're on
		html += TR_days;
		for(i = 0; i < dayArrayShort.length; i++)
			html += TD_days + dayArrayShort[i] + xTD;
		html += xTR;
		
		
		
		// now we'll start populating the table with days of the month
		html += TR;
		// first, the leading blanks
		for (i = 0; i < thisDay.getDay(); i++)
			html += TD + '&nbsp;' + xTD;
		// now, the days of the month
		do {
			TD_onclick = ' onclick=\'events.cancelBubble(event);updateDateField("' + dateFieldName + '", "' + getDateString(thisDay) + '");\'>';
			dayNum = thisDay.getDate();
			
			if (dayNum == day)
				html += TD_selected + TD_onclick + DIV_selected + dayNum + xDIV + xTD;
			else
				html += TD + TD_onclick + dayNum + xTD;
			
			// if this is a Saturday, start a new row
			if (thisDay.getDay() == 6)
				html += xTR + TR;
			
			// increment the day
			thisDay.setDate(thisDay.getDate() + 1);
		} while (thisDay.getDate() > 1);
		
		// fill in any trailing blanks
		if (thisDay.getDay() > 0) {
			for (i = 6; i > thisDay.getDay(); i--)
				html += TD + '&nbsp;' + xTD;
		}
		html += xTR;
		
		// add a button to allow the user to easily return to today, or close the calendar
		var today = new Date();
		var todayString = 'Today is ' + dayArrayMed[today.getDay()] + ', ' + monthArrayMed[ today.getMonth()] + ' ' + today.getDate();
		
		html += TR_todaybutton + TD_todaybutton;
		html += '<button class="dpTodayButton" onClick=\'events.cancelBubble(event);refreshDatePicker("' + dateFieldName + '");\'>today</button>';
		html += '<button class="dpTodayButton" onClick=\'events.cancelBubble(event);updateDateField("' + dateFieldName + '", "0");\'>clear</button>';
		html += '<button class="dpTodayButton" onClick=\'events.cancelBubble(event);updateDateField("' + dateFieldName + '");\'>close</button>';
		html += xTD + xTR;
		
		
		// and finally, close the table
		html += xTABLE;
		
		document.getElementById(datePickerDivID).innerHTML = html;
	}
	
	function getButtonCode(dateFieldName, dateVal, adjust, label) {
		var newMonth = (dateVal.getMonth () + adjust) % 12;
		var newYear = dateVal.getFullYear() + parseInt((dateVal.getMonth() + adjust) / 12);
		if (newMonth < 0) {
			newMonth += 12;
			newYear += -1;
		}
		return '<button class="dpButton" onClick=\'events.cancelBubble(event);refreshDatePicker("' + dateFieldName + '", ' + newYear + ', ' + newMonth + ');\'>' + label + '</button>';
	}
	
	function getFieldDate(dateString) {
		var dateVal, dArray, d, m, y;
		try {
			dArray = splitDateString(dateString);
			if (dArray) {
				switch (dateFormat) {
				case 'dmy' :
					d = parseInt(dArray[0], 10);
					m = parseInt(dArray[1], 10) - 1;
					y = parseInt(dArray[2], 10);
					break;
				case 'ymd' :
					d = parseInt(dArray[2], 10);
					m = parseInt(dArray[1], 10) - 1;
					y = parseInt(dArray[0], 10);
					break;
				case 'mdy' :
					default :
					d = parseInt(dArray[1], 10);
					m = parseInt(dArray[0], 10) - 1;
					y = parseInt(dArray[2], 10);
					break;
				}
				dateVal = new Date(y, m, d);
			} else if (dateString) {
				dateVal = new Date(dateString);
			} else {
				dateVal = new Date();
			}
		} catch(e) {
			dateVal = new Date();
		}
		
		return dateVal;
	}
	
	function getDateString(dateVal) {
		var dayString = '00' + dateVal.getDate();
		var monthString = '00' + (dateVal.getMonth()+1);
		dayString = dayString.substring(dayString.length - 2);
		monthString = monthString.substring(monthString.length - 2);
		
		switch (dateFormat) {
		case 'dmy' :
			return dayString + dateSeparator + monthString + dateSeparator + dateVal.getFullYear();
		case 'ymd' :
			return dateVal.getFullYear() + dateSeparator + monthString + dateSeparator + dayString;
		case 'mdy' :
			default :
			return monthString + dateSeparator + dayString + dateSeparator + dateVal.getFullYear();
		}
	}
	
	function updateDateField(dateFieldName, dateString) {
		var targetDateField = document.getElementsByName (dateFieldName).item(0);
		
		if(dateString	== "0") {
			targetDateField.value = null;
		} else if (dateString) {
			targetDateField.value = dateString;
		}
		
		var pickerDiv = document.getElementById(datePickerDivID);
		pickerDiv.style.visibility = 'hidden';
		pickerDiv.style.display = 'none';
		
		targetDateField.focus();
		
		// after the datepicker has closed, optionally run a user-defined function called
		// datePickerClosed, passing the field that was just updated as a parameter
		// (note that this will only run if the user actually selected a date from the datepicker)
		if ((dateString) && (typeof(datePickerClosed) == 'function'))
			datePickerClosed(targetDateField);
	}
// end calendar script }

// cavtitles script {
	function setCavTimer(evt) {
		var e = (window.event) ? window.event : evt;
		var src = (e.srcElement) ? e.srcElement : e.target;
		
		g_iCavDivLeft = e.clientX + 25 + document.body.scrollLeft;
		g_iCavDivTop = e.clientY - 5 + document.body.scrollTop;
		
		window.clearTimeout(g_iCavTimer);
		
		g_iCavTimer = window.setTimeout('ShowCavTitle()', 500);
		g_CarEle = src;
	}
	
	function ShowCavTitle() {
		for (var i = g_CarEle.attributes.length - 1; i >= 0; i--) {
			if (g_CarEle.attributes[i].name.toUpperCase() == 'CAVTITLE') {
				var div = document.getElementById('cavTitleDiv');
				if (div)
					break;
				
				div = document.createElement('div');
				div.id = 'cavTitleDiv';
				div.style.position = 'absolute';
				div.style.visibility = 'visible';
				div.style.zIndex = 1000;
				div.style.backgroundColor = 'white';
				div.style.border = '1px solid black';
				
				var sLeft = new String();
				sLeft = g_iCavDivLeft.toString();
				sLeft += 'px';
				div.style.left = sLeft;
				var sTop = new String();
				sTop = g_iCavDivTop.toString();
				sTop += 'px';
				div.style.top = sTop;
				
				var titletext	= g_CarEle.attributes[i].value;
				div.innerHTML = titletext;
				document.body.appendChild(div);
				
				var iWidth = div.scrollWidth + 10;
				var sWidth = new String();
				sWidth = iWidth.toString();
				sWidth += 'px';
				div.style.width = sWidth;
				
				break;
			}
		}
	}
	
	function CancelCavTimer(evt) {
		var e = (window.event) ? window.event : evt;
		var src = (e.srcElement) ? e.srcElement : e.target;
		
		var div = document.getElementById('cavTitleDiv');
		if (div)
			document.body.removeChild(div);
		
		window.clearTimeout(g_iCavTimer);
		g_CarEle = null;
	}
// end cavtitles script }

/** Object::profiles
 * show|hide user profiles
 */
var profiles = {};
profiles.show = function(elem) {
	var id = 'profileData['+elem.id.match('[0-9]{1,}')+']';
	document.getElementById(id).style.display = '';
	while(elem.firstChild) {elem.removeChild(elem.firstChild);}
	elem.appendChild(document.createTextNode('less'));
	elem.addEventListener("click", function() {profiles.hide(this);}, false);
	elem.style.zIndex = 11;
	document.getElementById(id).style.zIndex = 10;
};
profiles.hide = function(elem) {
	var id = 'profileData['+elem.id.match('[0-9]{1,}')+']';
	document.getElementById(id).style.display = 'none';
	while(elem.firstChild) {elem.removeChild(elem.firstChild);}
	elem.appendChild(document.createTextNode('more'));
	elem.addEventListener("click", function() {profiles.show(this);}, false);
	elem.style.zIndex = 'inherit';
	document.getElementById(id).style.zIndex = 'inherit';
};
/** Object::events
  * event model
  */
var events = {};
events.cancelBubble = function(e) {
        if (!e) {var e = window.event;}
        if (!e) {return false;}
        e.cancelBubble = true;
        if (e.stopPropagation) {e.stopPropagation();}
};
/** Object::forms
  * forms js
  */
var formManipulators = {};
formManipulators.checkCheckBoxes = function (formId, elem) {
        var i, inputs = document.getElementById(formId).getElementsByTagName("INPUT");
        for (i=0;i<inputs.length;i++) {
                if (inputs[i].type != "checkbox") {continue;}
                inputs[i].checked = elem.checked; 
        }
};
formManipulators.validateEmailAddress = function(elem) {
        if (!elem.value.toString().match(/.*\@.*/)) {
                alert("I'm sorry but the email address supplied is not a vaild email address. Format should be somethig like this: bob@manlinegroup.com");
                return false;
        }
};
/** FUNCTION::clearAllElements(elem)
  * @author Feighen Oosterbroek
  * @author feighen@manlinegroup.com
  * @param string|object elem string element id or object element by reference
  * @example clearAllElements(this);
  * @example clearAllElements('searchForm');
  */
function clearAllElements(elem) {
        switch (typeof elem) {
        case "object": // element was passed
                while (elem.firstChild) {
                        elem.removeChild(elem.firstChild);
                }
                break;
        case "string": // element id was passed
                if (!document.getElementById(elem)) {return false;}
                while (document.getElementById(elem).firstChild) {
                        document.getElementById(elem).removeChild(document.getElementById(elem).firstChild);
                }
                break;
        }
}
