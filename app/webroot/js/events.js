

 // Ensures that the dates for events are valid
 function validateEventDates(eventBegin, eventEnd, EventDue) {
     // Get the proper event strings from Paramemters

     // Get the dates from the field in a milisecond number format
     var dueDate = Date.parse($(EventDue).value.replace(/-/g,"/"));
     var releaseDate = Date.parse($(eventBegin).value.replace(/-/g,"/"));
     var finishDate = Date.parse($(eventEnd).value.replace(/-/g,"/"));

     // First, check for valid entries
     if (isNaN(dueDate)) {
         $(EventDue).focus(); alert("Invalid Due Date Format: " + $(EventDue).value);
     } else if (isNaN(releaseDate)) {
         $(eventBegin).focus(); alert("Invalid Release From Date Format: " + $(eventBegin).value);
     } else if (isNaN(finishDate)) {
         $(eventEnd).focus(); alert("Invalid Release To Date Format: " + $(eventEnd).value);
     } else if (finishDate < releaseDate) {
         // Then check the relationships between dates
         // Check release dates, and due dates
         $(eventEnd).focus(); alert("Release To: must be *after* Release From date.");
     } else if (finishDate < dueDate) {
         $(EventDue).focus(); alert("Due Date: must be *before* Release To date.");
     } else if (releaseDate > dueDate) {
         $(EventDue).focus(); alert("Due Date: must be *after* Release From date.");
     } else {
         // All okay
         return true;
     }
     // In all other cases, return false
     return false;
 }