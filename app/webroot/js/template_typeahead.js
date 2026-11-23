// Makes a template select of the event form into one search box.
//
// The select stays in the form, hidden, and holds the value that the form
// posts. Typing filters the templates by their text, "Name (ID x)", so a name
// or an ID finds a template, and the list marks the match. The list keeps the
// headings of the select ("My templates", "Other templates").
//
// The box and the select must always agree: sync() checks the box text against
// the select on blur, when the list closes, and before the form posts.
//
// Uses the jQuery UI 1.8 autocomplete widget. Check this file when jQuery UI
// is upgraded: 1.10 renames the widget's data key, and 1.12 changes the menu
// markup that _renderMenu and _renderItem write.
var templateTypeahead = (function ($) {
    var ENTER = 13;

    // The headings (optgroups) of the select, also the empty ones.
    function groupsOf(select) {
        return select.find('optgroup').map(function () {
            return $(this).attr('label');
        }).get();
    }

    // The options of the select, with the heading (optgroup) of each.
    function itemsOf(select) {
        var items = [];
        select.find('option').each(function () {
            var option = $(this);
            var group = option.parent('optgroup');
            items.push({
                label: option.text(),
                value: option.text(),
                id: option.val(),
                group: group.length ? group.attr('label') : null
            });
        });
        return items;
    }

    // The label as a link of the menu, with the first match of the term in a
    // <mark>. Built from text nodes, so a template name is never read as HTML.
    function markedLabel(label, term) {
        var link = $('<a></a>');
        var at = term ? label.toLowerCase().indexOf(term) : -1;
        if (at < 0) {
            return link.text(label);
        }
        return link
            .append(document.createTextNode(label.slice(0, at)))
            .append($('<mark></mark>').text(label.slice(at, at + term.length)))
            .append(document.createTextNode(label.slice(at + term.length)));
    }

    // texts: the translated 'label' and 'placeholder' of the box, and 'empty',
    // the line under a heading with no templates
    return function (selector, texts) {
        var select = $(selector);
        if (!select.length || !$.fn.autocomplete) {
            return;
        }
        var form = select.closest('form');
        var groups = groupsOf(select);
        var items = itemsOf(select);
        var term = ''; // the filter of the open list, for the marks
        var selectedText = function () {
            return select.find('option:selected').text();
        };
        var exactMatch = function (text) {
            return $.grep(items, function (item) {
                return item.label === text;
            })[0];
        };
        var hasFocus = function () {
            return document.activeElement === input[0];
        };

        var id = select.attr('id') + 'Search';
        var input = $('<input type="text" class="template-typeahead">')
            .attr({
                id: id,
                'aria-label': texts.label,
                autocomplete: 'off',
                placeholder: texts.placeholder
            })
            .val(selectedText())
            .insertAfter(select);

        // Make the select agree with the box: the full text of a template
        // picks it; any other text gives way to the selected template.
        function sync() {
            var text = input.val();
            if (text === selectedText()) {
                return;
            }
            var match = exactMatch(text);
            if (match) {
                // change() runs the form's own handlers, such as the
                // Preview link update
                select.val(match.id).change();
            } else {
                input.val(selectedText());
            }
        }

        input.autocomplete({
            minLength: 0,
            delay: 0,
            source: function (request, response) {
                // While the box shows the selected template, list every
                // template, so a click on the box shows all the choices.
                term = request.term === selectedText() ? '' : request.term.toLowerCase();
                response($.grep(items, function (item) {
                    return item.label.toLowerCase().indexOf(term) !== -1;
                }));
            },
            // The arrow keys only highlight a template; the box keeps the
            // typed text until the user picks one.
            focus: function () {
                input.attr('aria-activedescendant', widget.menu.active.attr('id'));
                return false;
            },
            select: function (event, ui) {
                input.val(ui.item.label);
                select.val(ui.item.id).change();
                return false;
            },
            open: function () {
                // Give the list the same font as the box. The jQuery UI theme
                // sets its own font on the list (Lucida Grande, 1.1em), so
                // without this the templates show larger than the box text.
                // The box's computed font is copied, not a fixed size,
                // because each browser draws form fields in its own font.
                widget.menu.element.css({
                    fontFamily: input.css('font-family'),
                    fontSize: input.css('font-size')
                });
                // jQuery UI 1.8 marks the list as a menu; it is a list box
                widget.menu.element.attr('role', 'listbox');
                widget.menu.element.children('.ui-menu-item').attr('role', 'option');
                widget.menu.element
                    .children('.ui-autocomplete-category, .ui-autocomplete-empty')
                    .attr('role', 'presentation');
                input.attr('aria-expanded', 'true');
            },
            close: function () {
                input.attr('aria-expanded', 'false').removeAttr('aria-activedescendant');
                // closed by a click elsewhere, which skips the widget's blur
                if (!hasFocus()) {
                    sync();
                }
            }
        });

        var widget = input.data('autocomplete') || input.data('ui-autocomplete');
        if (!widget) {
            input.remove();
            return;
        }
        select.hide();

        // after the widget starts: jQuery UI 1.8 sets role="textbox"
        input.removeAttr('aria-haspopup').attr({
            role: 'combobox',
            'aria-autocomplete': 'list',
            'aria-expanded': 'false',
            'aria-controls': id + 'List'
        });
        widget.menu.element.attr('id', id + 'List');
        // the menu removes its highlight past the first or last template
        var deactivate = widget.menu.deactivate;
        widget.menu.deactivate = function () {
            input.removeAttr('aria-activedescendant');
            return deactivate.apply(this, arguments);
        };

        // A heading before the templates of each group. The full list shows
        // every heading, and a line under a group with no templates; a search
        // shows only the groups with a match. The headings and lines have no
        // ui-menu-item class, so the arrow keys skip them.
        widget._renderMenu = function (ul, list) {
            var self = this;
            var heading = function (group) {
                $('<li class="ui-autocomplete-category"></li>').text(group).appendTo(ul);
            };
            var rest = list;
            if (term === '') {
                $.each(groups, function (i, group) {
                    var inGroup = $.grep(list, function (item) {
                        return item.group === group;
                    });
                    heading(group);
                    if (!inGroup.length) {
                        $('<li class="ui-autocomplete-empty"></li>').text(texts.empty).appendTo(ul);
                    }
                    $.each(inGroup, function (j, item) {
                        self._renderItem(ul, item);
                    });
                });
                // options outside the groups, if the select has any
                rest = $.grep(list, function (item) {
                    return !item.group;
                });
            }
            var group = null;
            $.each(rest, function (i, item) {
                if (item.group && item.group !== group) {
                    group = item.group;
                    heading(group);
                }
                self._renderItem(ul, item);
            });
        };
        widget._renderItem = function (ul, item) {
            return $('<li></li>')
                .attr('id', id + 'Option' + item.id)
                .data('item.autocomplete', item)
                .append(markedLabel(item.label, term))
                .appendTo(ul);
        };

        // Focus selects the text, so the first key starts a new search and
        // Tab or Escape leaves the template as it is. A click that gives the
        // focus would clear the selection on its mouseup, so that mouseup is
        // cancelled.
        var focusedByMouse = false;
        input.bind('mousedown', function () {
            focusedByMouse = !hasFocus();
        });
        input.bind('focus', function () {
            input.select();
        });
        input.bind('mouseup', function (event) {
            if (focusedByMouse) {
                event.preventDefault();
                focusedByMouse = false;
            }
        });

        // A click opens the full list. Focus alone does not, so that moving
        // through the form with Tab does not open it.
        input.bind('click', function () {
            if (!widget.menu.element.is(':visible')) {
                input.autocomplete('search', input.val());
            }
        });

        // jQuery UI 1.8 searches only on key presses: also on paste and cut
        input.bind('input', function () {
            if (hasFocus()) {
                input.autocomplete('search', input.val());
            }
        });

        // Enter on a search, with no template highlighted, does not post the
        // form: it picks the template with that exact text, or opens the list.
        input.bind('keydown', function (event) {
            if (event.keyCode !== ENTER || event.isDefaultPrevented()) {
                return;
            }
            if (input.val() === selectedText()) {
                return;
            }
            event.preventDefault();
            if (exactMatch(input.val())) {
                sync();
            } else if (!widget.menu.element.is(':visible')) {
                input.autocomplete('search', input.val());
            }
        });

        // After the widget's own blur, which closes the list 150 ms later.
        input.bind('blur', function () {
            setTimeout(function () {
                if (!hasFocus() && !widget.menu.element.is(':visible')) {
                    sync();
                }
            }, 200);
        });

        // Before the form posts: a click on Submit can come before the blur
        // check. A search that is not a template stops the post and opens the
        // list, so the user picks one.
        form.bind('submit', function (event) {
            if (input.val() === selectedText()) {
                return;
            }
            if (exactMatch(input.val()) || !input.is(':visible')) {
                sync();
                return;
            }
            event.preventDefault();
            input.focus();
            input.autocomplete('search', input.val());
        });
    };
}(jQuery));
