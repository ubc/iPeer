// Configuration Variables
var AjaxListActionOffsetX = -2;
var AjaxListActionOffsetY = -2;
var AjaxListActionMenuTextEnabledColor = "Black";
var AjaxListActionMenuTextDisabledColor = "LightGrey";
var AjaxListActionMenuTextSelectedColor = "Blue";
var AjaxListActionMenuColor = "LightYellow";
var AjaxListActionMenuSelected = "LightBlue";

var AjaxListTableRowSelectedBackgroundColor = "#F8F850";

// How long to wait after a user has finished typing to start a search
var searchAfterTypeDelay = 1100; //ms

var AjaxListActionNewWindowFeatures = "width=640, height=480";

var iconURL = "img/icons/";
var busyIconURL = "img/big-rotation.gif";

var BusyImageYOffset = 380;
var BusyImageXOffset = 70;


// It's best to keep the following as multiples of the lowest page count,
//  so that when the page-size-change Occurs, the
//  results are paged up in blocks. But this is not
//  required: the JS pagination code will figure out
//  the right page to jump to: the one that for sure
//  contains the first record on the present page,
var AvailablePageSizes = [15, 30, 90, 270];

// How many page numbers to show before we collapse the page list a bit.
// must be 6 or greater, no upper limit.
var maxPageNumbersToShow = 5;
// on average the current page number is in the middle with two pages on either side
var averageOffset = 2;

//Contants
var blackUpTriangleHTMLCode = "&#9650;";
var blackDownTriableHTMLCode = "&#9660;";

var controllerAjaxListFunctionName = "ajaxList";

var ID_COL = 0;     // ID of the column
var DESC_COL = 1;   // Description of the columns
var WIDTH_COL = 2;
var TYPE_COL = 3;   // Type of the column
var MAP_COL = 4;    // Map of column values
var ACTION_COL = 4; // Action for an action try column
var ICON_COL = 4;  // Icon for a link-type column

var ACTION_NAME = 0;
var ACTION_WARNING = 1;
var ACTION_RESTRICTIONS = 2;
var ACTION_CONTROLLER = 3;
var ACTION_URL_PARTS_START = 4;

var ListUsage =  "<div id='short_help'><h6>How to use Lists in iPeer:</h6>";
    ListUsage += "<ol><li><i>Left-Clicking</i> on a ";
    ListUsage += "link will take you to that item's display.</li>";
    ListUsage += "<li><i>Left-Clicking</i> the light yellow ";
    ListUsage += "background of an item will open the <b>pop-up menu.</b></li>";
    ListUsage += "<li>The <b>pop-up menu</b> displays all available actions for this item, ";
    ListUsage += "like 'View', 'Edit', 'Copy', 'Delete', etc...</li>";
    ListUsage += "<li><i>Right-Clicking</i> anywhere on a list will also open the <b>pop-up menu</b>.</li></ol></div>";

// Small Helper function library class
function AjaxListLibrary () {
}

AjaxListLibrary.prototype.createDelegate = function (object, method) {
    return function() {
        return method.apply(object, arguments);
    }
}

AjaxListLibrary.prototype.createDelegateWithParams = function (object, method, param1, param2) {
    return function() {
        // Copy the parameter to the newArguments array....
        //  since the arguments array has no push() function
        var newArguments = Array();
        for (i = 0; i < arguments.length; i++) {
            newArguments.push(arguments[i]);
        }

        // IE6 workaround
        if (newArguments.length  < 1) {
            newArguments.push("dummy event");
        }

        // Push the new argument into the newArguments array
       if (param1 !== undefined) {
            newArguments.push(param1);
       }

       if (param2 !== undefined) {
           newArguments.push(param2);
       }
        // And call the intended function with the old + new parameters
        return method.apply(object, newArguments);
    }
}

// Gets a cookie - from JavaScript, the Definitive Guide, 5th ed, Chap 19.2
AjaxListLibrary.prototype.getCookie = function(name) {
    // Read the cookie property. This returns all cookies for this document.
    var allcookies = document.cookie;
    // Look for the start of the cookie named "version"
    var pos = allcookies.indexOf(name + "=");
    // If we find a cookie by that name, extract and use its value
    if (pos != -1) {
        var start = pos + name.length + 1;              // Start of cookie value
        var end = allcookies.indexOf(";", start);       // End of cookie value
        if (end == -1) end = allcookies.length;
        var value = allcookies.substring(start, end);   // Extract the value
        return value
    } else {
        return null;
    }
}


// Sets a cookie
AjaxListLibrary.prototype.setCookie = function(name, value) {
    document.cookie = name + "=" + encodeURIComponent(value) + "; max-age=" + (60*60*24);
}


// Determing if an object exists in an array
// From: http://stackoverflow.com/questions/237104/javascript-array-containsobj
AjaxListLibrary.prototype.contains = function (a, obj){
    for(var i = 0; i < a.length; i++) {
        if(a[i] === obj){
            return true;
        }
    }
    return false;
}

// Set an objects opacity in a cross-browser sort of way.
AjaxListLibrary.prototype.setOpacity = function (obj, value) {
    if (Prototype.Browser.IE) {
        // IE has a very interesting way of setting alpha
        obj.style.filter = "alpha(opacity=" + (value*100) + ")";
        obj.zoom = 1; // Required by ie for filters to work
    } else {
        // Other browsers has a more sane approach...
        obj.style.opacity = value;
    }
}

// Instantiate the library.
var ajaxListLibrary = new AjaxListLibrary();

/**
 * Ajax List java script element to render interactible lists in iPeer
 */
