<div id='UserMergeForm'>
<h2><?php echo __('Instructions', true);?></h2>
<ul>
    <li><?php echo __('Search for the primary account on the left by name, username, or student number.', true); ?></li>
    <li><?php echo __('Search for the secondary account on the right by name, username, or student number.', true); ?></li>
    <li><?php echo __('WARNING: The merger of two accounts cannot be undone.', true); ?></li>
</ul>
<h2><?php echo __('Merge Accounts', true); ?></h2>
<div id='form'>
<?php echo $this->Form->create('User',
    array('onsubmit' => 'return confirm("Are you sure you want to merge the two users? The merger cannot be undone.");')); ?>
<!-- secondary account -->
<?php echo $this->element('users/merge_search', array('account' => 'secondary', 'searchValue' => $searchValue)); ?>
<!-- primary account -->
<?php echo $this->element('users/merge_search', array('account' => 'primary', 'searchValue' => $searchValue)); ?>
<?php echo $this->Form->end(array('label' => __('Merge', true), 'id' => 'merge')); ?>
</div>
</div>

<script type="text/javascript">
disableButton();
disableSelection('#UserPrimaryAccount');
disableSelection('#UserSecondaryAccount');
jQuery().ready(function() {
    jQuery.each(['Primary', 'Secondary'], function(i, account) {
        jQuery('#User' + account + 'SearchValue').keydown(function(e) {
            if (e.keyCode == 13) {
                // Enter searches. It must not also submit the merge form.
                e.preventDefault();
                search(account);
            }
        });
        jQuery('#User' + account + 'Account').change(function() {
            showDetails(account, jQuery(this).val());
        });
    });
    jQuery("select").change(disableButton);
});

// generate the options for the users fields
function populate(selections, update, empty) {
    var select = jQuery(update).empty();
    var prompt = selections.length === 0 ?
        '-- No users found --' : '-- Pick the ' + empty + ' account --';
    select.append(jQuery('<option value="">').text(prompt));
    jQuery.each(selections, function(index, value) {
        select.append(jQuery('<option>').val(index).text(value));
    });
    disableSelection(update);
}

function disableButton() {
    var empty = false;
    jQuery("select").each(function() {
        if(jQuery(this).val() == '') {
            empty = true;
        }
    });
    if (empty) {
        jQuery('#merge').attr('disabled', 'disabled');
    } else {
        jQuery('#merge').removeAttr('disabled');
    }
}

// The number of the latest request of each kind for each account. A slow
// answer to an older request must not replace the answer to a newer one.
var searches = {Primary: 0, Secondary: 0};
var details = {Primary: 0, Secondary: 0};

function search(account) {
    var name = account.toLowerCase();
    var select = '#User' + account + 'Account';
    var field = jQuery('#User' + account + 'Search option:selected').val();
    var value = jQuery('#User' + account + 'SearchValue').val();
    var current = ++searches[account];
    jQuery(select).html('<option value>Searching ...</option>');
    jQuery(select).attr('disabled', 'disabled');
    jQuery.getJSON('ajax_merge', {action: 'account', field: field, value: value})
        .done(function(users) {
            if (current === searches[account]) {
                populate(users, select, name);
            }
        })
        .fail(function() {
            if (current === searches[account]) {
                jQuery(select).html('<option value>-- Search failed --</option>');
            }
        });
    showDetails(account, '');
    //disable the merge button if the search button is pressed
    jQuery('#merge').attr('disabled', 'disabled');
}

// Shows the details of the picked user, or none when no user is picked.
function showDetails(account, userId) {
    var name = account.toLowerCase();
    var current = ++details[account];
    jQuery('#' + name + 'Data td').text('');
    if (!userId) {
        return;
    }
    jQuery.getJSON('ajax_merge', {action: 'data', userId: userId}, function(field) {
        if (current === details[account]) {
            jQuery.each(field, function(index, value) {
                jQuery('td#' + name + index).text(value);
            });
        }
    });
}

function disableSelection(account) {
    var length = jQuery(account + ' option').length;
    if (length > 1) {
        jQuery(account).removeAttr('disabled');
    } else {
        jQuery(account).attr('disabled', 'disabled');
    }
}

</script>
