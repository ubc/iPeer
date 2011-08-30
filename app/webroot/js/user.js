
  function addToCourse() {
      if (total_course_count == 0) {
        alert("No course has been created in iPeer.  Please add new instructor course.");
      }
      else if (parseInt(document.frm.add.value) >= total_course_count) {
          alert("No more courses available.");
      } else {
          document.frm.add.value = (parseInt(document.frm.add.value)+1);
    }

    return false;
  }

  function removeFromCourse() {
    if ((parseInt(document.frm.add.value)-1) < 0 ) {
        document.frm.add.value = 0;
        alert("No more courses to remove.");
    } else {
        document.frm.add.value = parseInt(document.frm.add.value)-1;
    }
    return false;
  }