function AjaxList (parameterArray, whereToDisplay) {
    // Initialize given vairables
    this.controller     = parameterArray.controller;
    this.columns        = parameterArray.columns;
    this.data           = parameterArray.data.entries;
    this.count          = parameterArray.data.count;
    this.timeStamp      = parameterArray.data.timeStamp;
    this.state          = parameterArray.data.state;
    this.actions        = parameterArray.actions;
    this.joinTables    = parameterArray.joinFilters;
    this.webroot        = parameterArray.webroot;


    // Where to display this object?
    var whereToDisplayElement = $(whereToDisplay);
    if (!whereToDisplayElement) {
        alert("AjaxList: Can not find the element '" + whereToDisplay + "' to render list!");
        return;
    }
    this.display = new Element("div");
    this.header = new Element ("div");
    this.table = new Element("table");
    this.table.width = "100%";
    this.table.cellPadding = "4";
	this.table.setAttribute('class', 'standardtable');
    this.header = new Element ("div");
    this.footer = new Element("div");

    this.display.appendChild(this.header);
    this.display.appendChild(this.table);
    this.display.appendChild(this.footer);
    whereToDisplayElement.appendChild(this.display);

    this.ajaxObject = null;
    this.headerPageList = null;
    this.footerPageList = null;
    this.headerPageSizes = null;
    this.actionDisplay = null;
    this.searchBar = null;
    this.busyImage = null;
    this.searchAfterTypeDelayTimer = null;

    // There should always be data supplied by php in the HTML
    //  file sent by the server. Decide to either use this data, or
    //  ask the server to refresh it.
    var isStaleData = false;
    var lastTime = ajaxListLibrary.getCookie(this.getUpdateCookieName());
    if (lastTime && this.data !=null) {
        // If the cookie's timeStamp is older, this means that
        //  this page was AJAX-updated since, and we need to refresh it.
        isStaleData = (lastTime >= this.timeStamp);
    }

    if (!isStaleData && this.data != null && this.state != null) {
        this.renderHeader();
        this.renderTable();
        this.renderFooter(this.footer);
        this.renderUsage(this.display);
    } else {
        this.updateFromServer(false);
    }
}

// Gets the update cookie name for this controller list
AjaxList.prototype.getUpdateCookieName = function() {
    return "ajaxListLastUpdateTime" + "_" + this.controller;
}

// Renders the busy spinner in the middle of the talbe
AjaxList.prototype.renderBusyImage = function() {
    // Shut off image if already rendering
    if (this.busyImage) {
        this.stopBusyImage();
    }
    // Create a new image in the middle of the table.
    this.busyImage = new Element("img");
    this.busyImage.src = this.webroot + busyIconURL;
    if (!isIE6) {
        this.busyImage.style.position = "fixed";
        this.busyImage.style.top = BusyImageYOffset + "px"
    } else {
        this.busyImage.style.position = "absolute";
        this.busyImage.style.bottom = 0;
        this.busyImage.style.top = BusyImageYOffset +
            (document.documentElement.scrollTop  || document.body.scrollTop) + "px"
    };
    this.busyImage.style.left = (this.table.offsetWidth / 2) + BusyImageXOffset + "px" ;

    // Add the image to the body of the page
    document.body.appendChild(this.busyImage);
}

// Stops the busy image from rendering anymore
AjaxList.prototype.stopBusyImage = function () {
    if (this.busyImage) {
        document.body.removeChild(this.busyImage);
        this.busyImage = null;
    }
}


// Renders the Search Tab, etc.
AjaxList.prototype.renderHeader = function() {
    while(this.header.firstChild) this.header.removeChild(this.header.firstChild);

    var table = new Element("table");
    table.width = "100%";
    var tbody = new Element("tbody");
    var tr = new Element("tr");
    var td = new Element("td");
    this.searchBar = td;
    this.renderSearchBar(this.searchBar);
    tr.appendChild(td);

    // the page Size, and page selections
    var td = new Element("td");
    var div1 = new Element("div");
    var div2 = new Element("div");
    this.headerPageSizes = div1;
    this.renderPageSizes(this.headerPageSizes);
    this.headerPageList = div2;
    this.renderPageList(this.headerPageList);
    td.appendChild(div1);
    td.appendChild(div2);

    tr.appendChild(td);
    tbody.appendChild(tr);
    table.appendChild(tbody);
    this.header.appendChild(table);
}

AjaxList.prototype.renderUsage = function (div) {
    var textBox = new Element("div", {"style":
    "text-align:left;color:dimgray"}).update(ListUsage);
    div.appendChild(new Element("br"));
    div.appendChild(textBox);
}


// Create a handler for the map Selectors
AjaxList.prototype.createDelegateForSelectionMap = function (thisObject, thisColumn)
{
    return function () {
        // We need to create a callback for this: direct call
        //   to changeMapFilter doesn't detect the value change!
        var delegate = ajaxListLibrary.createDelegateWithParams(thisObject,
                                                                thisObject.changeMapFilter, thisColumn, this.value);
        window.setTimeout(delegate, 0);
    }
}

