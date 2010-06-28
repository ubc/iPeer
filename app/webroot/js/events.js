

 // Ensures that the dates for events are valid
 function validateEventDates() {
     // Get the dates from the field in a milisecond number format
     var dueDate = Date.parse($("EventDueDate").value.replace(/-/g,"/"));
     var releaseDate = Date.parse($("EventReleaseDateBegin").value.replace(/-/g,"/"));
     var finishDate = Date.parse($("EventReleaseDateEnd").value.replace(/-/g,"/"));

     // First, check for valid entries
     if (isNaN(dueDate)) {
         $("EventDueDate").focus(); alert("Invalid Due Date Format: " + $("EventDueDate").value);
     } else if (isNaN(releaseDate)) {
         $("EventReleaseDateBegin").focus(); alert("Invalid Release From Date Format: " + $("EventReleaseDateBegin").value);
     } else if (isNaN(finishDate)) {
         $("EventReleaseDateEnd").focus(); alert("Invalid Release To Date Format: " + $("EventReleaseDateEnd").value);
     } else if (finishDate < releaseDate) {
         // Then check the relationships between dates
         // Check release dates, and due dates
         $("EventReleaseDateEnd").focus(); alert("Release To: must be *after* Release From date.");
     } else if (finishDate < dueDate) {
         $("EventDueDate").focus(); alert("Due Date: must be *before* Release To date.");
     } else if (releaseDate > dueDate) {
         $("EventDueDate").focus(); alert("Due Date: must be *after* Release From date.");
     } else {
         // All okay
         return true;
     }
     // In all other cases, return false
     return false;
 }