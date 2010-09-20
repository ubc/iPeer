
  function addInstructor() {
      if (total_instructor_count == 0) {
        alert("No instructor has been created in iPeer.  Please add new instructor first.");
      }
      else if (parseInt(document.frm.add.value) >= total_instructor_count) {
          alert("No more instructors available.");
      } else {
          document.frm.add.value = (parseInt(document.frm.add.value)+1);
    }

    return false;
  }

  function removeInstructor() {
    if ((parseInt(document.frm.add.value)-1) < 0 ) {
        document.frm.add.value = 0;
    } else {
        document.frm.add.value = parseInt(document.frm.add.value)-1;
    }
    return false;
  }