// Renders the Select elements for any "map" type columns
AjaxList.prototype.renderSelectionMaps = function (div) {
    // First, render the selection maps
    var atLeastOneMapAdded = false;
    for (i = 0; i < this.columns.length; i++) {
        // Put all maps into the selections
        var column = this.columns[i];
        var type = (column[TYPE_COL] !== undefined) ? column[TYPE_COL] : "string";
        // Is this a map? add it in!
        if (type == "map") {
            div.appendChild(document.createTextNode(atLeastOneMapAdded ? ", and " : "Show "));

            // Does the state variable exist for this entry? if not, put it in
            if (this.state.mapFilterSelections[column[ID_COL]] === undefined) {
                this.state.mapFilterSelections[column[ID_COL]] = "";
            }

            var select = new Element("select");
            var thisObject = this;
            var thisColumn = column[ID_COL];
            select.onchange = this.createDelegateForSelectionMap(
                thisObject, thisColumn);

            var option = new Element("option", {"value" : ""}).update("-- All --");
            select.appendChild(option);
            // Now list each of the options records
            for (var entry in column[MAP_COL]) {
                var text = column[MAP_COL][entry];

                if (!column[MAP_COL].hasOwnProperty(entry)) {
                    continue;
                }
                // Create the new option
                option = new Element("option",
                                     {"value" : entry}).update(column[MAP_COL][entry]);

                if (this.state.mapFilterSelections[column[ID_COL]] == entry) {
                    option.selected = "selected";
                }

                select.appendChild(option);
                div.appendChild(select);
            }
            // Get the proper plural
            var text = column[DESC_COL];
            //starting to create weird words (eg. Availabilitys)
            //text += (text.length > 1 && text.charAt(text.length - 1) == 's') ? "es" : "s";

            div.appendChild(document.createTextNode(" " + text));
            atLeastOneMapAdded = true;
        }
    }
    if (atLeastOneMapAdded) {
        // adding a period to separate the maps with the next section
        div.appendChild(document.createTextNode(". "));
    }
    return atLeastOneMapAdded;
}

// Create a handler for the map Selectors
AjaxList.prototype.createDelegateForJoinFilter = function (thisObject, thisColumn)
{
    return function () {
        // We need to create a callback for this: direct call
        //   to changeMapFilter doesn't detect the value change!
        var delegate = ajaxListLibrary.createDelegateWithParams(thisObject,
                                                                thisObject.changeJoinFilter, thisColumn, this.value);
        window.setTimeout(delegate, 0);
    }
}

// Render the extra filter control
AjaxList.prototype.renderJoinFilters = function (div) {
    // First, render the selection maps
    var atLeastOneAdded = false;

    // If there are no extra paramaters, just return false;
    if (!this.joinTables) {
        return atLeastOneAdded; // (false)
    }

// For each filter, render a selecngineering Design Graphics - 8th Edition by J. H. Earletion
    for (i = 0; i < this.joinTables.length; i++) {

        // Create a local reference
        var filter = this.joinTables[i];

        // If this a filter, or just a join table?
        if (filter.id === undefined) {
            // Don't process non-filters.
            continue;
        }

        // Does the state variable exist for this entry? if not, create it
        if (this.state.joinFilterSelections[filter.id] === undefined) {
            this.state.joinFilterSelections[filter.id] = "";
        }

        // Is this filter meant to be shown now? Do a quick check to make sure.
        if (filter.dependsMap !== undefined && filter.dependsValues !== undefined) {
            // Check this state of the map this filter depends on
            if (this.state.mapFilterSelections[filter.dependsMap] !== undefined) {
                // Check if this is one of the
                if (!ajaxListLibrary.contains(filter.dependsValues,
                        this.state.mapFilterSelections[filter.dependsMap])) {
                    // Sorry! don't render this join condition unless the above are met.
                    continue;
                }
            } else {
                // Sorry! don't render this join condition unless the above are met.
                continue;
            }
        }

        var text = atLeastOneAdded ? ", and" : "";
        text += " " + filter.description + " ";
        div.appendChild(document.createTextNode(text));

        var select = new Element("select");
        var thisObject = this;
        select.onchange = this.createDelegateForJoinFilter(thisObject, filter.id);

        /*function () {
            // We need to create a callback for this: direct call
            //   to changeExtraFilter doesn't detect the value change!
            var delegate = ajaxListLibrary.createDelegateWithParams(thisObject,
                            thisObject.changeJoinFilter, filter.id, this.value);
            window.setTimeout(delegate, 0);
        }
      */
        var option = new Element("option", {"value" : ""}).update("-- All --");
        select.appendChild(option);
        // Now list each of the optionsecords
        for (var entry in filter.list) {
            // Discart any objects that do not come from this one.
            if (!filter.list.hasOwnProperty(entry)) {
                continue;
            }

            var description = filter.list[entry];
            // Create the new option
            option = new Element("option", {"value" : entry}).update(description);

            if (this.state.joinFilterSelections[filter.id] == entry) {
                option.selected = "selected";
            }

            select.appendChild(option);
            div.appendChild(select);
        }

        atLeastOneAdded = true;
    }
    return atLeastOneAdded;
}


