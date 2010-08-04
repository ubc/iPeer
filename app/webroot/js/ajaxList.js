

// Configuration Variables
var AjaxListActionMenuOffsetX = 100;
var AjaxListActionMenuOffsetY = -120;

// It's best to keep the following as multiples of the lowest page count,
//  so that when the page-size-change Occurs, the
//  results are paged up in blocks. But this is not
//  required: the JS pagination code will figure out
//  the right page to jump to: the one that for sure
//  contains the first record on the present page,
var AvailablePageSizes = [15, 30, 90, 360];

//Contants
var blackUpTriangleHTMLCode = "&#9650;";
var blackDownTriableHTMLCode = "&#9660;";

var controllerAjaxListFunctionName = "ajaxList";

var ID_COL = 0;     // ID of the column
var DESC_COL = 1;   // Description of the columns
var TYPE_COL = 2;   // Type of the column
var MAP_COL = 3;    // Map of column values

// Small Helper function library class
function AjaxListLibrary () {
}

AjaxListLibrary.prototype.createDelegate = function (object, method) {
    return function() {
        return method.apply(object, arguments);
    }
}

AjaxListLibrary.prototype.createDelegateWith1Param = function (object, method, param1) {
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
        newArguments.push(param1);
        // And call the intended function with the old + new parameters
        return method.apply(object, newArguments);
    }
}


// Instantiate the library.
var ajaxListLibrary = new AjaxListLibrary();

/**
 * Ajax List java script element to render interactible lists in iPeer
 */
function AjaxList (parameterArray, whereToDisplay, state) {
    // Initialize given vairables
    this.controller = parameterArray.controller;
    this.columns = parameterArray.columns;
    this.data = parameterArray.data.entries;
    this.count = parameterArray.data.count;
    this.actions = parameterArray.actions;
    this.webroot = parameterArray.webroot;
    this.state = state;

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
    this.header = new Element ("div");

    this.display.appendChild(this.header);
    this.display.appendChild(this.table);
    whereToDisplayElement.appendChild(this.display);

    this.headerPageList = null;
    this.headerPageSizes = null;
    this.actionDisplay = null;
    this.searchBar = null;
    this.renderHeader();


    if (this.data != null && this.state != null) {
        this.renderTable();
        this.renderFooter();
    } else {
        this.updateFromServer();
    }
}



// Clears out the headers for the list
AjaxList.prototype.clearHeader = function() {
    while(this.header.firstChild) this.header.removeChild(this.header.firstChild);
}

//Clears out the list contents
AjaxList.prototype.clearTable = function() {
    // so long as obj has children, remove them
    while(this.table.firstChild) this.table.removeChild(this.table.firstChild);
}

//Clears out the list contents
AjaxList.prototype.clearFooter = function() {
    // so long as obj has children, remove them
    while(this.footer.firstChild) this.footer.removeChild(this.footer.firstChild);
}


// Renders the Search Tab, etc.
AjaxList.prototype.renderHeader = function() {
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


// Renders the search bar
AjaxList.prototype.renderSearchBar = function (divIn) {
    // so long as obj has children, remove them
    while(divIn.firstChild) divIn.removeChild(divIn.firstChild);

    // make everything bold
    var div = new Element("b");
    divIn.appendChild(div);

    div.appendChild(document.createTextNode(" "));

    var atLeastOneMapAdded = false;
    for (i = 0; i < this.columns.length; i++) {
        // Put all maps into the selections
        var column = this.columns[i];
        var type = (column[TYPE_COL] !== undefined) ? column[TYPE_COL] : "string";
        // Is this a map? add it in!
        if (type == "map") {
            div.appendChild(document.createTextNode(atLeastOneMapAdded ? ", and " : "Show "));

            var select = new Element("select");
            var option = new Element("option", {"value" : ""}).update("-- All --");
            select.appendChild(option);
            // Now list each of the options
            for (var entry in column[MAP_COL]) {
                option = new Element("option",
                    {"value" : entry}).update(column[MAP_COL][entry]);
                select.appendChild(option);
                div.appendChild(select);
            }
            div.appendChild(document.createTextNode(" " + column[DESC_COL] + "s"));
            atLeastOneMapAdded = true;
        }
    }

    // Fill the select with options
    div.appendChild(document.createTextNode(atLeastOneMapAdded ? " and Search where: " : "Search where: "));

    var select = new Element("select");

    // Generate a drop-down entry for each column
    for (i = 0; i < this.columns.length; i++) {
        // Put all maps into the selections
        var column = this.columns[i];
        var columnDesc = (column[DESC_COL] !== undefined) ? column[DESC_COL] : column[ID_COL];
        var option = new Element("option", {"value" : column[ID_COL]}).update(columnDesc);
        if (this.state.searchBy == column[ID_COL]) {
            option.selected = "selected";
        }

        option.onclick = ajaxListLibrary.createDelegateWith1Param
            (this, this.selectSearchColumn, column[ID_COL]);

        select.appendChild(option);
    }
    div.appendChild(select);

    div.appendChild(document.createTextNode(" contains: "));

    var input = new Element("input", {"type" : "text", "size" : 20});
    input.id = "searchInputField";
    var thisObject = this;
    input.onkeyup = function() {  thisObject.changeSearchValue(this.value); }
    div.appendChild(input);

    div.appendChild(document.createTextNode("   "));
    var submit = new Element("input", {"type" : "submit", "value" : "Search"});
    submit.onclick = ajaxListLibrary.createDelegate(this, this.updateFromServer);

    var clear = new Element("input", {"type" : "submit", "value" : "Clear"});
    clear.onclick = ajaxListLibrary.createDelegate(this, this.clearSearchValue);

    div.appendChild(submit);
    div.appendChild(document.createTextNode(" . . . . "));
    div.appendChild(clear);
}

// Renders the page size control
AjaxList.prototype.renderPageSizes = function (div) {
    // so long as obj has children, remove them
    while(div.firstChild) div.removeChild(div.firstChild);

    div.style.textAlign = "right";
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
            input.onclick = ajaxListLibrary.createDelegateWith1Param
                (this, this.changePageSize, thisPageSize);
            div.appendChild(input);
            div.appendChild(document.createTextNode(thisPageSize + "  "));
        }
    }
}

