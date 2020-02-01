<!------------ Date Picker : Start Common Code - DO NOT MODIFY ------------>
/* Author 	: Susantha Soysa, Sri Lanka.
   Contact	: susanthas@hotmail.com/ susanthas@gmail.com
   Created Date	: 11/Feb/2006
   Last Modified: 02/Apr/2006
   Copyright	: The code is freely distributable, but appriciate if the Author and Contact email is included in the
		  final source. The author will be delited with a short thanking email.
   Warranty	: This code is provided "AS IS" and the author bares no warranty what so ever.
   Description	: This Javascript DatePicker was created to overcome few problems with the existing calendars
		  available in the internet.
	        	1. Difficulty to deal with ComboBoxes
			2. Slow navigation.
			3. Difficulty to incorporate with the own code/ tracking events etc.
		  This component has a common calendar building code which will be shared with other caledar controls. Each 
		  control can have a different set of properties to customize the behavior.
			id 		- string html id of the value control
			overlayControls - string, comma seperated controls ids to hide when the calender is popped 
					  up. Usually combo boxes
			month 		- integer, initial display month
			year 		- integer, initial display year
			canvas 		- string html div id, which will be used to render the calendar
			format 		- string, date format: supports d,dd,m,mm,mmm,yy,yy formats
			anchor 		- string, html anchor id which invokes the popup

		  Also, when it is required to localize the display modify the section marked as, 
		  "Start Customizable data ( for internationalization/ Initial month )"

		  The Calendar was tested in IE6 and Firefox browsers.

   HOW TO USE	: Use the common javascript/ stylesheet to initialize the DatePickers. Add a text, div and an HTML Anchor elements
		  to the page to pick the date. Assign html id's as necessary. Make sure to add a <br> tag before adding div tag 
		  ie: to make the calendar appear below the text box. 
		  
 		  At the end of the page create the javascript objects with appropriate properties as follows. 
		  eg : 
			var dpStartDate = new DatePicker(); 		    // create a new DatePicker object
			dpStartDate.id = "Age"; 			    // value control id in the html page
			dpStartDate.overlayControls = "Sex2,DynaSubmit2";   // comma seperated html elemet ids.
			dpStartDate.month = 5; 			            // start month of the calendar
			dpStartDate.year = 2007;			    // start year of the calendar
			dpStartDate.canvas = "dtDivAge";		    // HTML div id which will be used to render the calendar
			dpStartDate.format = "dd-mmm-yyyy";		    // date format
			dpStartDate.anchor = "ancAge";		            // HTML anchor id which invokes the popup.

			
			dpStartDate.initialize(); 		            // finally call the initalize method of the created object
	
*/ 
// ---------- Start Customizable data for Calendar ( for internationalization/ Curent Date ) ----------
var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni","Juli", "Augustus", "September", "Oktober", "November", "Desember"];
var daysOfWeek = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
var todayCaption = "Today";

var currDate = (new Date()).getDate(); // Assign client date. To use server date, set the server date here using server script.
var weekStartDay = 1; // To Start from - Sunday : 0, Monday : 1 etc 
var nonWorkingDays = "6,0"; // Saturdays and Sundays are non-working days

// ---------- End Customizable data ----------
var monthDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
var leapMonthDays = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
var activeDp = null;
var mousePressed = false;
var calenderBuilding = false;