// Renders the search control itself
AjaxList.prototype.renderSearchControl = function (div) {
    var select = new Element("select");
    var thisObject = this;
    select.onchange = function () {
        // We need to create a callback for this: direct call
        //   to selectSearchColumn doesn't detect the value change!
        var delegate = ajaxListLibrary.createDelegateWithParams(thisObject,
            thisObject.selectSearchColumn,this.value);
        window.setTimeout(delegate, 0);
    }
    // Generate a drop-down entry for each column
    for (i = 0; i < this.columns.length; i++) {

        // Put all maps into the selections
        var column = this.columns[i];

        // Should we show this search column
        if ((column[ID_COL] && column[ID_COL].charAt(0) =='!') ||
                column[TYPE_COL] !== undefined && column[TYPE_COL] == "hidden" ||
                column[TYPE_COL] == "map" || column[TYPE_COL] == "date") {
            // do not render hidden columns.
            continue;
        }

        // Prep the selection drop down entry
        var columnDesc = (column[DESC_COL] !== undefined) ? column[DESC_COL] : column[ID_COL];
        var option = new Element("option", {"value" : column[ID_COL]}).update(columnDesc);
        if (this.state.searchBy == column[ID_COL]) {
            option.selected = "selected";
        }

        select.appendChild(option);
    }
    div.appendChild(select);

    div.appendChild(document.createTextNode(" contains: "));

    var input = new Element("input", {"type" : "text", "size" : 15, "value" : this.state.searchValue,
        "style":"text-align:center"});

    // Set the input's color based on what on the resutls
    if (input.value) {
        // If there are files listed, make it yellow, but for no files, make it red.
        input.style.backgroundColor = (this.count > 0) ? "#FFFFA8" : "#FFB0B0";
    }

    input.id = "searchInputField";
    var thisObject = this;
    input.onkeyup = function() {  thisObject.changeSearchValue(this.value); }
    div.appendChild(input);
    // Focus input after a moment, if it contains anything.
    window.setTimeout("var s = $('searchInputField'); if (s.value) { s.focus(); }", 10);

    div.appendChild(document.createTextNode("   "));
    var submit = new Element("input", {"type" : "submit", "value" : "Search", "style":"margin:0em 2em 0em 1em"});
    submit.onclick = ajaxListLibrary.createDelegate(this, this.doSearch);

    var clear = new Element("input", {"type" : "submit", "value" : "Clear"});
    clear.onclick = ajaxListLibrary.createDelegate(this, this.clearSearchValue);

    div.appendChild(submit);
    div.appendChild(clear);
}


// Renders the search bar
AjaxList.prototype.renderSearchBar = function (divIn) {
    // so long as obj has children, remove them
    while(divIn.firstChild) divIn.removeChild(divIn.firstChild);

    // make everything bold
    var div = new Element("b");
    divIn.appendChild(div);

    var divTop = new Element("div", {"style":"margin: 0 6px 6px 6px"});
    var divBottom = new Element("div", {"style":"margin:6px"});

    // Put in a state variable for selection maps
    if (this.state.mapFilterSelections === undefined) {
        this.state.mapFilterSelections = {}; // New Object
    }

    // Put in a state variable for selection maps
    if (this.state.joinFilterSelections === undefined) {
        this.state.joinFilterSelections = {}; // New Object
    }

    var atLeastOneMapAdded = this.renderSelectionMaps(divTop);

    this.renderJoinFilters(divTop);

    // Now, render the search control
    divBottom.appendChild(document.createTextNode(atLeastOneMapAdded ? " and Search where: " : "Search where: "));
    this.renderSearchControl(divBottom);

    // Add the divisions to the
    div.appendChild(divTop);
    div.appendChild(divBottom);

}


// Renders the page size control
AjaxList.prototype.renderPageSizes = function (div) {
    // so long as obj has children, remove them
    while(div.firstChild) div.removeChild(div.firstChild);

    div.style.textAlign = "right";
    div.style.whiteSpace = "nowrap";
    div.style.padding = "12px 0";
    div.appendChild(new Element("b").update("Page Size: "));

    // Create a radio button for each page size
    for (i = 0; i < AvailablePageSizes.length; i++) {
        var thisPageSize = AvailablePageSizes[i];
        var input = new Element("input");
        input.type = "radio";
        if (this.state.pageSize == thisPageSize) {
            input.checked = "checked";
            if (Prototype.Browser.IE) {
                // IE workaround
                div.appendChild(new Element("b").update("(" + thisPageSize + ") "));
            } else {
                div.appendChild(input);
                div.appendChild(document.createTextNode(thisPageSize + "  "));
            }
        } else {
            input.onclick = ajaxListLibrary.createDelegateWithParams
                (this, this.changePageSize, thisPageSize);
            div.appendChild(input);
            div.appendChild(document.createTextNode(thisPageSize + "  "));
        }
    }
}

// Render a single page entry radio button

AjaxList.prototype.renderSinglePageRadioButton = function (i, pages) {
    pages.appendChild(document.createTextNode(" "));
    var a = document.createElement("a");
    if (this.state.pageShown == i) {
        a.setAttribute('class', 'selected_number');
    } else {
        a.setAttribute('class', 'ajax_paginate');
        a.style.cursor = "pointer";
        a.onclick = ajaxListLibrary.createDelegateWithParams(this, this.changePage, i);
    }
    a.appendChild(document.createTextNode(i));
    pages.appendChild(a);
}

// Renders "directional buttons" (eg. First, Next)
AjaxList.prototype.renderDirectionRadioButton = function (index, pages, totalPages) {
    pages.appendChild(document.createTextNode(" "));
    var text = new Array("<<", "<", ">", ">>");
    var a = document.createElement("a");
    a.setAttribute('class', 'ajax_paginate');
    // first
    if (text[index] == "<<") {
        var i = 1;
        if (this.state.pageShown != 1) {
            a.style.cursor = "pointer";
            a.onclick = ajaxListLibrary.createDelegateWithParams(this, this.changePage, i);
        }
    // previous
    } else if (text[index] == "<") {
        var i = this.state.pageShown - 1;
        if (this.state.pageShown != 1) {
            a.style.cursor = "pointer";
            a.onclick = ajaxListLibrary.createDelegateWithParams(this, this.changePage, i);
        }
    // next
    } else if (text[index] == ">") {
        var i = this.state.pageShown + 1;
        if (this.state.pageShown != totalPages) {
            a.style.cursor = "pointer";
            a.onclick = ajaxListLibrary.createDelegateWithParams(this, this.changePage, i);
        }
    // last
    } else {
        var i = totalPages;
        if (this.state.pageShown != totalPages) {
            a.style.cursor = "pointer";
            a.onclick = ajaxListLibrary.createDelegateWithParams(this, this.changePage, i);
        }
    }
    a.appendChild(document.createTextNode(text[index]));
    pages.appendChild(a);
}