AjaxList.prototype.renderPageList = function(div) {
    // so long as obj has children, remove them
    while(div.firstChild) div.removeChild(div.firstChild);

    div.style.textAlign = "right";
    div.style.paddingTop = "4px";

    div.appendChild(new Element("b").update("Go to Page: "));
    var pages = new Element("span");
    pages.style.color = "chocolate";
    var totalPages = Math.floor(this.count / this.state.pageSize) + 1;
    for (i = 1; i <= totalPages; i++) {
        pages.appendChild(document.createTextNode(" "));
        if (this.state.pageShown == i) {
            // Create a link for the non-selecte Pages
            var span = new Element("b",{"style":"font-size:110%;"})
        } else {
            // Selected Pages
            var span = new Element("span");
            span.style.cursor = "pointer";
            span.onclick = ajaxListLibrary.createDelegateWith1Param(this, this.changePage, i);
        }
        span.appendChild(document.createTextNode(i));
        pages.appendChild(span);
    }
    div.appendChild(pages);
}


// Renders the Page Selection Tab, etc.
AjaxList.prototype.renderFooter = function() {

}

// Renders the table headers, with column names
AjaxList.prototype.renderTableHeaders = function (tbody) {
    // Create Headers
    var headerRow = new Element("tr", { "class":"tableheader",
                                        "style":"cursor:pointer; text-align:center"});

    for (i = 0; i < this.columns.length; i++) {
        var th = new Element("th");
        th.noWrap = true;

        // Set up the sorting onclick handler
        th.onclick = ajaxListLibrary.createDelegateWith1Param(
            this, this.setSortingColumn, this.columns[i][0]);

        // User either Model.column convension, if a real column name if it was specified
        if (this.columns[i][1] !== undefined) {
            var columnTitle = this.columns[i][1];
         } else {
            var columnTitle = this.columns[i][0];
        }

        // If sorting by this column
        if (this.columns[i][0] == this.state.sortBy) {
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
        var tr = new Element("tr",{ "class"  :"tablecell", "style" : "cursor:pointer;"});
        var entry = this.data[j];
        for (i = 0; i < this.columns.length; i++) {
            var td = new Element("td");
            td.noWrap = true;
            // Get the actual entry name
            var column = this.columns[i];
            var split = column[0].split(".", 2);
            var contents = entry[split[0]][split[1]];

            // Is this a map-type column? Should be translate it?
            if (column[TYPE_COL] !== undefined && column[TYPE_COL] == "map") {
                if (column[MAP_COL][contents] !== undefined) {
                    contents = column[MAP_COL][contents];
                } else {
                    contents = "(unknown) " + contents;
                }
            }

            td.appendChild(document.createTextNode(contents));
            tr.appendChild(td);
        }

        tr.onclick = ajaxListLibrary.createDelegateWith1Param(this, this.rowClicked, entry);

        tbody.appendChild(tr);
    }
}

// Renders the whole table at once
AjaxList.prototype.renderTable = function() {
    var tbody = new Element("tbody");

    this.renderTableHeaders(tbody);
    this.renderTableBody(tbody);

    this.table.appendChild(tbody);
}

// Handles a user's click on a displayed row
AjaxList.prototype.rowClicked = function (event, entry) {
    if (this.actionDisplay != null) {
        this.actionDisplay.close();
    }

    // IE workaround
    var x = ((window.event) ? (window.event.clientX) : event.clientX);
    var y = ((window.event) ? (window.event.clientY) : event.clientY);

    x += (document.documentElement.scrollLeft || document.body.scrollLeft);
    y += (document.documentElement.scrollTop  || document.body.scrollTop);

    this.actionDisplay = new AjaxListActionMenu( x + AjaxListActionMenuOffsetX,
                                                 y + AjaxListActionMenuOffsetY,
                                                this.webroot + this.controller,
                                                this.columns, entry, this.actions);
}

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
    this.renderPageList(this.headerPageList);
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
    this.renderPageList(this.headerPageList);
}

