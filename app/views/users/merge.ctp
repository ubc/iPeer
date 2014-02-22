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
    var searching = '<option value>Searching ...</option>';
    jQuery('#UserPrimarySearchValue').keydown(function(e) {
        if (e.keyCode == 13) {
            jQuery('#UserPrimaryAccount').html(searching);
            primarySearch();
        }
    });
    jQuery('#UserSecondarySearchValue').keydown(function(e) {
        if (e.keyCode == 13) {
            jQuery('#UserSecondaryAccount').html(searching);
            secondarySearch();
        }
    });
    jQuery('#UserPrimaryAccount').change(function() {
        var userId = jQuery('#UserPrimaryAccount option:selected').val();
        jQuery.getJSON('ajax_merge', {action: 'data', userId: userId},
            function(field) {
                jQuery.each(field, function(index, value) {
                    jQuery('td#primary' + index).html(value);
                });
        });
    });
    jQuery('#UserSecondaryAccount').change(function() {
        var userId = jQuery('#UserSecondaryAccount option:selected').val();
        jQuery.getJSON('ajax_merge', {action: 'data', userId: userId},
            function(field) {
                jQuery.each(field, function(index, value) {
                    jQuery('td#secondary' + index).html(value);
                });
        });
    });
    jQuery("select").change(disableButton);
});

// generate the options for the users fields
function populate(selections, update, empty) {
    var options = '<option value>-- Pick the ' + empty + ' account --</option>';
    if (selections.length === 0) {
        options = '<option value>-- No users found --</option>';
    }
    jQuery.each(selections, function(index, value) {
        options += '<option value="' + index + '">' + value + '</option>';
    });
    jQuery(update).html(options);
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

function primarySearch() {
    var field = jQuery('#UserPrimarySearch option:selected').val();
    var value = jQuery('#UserPrimarySearchValue').val();
    jQuery('#UserPrimaryAccount').attr('disabled', 'disabled');
    jQuery.getJSON('ajax_merge', {action: 'account', field: field, value: value},
        function(users) {
            populate(users, '#UserPrimaryAccount', 'primary');
    });
    jQuery.getJSON('ajax_merge', {action: 'data', userId: ''},
        function(field) {
            jQuery.each(field, function(index, value) {
                jQuery('td#primary' + index).html(value);
            });
    });
    //disable the merge button if the search button is pressed
    jQuery('#merge').attr('disabled', 'disabled');
}

function secondarySearch() {
    var field = jQuery('#UserSecondarySearch option:selected').val();
    var value = jQuery('#UserSecondarySearchValue').val();
    jQuery('#UserSecondaryAccount').attr('disabled', 'disabled');
    jQuery.getJSON('ajax_merge', {action: 'account', field: field, value: value},
        function(users) {
            populate(users, '#UserSecondaryAccount', 'secondary');
    });
    jQuery.getJSON('ajax_merge', {action: 'data', userId: ''},
        function(field) {
            jQuery.each(field, function(index, value) {
                jQuery('td#secondary' + index).html(value);
            });
    });
    jQuery('#merge').attr('disabled', 'disabled');
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