// Renders the page listing
AjaxList.prototype.renderPageList = function(div) {
    // so long as obj has children, remove them
    while(div.firstChild) div.removeChild(div.firstChild);

    var totalPages = Math.ceil(this.count / this.state.pageSize);
    totalPages = (totalPages < 1) ? 1 : totalPages;

    // Check if there are any pages at all:
    if (totalPages < 2) {
        // Only 1 page? on need to render this, then.
        return;
    }

    div.style.textAlign = "right";
    div.style.paddingTop = "4px";

    //div.appendChild(new Element("b").update("Go to Page: "));
    var pages = new Element("span");
    pages.style.color = "chocolate";

    this.renderDirectionRadioButton(0, pages, totalPages);
    this.renderDirectionRadioButton(1, pages, totalPages);

    if (totalPages <= maxPageNumbersToShow) {
        for (i = 1; i <= totalPages; i++) {
            this.renderSinglePageRadioButton(i, pages);
        }
    } else {

        if (this.state.pageShown - averageOffset <= 0) {
            var minusOffset = this.state.pageShown - 1;
            var plusOffset = maxPageNumbersToShow - minusOffset - 1;
        } else if (this.state.pageShown + averageOffset > totalPages) {
            var plusOffset = totalPages - this.state.pageShown;
            var minusOffset = maxPageNumbersToShow - plusOffset - 1;
        } else {
            var minusOffset = averageOffset;
            var plusOffset = averageOffset;
        }

        // Display the pages around this page
        for (i = (this.state.pageShown - minusOffset);
             i <= (this.state.pageShown + plusOffset);
            i++) {
                this.renderSinglePageRadioButton(i, pages);
        }
    }

    this.renderDirectionRadioButton(2, pages, totalPages);
    this.renderDirectionRadioButton(3, pages, totalPages);

    // Add the pages to the GUI
    div.appendChild(pages);
}

AjaxList.prototype.renderAllPageLists = function() {
    this.renderPageList(this.headerPageList);
    this.renderPageList(this.footerPageList);
}


// Renders the Page Selection Tab, etc.
AjaxList.prototype.renderFooter = function(div) {
    // so long as obj has children, remove them
    while(div.firstChild) div.removeChild(div.firstChild);

    var table = new Element("table", {"width" : "100%"});
    var tbody = new Element("tbody");
    var tr = new Element("tr");

    // Display the time of the search
    var td = new Element("td", {"class":"timestamp" });
    //var date = new Date();
    //td.appendChild(document.createTextNode(date.toString()));
    tr.appendChild(td);

    // Display the number of results
    var td = new Element("td", {"class" : "total-result"});
    td.appendChild(document.createTextNode(
        this.count < 1 ?
        "No results" :
        ("Total Results: " + this.count)));
    tr.appendChild(td);

    // Display Number of search results in first f
    var td = new Element("td", {"style" : "text-align: right; width: 33%" });
    this.footerPageList = td;
    this.renderPageList (this.footerPageList);
    tr.appendChild(td);

    tbody.appendChild(tr);
    table.appendChild(tbody);
    div.appendChild(table);
}

// Renders the table headers, with column names
AjaxList.prototype.renderTableHeaders = function (tbody) {
    // Create Headers
    var headerRow = new Element("tr", { "style":"text-align:center"});

    for (i = 0; i < this.columns.length; i++) {
        var column = this.columns[i];

        // Should we show this column
        if (column[TYPE_COL] !== undefined && column[TYPE_COL] == "hidden") {
            // do not render hidden columns.
            continue;
        }

        var th = new Element("th");
        // Set the width of this column
        if (column[WIDTH_COL] !== undefined) {
            th.style.width = column[WIDTH_COL];
        }

        th.noWrap = true;

        // Set up the sorting onclick handler, if this isn't a special "!"-flagged column
        if (column[ID_COL] && column[ID_COL].charAt(0) != '!') {
            th.onclick = ajaxListLibrary.createDelegateWithParams(
                this, this.setSortingColumn, column[ID_COL]);
            th.style.cursor = "pointer";
        } else {
            th.style.cursor = "arrow";
        }

        // User either Model.column convension, if a real column name if it was specified
        if (this.columns[i][DESC_COL] !== undefined) {
            var columnTitle = column[DESC_COL];
        } else {
            var columnTitle = column[i][ID_COL];
        }

        // If sorting by this column
        if (column[ID_COL] == this.state.sortBy) {
            columnTitle += this.state.sortAsc ? " \u25B2" : " \u25BC";
        }

        th.appendChild(document.createTextNode(columnTitle));
        headerRow.appendChild(th);
    }
    tbody.appendChild(headerRow);
}