// capture the mouse leave event to hide the popup
document.onmouseup = documentMouseup;
//
function documentMouseup(){ if (mousePressed || calenderBuiling) { mousePressed = false; return false;}; tryBlur();}
function DatePicker(){this.initialize = initialize_object;this.canBlur = true;function initialize_object(){if (this.canvas != null){this.canvasElement = getElement(this.canvas);}if (this.anchor != null){this.anchorElement = getElement(this.anchor);this.anchorElement.onclick = DatePicker_AnchorEvents;this.anchorElement.onmouseover = DatePicker_AnchorEvents;this.anchorElement.parent = this;}}function DatePicker_AnchorEvents(){if (activeDp != this.parent)doShow(this.parent);}}
function getElement(id){var result = document.getElementById(id);return result == null ? document.all[id] : result;}
function setDate(dp, day){var date = dp.format;if (day == 0){dp.year = currYear;dp.month = currMonth;day = currDate;} if (date.indexOf('yyyy') >= 0) date = date.replace('yyyy',dp.year.toString());else date = date.replace('yy',dp.year.toString().substring(2,4));if (date.indexOf('mmm') >= 0) date = date.replace('mmm', months[dp.month-1].substring(0,3));else if (date.indexOf('mm') >= 0){ var monthString =  dp.month.toString();monthString = monthString.length == 1 ? '0' + monthString : monthString; date = date.replace('mm', monthString);}else date = date.replace('m', dp.month.toString());	if (date.indexOf('dd') >= 0){var dateString = day.toString();dateString = dateString.length == 1 ? '0' + dateString : dateString;date = date.replace('dd',dateString); }else date = date.replace('d',day);getElement(dp.id).value = date;dp.canBlur = true;doHide(dp);}
function tryFocus(dp){ dp.canBlur = false; }
function tryBlur(){if (activeDp != null) activeDp.canBlur = true;setTimeout("doHide();",50);}
function movePrev(isMonth){var dp = activeDp;dp.canBlur = false;if (isMonth){if (dp.month > 1){dp.month --;}else{dp.month = 12;dp.year --;}}else dp.year--;buildCalendar(dp);}
function moveNext(isMonth){var dp = activeDp;dp.canBlur = false;if (isMonth){if (dp.month < 12){dp.month ++;}else{dp.month = 1;dp.year ++;}}else dp.year++;buildCalendar(dp);}
function doShow(dp){if (activeDp == dp){activeDp.canBlur = true;doHide();}else{if (activeDp != null){activeDp.canBlur = true;doHide();}if (dp.overlayControls != null){var cons = dp.overlayControls.split(",");for(i=0; i < cons.length; i++)getElement(cons[i]).style.visibility = "hidden";}dp.canBlur = false;dp.canvasElement.style.visibility = "visible";dp.canvasElement.style.zIndex = 1;activeDp = dp;buildCalendar(dp)}}
function doHide(){if (activeDp != null && activeDp.canBlur) {activeDp.canvasElement.style.visibility = "hidden";activeDp.canvasElement.style.zIndex = -1;if (activeDp.overlayControls != null){var cons = activeDp.overlayControls.split(",");for(i=0; i < cons.length; i++)getElement(cons[i]).style.visibility = "visible";}activeDp = null;}}
function isDateInRange(from, to, current){ var result = false; if (from == null && to == null) result = true; else if ((from != null && current < from) || (to != null && current > to)) result = false; else result = true; return result;}
function getDayNameCell(dayIndex){var dayClass = "dayNames"; if (!isWorkingDay(dayIndex)) dayClass = "nwDayNames"; return "<td align='center' class='" + dayClass + "' width=21>" + daysOfWeek[dayIndex].substring(0,2) + "</td>";}
function isWorkingDay(dayIndex){return nonWorkingDays.indexOf(dayIndex) < 0;}
function getDayClass(currentClass, dayIndex){var dateClass = currentClass; if (!isWorkingDay(dayIndex)) dateClass = "nwday";  return dateClass;}
function moveAuto(direction){var delay = 50; switch(direction){ case 'yearPrev': movePrev(false); break; case 'yearNext': moveNext(false); break; case 'monthPrev': delay = 300; movePrev(true); break; case 'monthNext': delay = 300; moveNext(true); break;} if (mousePressed) setTimeout('moveAuto("' + direction + '")',delay);}
function startAuto(direction){ mousePressed = true; setTimeout('moveAuto("' + direction + '")',300);}
function stopAuto(){mousePressed = false;}
function buildCalendar(dp){
  calenderBuiling = true;
  var m = dp.month - 1;var isLeap = true; if (dp.year % 4 != 0) isLeap = false; else if (dp.year % 400 == 0) isLeap = true; else if (dp.year % 100 == 0) isLeap = false;
  var days = monthDays[m];
  if (isLeap) days = leapMonthDays[m];
  var dDate = new Date();
  dDate.setDate(1);
  dDate.setMonth(m);
  dDate.setFullYear(dp.year);
  var firstDay = dDate.getDay();
  var calCode = "<table border=0 cellspacing=0 cellpadding=2 width='100%' height='100%' class='calbg' onclick='tryFocus(dp" + dp.id + ");' onfocus='tryFocus(dp" + dp.id + ");'>"
  calCode += "<tr><td align='center'><a href='##' class='linknormal' onmousedown='startAuto(\"yearPrev\");' onfocus='tryFocus(dp" + dp.id + ");'>&lt;</a></td><td colspan=5 align='center' class='year'><b>"+ dp.year +"</b></td><td align='center'><a href='##' class='linknormal' onmousedown='startAuto(\"yearNext\");' onfocus='tryFocus(dp" + dp.id + ");'>&gt;</a></td></tr>";
  calCode += "<tr><td align='center'><a href='##' class='linknormal' onmousedown='startAuto(\"monthPrev\");' onfocus='tryFocus(dp" + dp.id + ");'>&lt;</a></td><td colspan=5 align='center' class='month'>"+ months[m] +"</td><td align='center'><a href='##' class='linknormal' onfocus='tryFocus(dp" + dp.id + ");' onmousedown='startAuto(\"monthNext\");'>&gt;</a></td></tr>";
  calCode += "<tr>";
  for (i = 0; i < 7; i++) // we have only 7 days to print
    if (i + weekStartDay < 7) // print first day names
    	calCode += getDayNameCell(i + weekStartDay)
    else
	calCode += getDayNameCell(i + weekStartDay-7); // print last day names

  calCode += "</tr>";
  colIndex = 0;

  var dayIndex = 0;
  var loopDate = 0;
  var displayDate = new Date();
  displayDate.setMonth(m);
  displayDate.setFullYear(dp.year);
  displayDate.setDate(1);

  firstDay -= weekStartDay;
  if (firstDay < 0) 
    firstDay = 7 - weekStartDay;
  for (i = -firstDay + 1; i < 36 - firstDay ; i++)
  {
    loopDate = 0;	

    if (colIndex >6)
    {
      colIndex = 0;
      calCode += "</tr><tr>";
    }
    dayIndex = colIndex + weekStartDay;
    if (colIndex + weekStartDay > 6)
	dayIndex = 7 - dayIndex;
    colIndex++;
    

    if (days - i >= 35 && i < 0){
      calCode += "<td align='right' class='" + getDayClass("day",dayIndex) + "'>";
      loopDate  = 35 + i;
    }
    else if (i < 1 || i > days){
      calCode += "<td>";
      loopDate = 0;	
    }
    else if (i == currDate && dp.month == currMonth && dp.year == currYear){ 
      calCode += "<td align='right' class='" + getDayClass("cday",dayIndex) + "'>";
      loopDate  = i;	

    }
    else if (i > 0) {
      calCode += "<td align='right' class='" + getDayClass("day",dayIndex) + "'>";
      loopDate  = i;
    }

    if (loopDate > 0)
      displayDate.setDate(loopDate);

    if (loopDate <= 0)
	calCode += "&nbsp;";
    else if (isDateInRange(dp.fromDate, dp.toDate, displayDate))
	calCode += "<a href='##' class='linkbold' onfocus='tryFocus(dp" + dp.id + ");' onclick='setDate(dp" + dp.id + "," + loopDate.toString() + ");'>"+ loopDate.toString() +"</a>"
    else
	calCode += loopDate.toString();

    calCode += "</td>";
  }
  calCode += "</tr>";
  calCode += "<tr><td align='left' colspan=7 class='today'>";
  var currentDate = new Date(dp.year.toString() + "/" + months[dp.month-1] + "/" + currDate.toString());
  if (isDateInRange(dp.fromDate, dp.toDate, currentDate))
  	calCode += "<a href='##' onfocus='tryFocus(dp" + dp.id + ");' onclick='setDate(dp" + dp.id + ",0);'>" + todayCaption + ": " + months[dp.month-1] + " " + currDate.toString() + ", " + dp.year.toString() + "</a>";
  else
  	calCode += todayCaption + ": " + months[dp.month-1] + " " + currDate.toString() + ", " + dp.year.toString();

  calCode += "</td></tr>";
  dp.canvasElement.innerHTML = calCode;
  calenderBuiling = false;
}
<!------------ Date Picker : End Common Code ---------->
