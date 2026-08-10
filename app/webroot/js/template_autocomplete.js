/**
 * Turns an evaluation template <select> into a searchable jQuery UI
 * autocomplete text box with a "Mine only" filter.
 *
 * The original <select> stays in the DOM (hidden) and remains the field that
 * is submitted, so the form still posts the template id and existing code that
 * reads or watches the select by id - updatePreview(), the Preview links -
 * keeps working. The text box is only a search interface over the options.
 *
 * @param selectSelector jQuery selector for the template select
 * @param mineIds        array of template ids created by the current user
 */
function initTemplateAutocomplete(selectSelector, mineIds) {
    var select = jQuery(selectSelector);
    if (!select.length) {
        return;
    }

    var mine = {};
    for (var i = 0; i < mineIds.length; i++) {
        mine[String(mineIds[i])] = true;
    }

    // the full list comes from the select's own options, so there is no second
    // copy of the template names to keep in sync
    var all = [];
    select.find('option').each(function () {
        var option = jQuery(this);
        if (option.val() === '') {
            return; // the "-- Select a template --" placeholder
        }
        all.push({label: option.text(), value: option.val()});
    });

    var input = jQuery('<input />').attr({
        'type': 'text',
        'class': 'templateAutocomplete',
        'autocomplete': 'off',
        'placeholder': 'Click or type to search templates'
    });
    var mineOnly = jQuery('<input />').attr({
        'type': 'checkbox',
        'class': 'templateMineOnly',
        'id': select.attr('id') + 'MineOnly'
    });
    var mineOnlyLabel = jQuery('<label />')
        .attr({'class': 'templateMineOnlyLabel', 'for': mineOnly.attr('id')})
        .text('Only show templates I created');

    // input and filter share one inline-block, so the filter line starts at
    // the input's left edge no matter how wide the form label is
    select.hide();
    jQuery('<span />').addClass('templateField')
        .append(input)
        .append(jQuery('<span />').addClass('templateFilter')
            .append(mineOnly).append(mineOnlyLabel))
        .insertAfter(select);

    // the Preview link is this field's label - it points nowhere until a
    // template is picked, so hide it while the selection is empty. Only the
    // link is hidden, its label keeps its place in the layout.
    var previewLink = select.parent().find('a.templatePreviewLink');
    function updatePreviewVisibility() {
        var value = select.val();
        previewLink.toggle(value !== '' && value !== null);
    }
    select.change(updatePreviewVisibility);

    function currentSource() {
        if (!mineOnly.is(':checked')) {
            return all;
        }
        return jQuery.grep(all, function (template) {
            return mine[template.value] === true;
        });
    }

    function labelFor(value) {
        for (var i = 0; i < all.length; i++) {
            if (all[i].value === String(value)) {
                return all[i].label;
            }
        }
        return '';
    }

    function setTemplate(value) {
        select.val(value);
        input.val(labelFor(value));
        select.change(); // keeps the Preview links up to date
    }

    function matches(term) {
        if (!term) {
            return currentSource();
        }
        var matcher = new RegExp(jQuery.ui.autocomplete.escapeRegex(term), 'i');
        return jQuery.grep(currentSource(), function (template) {
            return matcher.test(template.label);
        });
    }

    input.autocomplete({
        minLength: 0,
        source: function (request, response) {
            var found = matches(request.term);
            if (!found.length) {
                // the menu has no built-in empty state - show one, and mark it
                // so it cannot be picked
                found = [{label: 'No matching templates', value: '', noResults: true}];
            }
            response(found);
        },
        select: function (event, ui) {
            if (ui.item.noResults) {
                return false;
            }
            setTemplate(ui.item.value);
            return false; // we set the label ourselves, item.value is the id
        },
        focus: function () {
            return false; // arrowing the menu must not put the id in the box
        }
    });

    // grey out the "no matches" row so it does not look selectable
    input.data('autocomplete')._renderItem = function (ul, item) {
        return jQuery('<li />')
            .addClass(item.noResults ? 'templateNoResults' : '')
            .data('item.autocomplete', item)
            .append(jQuery('<a />').text(item.label))
            .appendTo(ul);
    };

    // clicking the box offers the whole (filtered) list, like a dropdown
    input.click(function () {
        input.autocomplete('search', input.val() === '' ? '' : input.val());
    });

    // Enter means "I am done typing", not "submit the form". If exactly one
    // template matches what was typed, take it.
    input.keydown(function (event) {
        if (event.keyCode !== jQuery.ui.keyCode.ENTER) {
            return;
        }
        event.preventDefault();
        if (input.data('autocomplete').menu.active) {
            return; // the menu handled it, an item was highlighted
        }
        var found = matches(input.val());
        if (found.length === 1) {
            setTemplate(found[0].value);
        }
        input.autocomplete('close');
    });

    // the select is the source of truth - discard anything typed that was not
    // picked from the menu
    input.blur(function () {
        input.val(labelFor(select.val()));
    });

    mineOnly.change(function () {
        var selected = select.val();
        if (mineOnly.is(':checked') && selected && mine[String(selected)] !== true) {
            setTemplate(''); // current pick is not the user's own - clear it
        }
        input.autocomplete('close');
    });

    input.val(labelFor(select.val()));
    updatePreviewVisibility();
}