// Render the main table body
AjaxList.prototype.renderTableBody = function(tbody) {

    // Render each entry
    for (j = 0; j < this.data.length; j++) {
        var tr = new Element("tr",{ "style" : "cursor: pointer;"});
        var entry = this.data[j];
        for (i = 0; i < this.columns.length; i++) {
            var column = this.columns[i];

            // Should we show this column
            if (column[TYPE_COL] !== undefined && column[TYPE_COL] == "hidden") {
                // do not render hidden columns.
                continue;
            }

            var td = new Element("td");

            // A division is required to use overflow
            var div = new Element("div");
            //div.style.overflow="hidden";

            // Get the actual entry name
            var split = column[ID_COL].split(".", 2);
            var contents = entry[split[0]][split[1]];

            // Handle any special column types
            if (column[TYPE_COL] !== undefined) {

                // Is this a map-type column? Should be translate it?
                if (column[TYPE_COL] == "map") {
                    if (column[MAP_COL][contents] !== undefined) {
                        contents = column[MAP_COL][contents];
                    } else {
                        contents = "(unknown) " + contents;
                    }
                    // Normal Text create and add
                    div.update(contents);
                } else if (column[TYPE_COL] == "action") {
                    // If this is an "action" type entry, we need to create a link for it
                    if (contents && contents.length > 161) {
                        contents = contents.substring(0, 159).concat(' ...');
                    }
                    var link = new Element("a").update(contents);
                    link.href="";
                    link.onclick = ajaxListLibrary.createDelegateWithParams(this,
                        this.doAction, entry, column[ACTION_COL]);
                        div.appendChild(link);
                } else if (column[TYPE_COL] == "link") {
                    // Create an icon with a link in it
                    if (contents && (contents.length > 10)) { // 10 is min url length
                        // Create a link fist
                        var link = new Element("a");
                        div.style.textAlign = "center";
                        link.href = contents;
                        // Check to see if there was an icon defined.
                        //  if there was, display it. if not, display
                        var linkInsides = (column[ICON_COL] !== undefined) ?
                            new Element("img", {"src":(this.webroot + iconURL + column[ICON_COL]),
                                "style":"border:0px;"}) :
                            document.createTextNode(contents); // if no icon defined
                        link.appendChild(linkInsides);
                        div.appendChild(link);
                    }
                } else if (column[TYPE_COL] == "date") {
                    div.style.textAlign = "center";
                    div.update(contents);
                } else {
                    // Normal Text create and add
                    // if the string is too long, it is very inconvenient for
                    // users to scroll through to see all the entries
                    if (contents && contents.length >161) {
                        contents = contents.substring(0, 159).concat(' ...');
                    }
                    div.update(contents);
                }
            } else {
                // Normal Text create and add
                div.appendChild(document.createTextNode(contents));
            }

            // Append the contents division onto the element
            td.appendChild(div);

            var clickDelegate = ajaxListLibrary.createDelegateWithParams(this, this.rowClicked, entry, tr);
            td.onclick = clickDelegate;
            td.oncontextmenu = clickDelegate;
            tr.appendChild(td);
        }

        tbody.appendChild(tr);
    }
}

// Renders the whole table at once
AjaxList.prototype.renderTable = function() {
    // Clear Table
    while(this.table.firstChild) this.table.removeChild(this.table.firstChild);

    var tbody = new Element("tbody");

    this.renderTableHeaders(tbody);
    this.renderTableBody(tbody);

    this.table.appendChild(tbody);
}

// When user clicks a link-type table cell entry
AjaxList.prototype.doAction = function (event, entryRow, actionDesc) {

    // Stop this event's propogation, so that the link is triggered,
    //  and not the row.
    if (Prototype.Browser.IE) {
        window.event.cancelBubble = true;   // IE only
    } else {
        event.stopPropagation();         // W3C compatibles
    }


    // Find the function description if the actions array
    for (var i = 0; i < this.actions.length; i++) {
        var action = this.actions[i];
        if (action[ACTION_NAME] == actionDesc) {
            // Action found, execute it!
            var actionDisplay = new AjaxListAction( 0, 0,
                                                    this.webroot, this.controller,
                                                    this.columns, entryRow, this.actions);
            actionDisplay.performAction(event, action);
            return false; // Do not take the link!
        }
    }
    return false; // Do not take the link!
}

// Handles a user's click on a displayed row
AjaxList.prototype.rowClicked = function (event, entryRow, tr) {

    if (this.actionDisplay != null && !this.actionDisplay.isClosed()) {
        this.actionDisplay.close();
        this.actionDisplay = null;
        return false;
    }

    // IE workaround - how do we get XY of click?
    var x = ((window.event) ? (window.event.clientX) : event.clientX);
    var y = ((window.event) ? (window.event.clientY) : event.clientY);

    x += (document.documentElement.scrollLeft || document.body.scrollLeft);
    y += (document.documentElement.scrollTop  || document.body.scrollTop);

    this.actionDisplay = new AjaxListAction( x + AjaxListActionOffsetX,
                                                 y + AjaxListActionOffsetY,
                                                this.webroot,  this.controller,
                                                this.columns, entryRow, this.actions, tr);
    this.actionDisplay.render();

    // This is for the on context menu
    return false;
}

// Sets up the list to sort by this column
AjaxList.prototype.setSortingColumn = function(event, column) {
    if (this.state.sortBy == column) {
        // If this column was already selected, toggle direction
        this.state.sortAsc = !this.state.sortAsc;
        this.state.pageShown = 1;
    } else {
        //Otherwise, just set the sorting to a new one
        this.state.sortBy = column;
        this.state.sortAsc = true;
        this.state.pageShown = 1;
    }

    // Update from Server
    this.updateFromServer();

    // Render the new page list
    this.renderAllPageLists();
}

