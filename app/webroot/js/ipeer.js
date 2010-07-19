function wopen(url, name, w, h)
{
  // Fudge factors for window decoration space.
  // In my tests these work well on all platforms & browsers.
  w += 32;
  h += 96;
  wleft = (screen.width - w) / 2;
  wtop = (screen.height - h) / 2;
  var win = window.open(url,
    name,
    'width=' + w + ', height=' + h + ', ' +
    'left=' + wleft + ', top=' + wtop + ', ' +
    'location=no, menubar=no, ' +
    'status=no, toolbar=no, scrollbars=yes, resizable=yes');
  // Just in case width and height are ignored
  win.resizeTo(w, h);
  // Just in case left and top are ignored
  win.moveTo(wleft, wtop);
  win.focus();
}

function showLimit(value, link, ajaxObj) {

    // a workaround for bug #191 in the users controller
    var position = window.location.toString().search("users/");
    if (position > 0) {
        var upTree = window.location.toString().slice(0, position);
    } else {
        var upTree = "../..";
    }

    var newLink = upTree + link + '&show=' + value;
    alert(newLink);

  new Ajax.Updater(ajaxObj,
                   newLink,
              { onLoading : function(request) {
                    Element.show('loading');
                },
                onComplete : function(request) {
                    Element.hide('loading');
                },
                asynchronous:true,
                evalScripts:true
              }
        );
  return false;
}

function show_hide_input(obj) {
  if (obj.selectedIndex == 3) {
    document.getElementById('threshold').style.visibility = 'visible';
  } else {
    document.getElementById('threshold').style.visibility = 'hidden';
  }
}
function OnChange(dropdown)
{

	var myindex  = dropdown.selectedIndex
	var SelValue = dropdown.options[myindex].value

  if (SelValue == 'S') {
    Element.show('course_select');
  } else {
    Element.hide('course_select');
  }

	return true;
}