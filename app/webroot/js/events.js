

 // Ensures that the dates for events are valid
 function validateEventDates(eventBegin, eventEnd, EventDue, resultBegin, resultEnd) {
     // Get the proper event strings from Paramemters

    var due = $(EventDue).value;
    var release = $(eventBegin).value;
    var finish = $(eventEnd).value;
    var resultRelease = $(resultBegin).value;
    var resultFinish = $(resultEnd).value;

    // Warn in there are empty
    if (!due) {
        alert("Due date empty. Please Enter a valid date."); return false;
    }

    if (!release) {
        alert("Evaluation Release From date empty. Please Enter a valid date."); return false;
    }

    if (!finish) {
        alert("Evalutaion Release To date empty. Please Enter a valid date."); return false;
    }

    if (!resultRelease) {
        alert("Result Release From date empty. Please Enter a valid date."); return false;
    }

    if (!resultFinish) {
        alert("Result Release To date empty. Please Enter a valid date."); return false;
    }

    // Get the dates from the field in a milisecond number format
    var dueDate = Date.parse($(EventDue).value.replace(/-/g,"/"));
    var releaseDate = Date.parse($(eventBegin).value.replace(/-/g,"/"));
    var finishDate = Date.parse($(eventEnd).value.replace(/-/g,"/"));
    var resultReleaseDate = Date.parse($(resultBegin).value.replace(/-/g,"/"));
    var resultFinishDate = Date.parse($(resultEnd).value.replace(/-/g,"/"));

     // First, check for valid entries
     if (isNaN(dueDate)) {
         $(EventDue).focus(); alert("Invalid Due Date Format: " + $(EventDue).value);
     } else if (isNaN(releaseDate)) {
         $(eventBegin).focus(); alert("Invalid Evaluation Release From Date Format: " + $(eventBegin).value);
     } else if (isNaN(finishDate)) {
         $(eventEnd).focus(); alert("Invalid Evaluation Release To Date Format: " + $(eventEnd).value);
     } else if (isNaN(resultReleaseDate)) {
         $(resultBegin).focus(); alert("Invalid Result Release To Date Format: " + $(resultBegin).value);
     } else if (isNaN(resultFinishDate)) {
         $(resultEnd).focus(); alert("Invalid Result Release To Date Format: " + $(resultEnd).value);
     } else if (finishDate < releaseDate) {
         // Then check the relationships between dates
         // Check release dates, and due dates
         $(eventEnd).focus(); alert("Evaluation Release To: must be *after* From date.");
     } else if (resultFinishDate < resultReleaseDate) {
         $(resultEnd).focus(); alert("Result Release To: must be *after* From date.");
     } else if (finishDate < dueDate) {
         $(EventDue).focus(); alert("Due Date: must be *before* Release To date.");
     } else if (releaseDate > dueDate) {
         $(EventDue).focus(); alert("Due Date: must be *after* Release From date.");
     } else if (resultReleaseDate < dueDate) {
         $(EventDue).focus(); alert("Result Release From: must be *after* Due Date.");
     } else {
         // All okay
         return true;
     }
     // In all other cases, return false
     return false;
 }