AjaxList.prototype.changePage = function(event, pageNumber) {
    // Set the page number
    this.state.pageShown = pageNumber;

    // Ask for update from server
    this.updateFromServer();

    // Render the new page list
    this.renderPageList(this.headerPageList);
}

// Change the search column selections
AjaxList.prototype.selectSearchColumn = function(event, columnName) {
    this.state.searchBy = columnName;
}

// Start a new search if the browser is not IE (use the search button here)
AjaxList.prototype.changeSearchValue = function (newValue) {
    this.state.searchValue = newValue;
}

AjaxList.prototype.clearSearchValue = function(newValue) {
    this.state.searchValue = newValue;
    $("searchInputField"). value = "";

    // Prevent the click from refreshing the page.
    return false;
}

// Fetches an update from server
AjaxList.prototype.updateFromServer = function() {

    // IE 6 doesn't handle rendering the whole page well.
    var fullPage = isIE6;

    var parameters = {"json" : JSON.stringify(this.state), "fullPage" : false};
    var url = this.webroot + this.controller + "/" + controllerAjaxListFunctionName;

    if (!fullPage) {
        new Ajax.Request(url,
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

    // Return false to indicate "do not proceed" for button clicks
    return false;
}

AjaxList.prototype.ajaxCallComplete = function (response) {
    if (response.status != 200 || !response.responseJSON) {
        alert("Can not update list: network down or bad server response.");
    } else {
        //  Set the new data up
        this.data = response.responseJSON.entries;
        this.count = response.responseJSON.count;
        this.clearTable();
        this.renderTable();
    }
}


/********** Action Display for Ajax List **********/
function AjaxListActionMenu(x, y, root, columns, entry, actions) {
    this.x = x;
    this.y = y;
    this.root = root;
    this.columns  = columns;
    this.entry = entry;
    this.actions = actions;
    this.display = null;
    this.render();
}

AjaxListActionMenu.prototype.close = function() {
    if (this.display) {
        document.body.removeChild(this.display);
        this.display = null;
    }
}


AjaxListActionMenu.prototype.render = function() {
    this.display = new Element("div");
    this.display.style.backgroundColor = "white";
    this.display.style.position = "absolute";
    this.display.style.left = this.x + "px";
    this.display.style.top = this.y + "px";
    this.display.style.order = "solid 1px";
    this.display.style.margin = "3px";
    this.display.style.padding = "3px";
    this.display.style.border = "solid 1px";

    var table = new Element("table");
    var tbody = new Element("tbody");

    // Create the summary table in the pop-up
    for (i = 0; i < this.columns.length; i++) {
        var tr = new Element("tr");

        var td = new Element("td");

        if (this.columns[i][1] !== undefined) {
            var columnTitle = this.columns[i][1];
        } else {
            var columnTitle = this.columns[i][0];
        }

        td.appendChild(document.createTextNode(columnTitle + ": "));
        tr.appendChild(td);

        td = new Element("td");
        // Get the actual entry name
        var column = this.columns[i];
        var split = column[0].split(".", 2);
        var contents = this.entry[split[0]][split[1]];

        // Is this a map-type column? Should be translate it?
        if (column[TYPE_COL] !== undefined && column[TYPE_COL] == "map") {
            if (column[MAP_COL][contents] !== undefined) {
                contents = column[MAP_COL][contents];
            } else {
                contents = "(unknown) " + contents;
            }
        }
        td.appendChild(document.createTextNode(contents));
        tr.appendChild(td);

        tbody.appendChild(tr);
    }

    table.appendChild(tbody);
    this.display.appendChild(table);

    // Create buttons for actions
    for (i = 0; i < this.actions.length; i++) {
        var action = this.actions[i];

        var button = new Element("input",{"type":"submit","value":action[0]});
        button.onclick = ajaxListLibrary.createDelegateWith1Param(this, this.performAction, action);
        this.display.appendChild(button);
    }

    var closeButton = new Element("input",{"type":"submit","value":"Close"});
    closeButton.onclick = ajaxListLibrary.createDelegate(this, this.close);
    this.display.appendChild(closeButton);

    document.body.appendChild(this.display);
}


AjaxListActionMenu.prototype.performAction = function(event, action) {
    if (action[1] != "") {
        if (!confirm(action[1])) {
            this.close();
            return;
        }
    }

    // Figure out the proper URL
    var url = "";
    for (j = 2; j < action.length; j++) {
        urlPart = action[j];
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
    window.location = this.root + url;
}