AjaxList.prototype.changePageSize = function(event, pageSize) {

    // Take the page offset into account, so that the present
    // first result is shown on the
    //  new page for sure
    if (this.state.pageShown < 2) {
        // This also works around bad page number, line -3 for example
        this.state.pageShown = 1;
        this.state.pageSize = pageSize;
    } else {
        var presentPosition = ((this.state.pageShown - 1) * this.state.pageSize);
        var newPagePosition = Math.floor(presentPosition / pageSize) + 1; // aka round down
        this.state.pageSize = pageSize;
        this.state.pageShown = newPagePosition;
    }

    // Update list from server.
    this.updateFromServer();

    // Re-render the page size display.
    this.renderPageSizes(this.headerPageSizes);
    this.renderAllPageLists();
}

AjaxList.prototype.changePage = function(event, pageNumber) {
    // Set the page number
    this.state.pageShown = pageNumber;

    // Ask for update from server
    this.updateFromServer();

    // Render the new page list
    this.renderAllPageLists();
}

// Change the search column selections
AjaxList.prototype.selectSearchColumn = function(event, columnName) {
    this.state.searchBy = columnName;
    this.startSearchAfterTypeDelayTimer();
}


// Stop the Search After Typing Timer, if it's been started
AjaxList.prototype.clearSearchAfterTypeDelayTimer = function () {
    if (this.searchAfterTypeDelayTimer) {
        window.clearTimeout(this.searchAfterTypeDelayTimer);
    }
    this.searchAfterTypeDelayTimer = null;
}

// Starts the Search After Typing Timer, restarting it if it's been previously started
AjaxList.prototype.startSearchAfterTypeDelayTimer = function () {
    this.clearSearchAfterTypeDelayTimer();
    this.searchAfterTypeDelayTimer = window.setTimeout(
        ajaxListLibrary.createDelegate(this, this.doSearch), searchAfterTypeDelay);
}

// Start a new search if the browser is not IE (use the search button here)
AjaxList.prototype.changeSearchValue = function (newValue) {
    // Was there a search timer already created?
    this.clearSearchAfterTypeDelayTimer();
    this.state.searchValue = newValue;
    this.startSearchAfterTypeDelayTimer();
}

AjaxList.prototype.clearSearchValue = function() {
    this.state.searchValue = "";
    $("searchInputField"). value = "";
    // And refresh the page!
    return this.doSearch();
}

// When the search button is clicked
AjaxList.prototype.doSearch = function() {
    // Clear the search after typing timer
    this.clearSearchAfterTypeDelayTimer();

    // Set the page number
    this.state.pageShown = 1;

    // Do the update from server
    this.updateFromServer();

    // Return false to indicate "do not proceed" for button clicks
    return false;
}

// When the uses changes the selection of a map filter
AjaxList.prototype.changeMapFilter = function (event, column, newValue) {
    // Set the new selection up
    this.state.mapFilterSelections[column] = newValue;
    // And refresh the page (pageShown set to 1, updateFromServer is called)
    this.doSearch();
}

// When the user changes an extra filter
AjaxList.prototype.changeJoinFilter = function (event, filter, newValue) {
    // Set the new selection up
    this.state.joinFilterSelections[filter] = newValue;
    // And refresh the page (pageShown set to 1, updateFromServer is called)
    this.doSearch();
}

// Fetches an update from server
AjaxList.prototype.updateFromServer = function(getState) {
    // Send the state or no?
    var state = this.state;
    if (getState != null && getState === false) {
        // Disregard state
        var state = null;
    }

    // If there's no call already outgoing.
    if (!this.ajaxObject) {
        // IE 6 doesn't handle rendering the whole page well.
        var fullPage = isIE6;
        var parameters = {"json" : JSON.stringify(state), "fullPage" : fullPage};
        var url = this.webroot + this.controller + "/" + controllerAjaxListFunctionName;

        ajaxListLibrary.setOpacity(this.table, 0.75);
        this.renderBusyImage();

        if (!fullPage) {
            this.ajaxObject = new Ajax.Request(url,
                {
                    "method" : "post",
                    "parameters" : parameters,
                    "onComplete" : ajaxListLibrary.createDelegate(this, this.ajaxCallComplete)
                }
            );
        } else {
            // Create a form and submit it to the server
            var hiddenDiv = new Element("div",
                    {"style":"display:none;"});
            var form = new Element("form",
                    {"action" : url, "method" : "post"});
            var jsonInput = new Element("input",
                    {"type" : "text", "name" : "json", "value" : parameters.json});
            var fullPageInput = new Element ("input",
                    {"type" : "text", "name" : "fullPage", "value" : "true"});
            var submitButton = new Element("input",
                    {"type" : "submit", "name" : "submit", "value" : "submit"});
            form.appendChild(jsonInput);
            form.appendChild(fullPageInput);
            form.appendChild(submitButton);
            hiddenDiv.appendChild(form);
            document.body.appendChild(hiddenDiv);
            submitButton.click();
        }
    }
}

AjaxList.prototype.ajaxCallComplete = function (response) {

    // free the ajax object
    this.ajaxObject = null;
    if (response.status != 200 || !response.responseJSON) {
        var message = "Can not update list: network down or invalid server response.\n";
        message += "This can be be your iPeer session timing out.\n";
        message += "Try logging out, and logging back into iPeer.";
        alert(message);
    } else {
        // Update the update time
        ajaxListLibrary.setCookie(this.getUpdateCookieName(), response.responseJSON.timeStamp);
        // Update Data
        this.data = response.responseJSON.entries;
        this.count = response.responseJSON.count;
        this.state = response.responseJSON.state;
        this.timeStamp =response.responseJSON.timeStamp;
        ajaxListLibrary.setCookie(this.getUpdateCookieName(), this.timeStamp);
        this.renderHeader();
        this.renderTable();
        this.renderFooter(this.footer);
    }

    //  Change the gui Components
    this.stopBusyImage();
    ajaxListLibrary.setOpacity(this.table, 1.0);
}


