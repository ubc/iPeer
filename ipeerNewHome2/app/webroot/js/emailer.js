//modified version of http://www.webmasterworld.com/forum91/4686.htm
//myField accepts an object reference, myValue accepts the text string to add
function insertAtCursor(myField, myValue) {
 //fixed scroll position
 textAreaScrollPosition = myField.scrollTop;
    //IE support
    if (document.selection) {
        myField.focus();
        //in effect we are creating a text range with zero
        //length at the cursor location and replacing it
        //with myValue
        sel = document.selection.createRange();
        sel.text = myValue;
    //Mozilla/Firefox/Netscape 7+ support
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        myField.focus();
        //Here we get the start and end points of the
        //selection. Then we create substrings up to the
        //start of the selection and from the end point
        //of the selection to the end of the field value.
        //Then we concatenate the first substring, myValue,
        //and the second substring to get the new value.
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
        myField.setSelectionRange(endPos+myValue.length, endPos+myValue.length);
    } else {
        myField.value += myValue;
    }
 //fixed scroll position
 myField.scrollTop = textAreaScrollPosition;

}