/********** Action Display for Ajax List **********/
function AjaxListAction(x, y, root, controller, columns, entry, actions, tableRow) {
    this.x = x;
    this.y = y;
    this.root = root;
    this.controller = controller;
    this.columns  = columns;
    this.entry = entry;
    this.actions = actions;
    this.tableRow = tableRow;
    this.display = null;
    this.closed = false;
}

AjaxListAction.prototype.close = function() {
    if (this.display) {
        document.body.removeChild(this.display);
        this.display = null;
    }

    // Clear any onclicks
    document.body.onclick = null;
    document.body.oncontextmenu = null;

    this.closed = true;

    if (this.tableRow !== undefined) {
        this.tableRow.style.backgroundColor = "";
    }

    return false;
}

AjaxListAction.prototype.isClosed = function() {
    return this.closed;
}

// Checks and renders a single action button.
AjaxListAction.prototype.renderAction = function (action) {
    // Determine if the user is allowed to renderAction, based on maps
    var active = true;

    // Highlist this row another color
    if (this.tableRow !== undefined) {
        this.tableRow.style.backgroundColor = AjaxListTableRowSelectedBackgroundColor;
    }

    if (action[ACTION_RESTRICTIONS]) {
        var restrictions = action[ACTION_RESTRICTIONS];
        for (var map in restrictions) {
            // Ensure this is an actual property
            if (!restrictions.hasOwnProperty(map)) {
                continue;
            }

            // Grab the cell of interest's value
            var split = map.split(".", 2);
            cell = this.entry[split[0]][split[1]];

            var values = restrictions[map];

            // If this value was specified, follow those direct instructrions
            if (values[cell] !== undefined) {
                if (!values[cell]) {
                    active = false;
                }
            } else {
                // Otherwise look for the default value entry, and follow that.
                if (values["!default"] !== undefined) {
                    if (!values["!default"]) {
                        active = false;;
                    }
                } else {
                    active = false;
                }
            }
        }
    }

    var menuItem = new Element("div", {"style": "cursor: default; padding:4px;font-weight: bold;"})
        .update(action[ACTION_NAME]);
    if (active) {
        menuItem.style.color = AjaxListActionMenuTextEnabledColor;
        menuItem.onmouseover =  function() { this.style.backgroundColor=AjaxListActionMenuSelected;};
        menuItem.onmouseout =   function() { this.style.backgroundColor=AjaxListActionMenuColor;};
        var actionDelegate = ajaxListLibrary.createDelegateWithParams(this, this.performAction, action);
        // Captures right and left button mouse ups on most platforms.
        // Tested : IE6, Firefox, Chrome.
        menuItem.onmouseup = actionDelegate;

    } else {
        menuItem.style.color = AjaxListActionMenuTextDisabledColor;
        menuItem.onclick = "return false;";
    }
    this.display.appendChild(menuItem);
}


AjaxListAction.prototype.render = function() {
    if (this.actions.length>0) {
        this.display = new Element("div");
        this.display.style.backgroundColor = AjaxListActionMenuColor;
        this.display.style.position = "absolute";
        this.display.style.left = this.x + "px";
        this.display.style.top = this.y + "px";
        this.display.style.order = "solid 1px";
        this.display.style.margin = "0px";
        this.display.style.padding = "5px";
        this.display.style.border = "solid 1px";

        // Disable text selection for various browsers - this is a pop-up menu!
        this.display.style.KhtmlUserSelect="none"; // WebKit based
        this.display.style.MozUserSelect="none";

        // Create MenU Items for actions
        for (i = 0; i < this.actions.length; i++) {
            var action = this.actions[i];
            this.renderAction(action);
        }

        document.body.appendChild(this.display);


        var delegate = ajaxListLibrary.createDelegate(this, this.assignCloseDelegate);
        window.setTimeout(delegate,10);
    }
}

AjaxListAction.prototype.assignCloseDelegate = function() {
    document.body.onclick = ajaxListLibrary.createDelegate(this, this.close);
    document.body.oncontextmenu = ajaxListLibrary.createDelegate(this, this.close);
}


AjaxListAction.prototype.performAction = function(event, action) {
    if (action[ACTION_WARNING] != "") {
        if (!confirm(action[ACTION_WARNING])) {
            this.close();
            return;
        }
    }

    var newWindow = false;

    // Figure out the proper URL, starting with the controller
    var url = action[ACTION_CONTROLLER] ? action[ACTION_CONTROLLER] : this.controller;

    // Combine the URL components
    for (j = ACTION_URL_PARTS_START; j < action.length; j++) {
        urlPart = action[j];

        // a "!" in front of url action means open a new window
        if (!newWindow && urlPart.length > 0 && urlPart.charAt(0) == '!') {
            urlPart = urlPart.substr(1);
            newWindow = true;
        }

        var split = urlPart.split(".", 2);
        if ((split.length == 2) && (this.entry[split[0]][split[1]])) {
            // If this is an actual value, set it.
            var lookedUpEntry = this.entry[split[0]][split[1]];
        } else {
            // Otherwise, display the value exactly as it was put in
            var lookedUpEntry = urlPart;
        }

        url += "/" + lookedUpEntry;
    }

    this.close();

    if (!newWindow) {
       window.location = this.root + url;
    }
}
