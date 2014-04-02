<div id="perm_editor">
<?php
echo $form->input('role', array(
    'options' => $roles,
    'default' => $roleId,
    'label' => __('Role', true).':',
));
?>
<table id="table_id">
    <thead>
        <tr>
<?php
$columns = array('id', 'permission', 'create', 'read', 'update', 'delete');
foreach ($columns as $val) {
    echo "<th>".Inflector::humanize($val)."</th>";
}
?>
        </tr>
    </thead>
    <tbody>

<?php
$text = array(-1 => 'Deny', 1 => 'Allow');
$types = array('create', 'read', 'update', 'delete');
foreach ($permissions as $key => $perm) {
    echo "<tr>";
    echo "<td>".$perm['id']."</td>";
    echo "<td>".$key."</td>";
    foreach ($types as $type) {
        echo "<td>".$text[$perm[$type]]."</td>";
    }
    echo "</tr>";
}
?>
    </tbody>
</table>
</div>

<script type="text/javascript">

function fnFormatDetails (oTable, nTr)
{
    var aData = oTable.fnGetData(nTr);
    var roleId = "<?php echo $roleId ?>";
    var types = new Array("Create", "Read", "Update", "Delete");
    var actions = new Array();
    for (var i=2;i<6;i++) {
        if (aData[i] == 'Allow') {
            actions[i-2] = 'Deny';
        } else {
            actions[i-2] = 'Allow';
        }
    }

    var sOut = '<div class="userActionPanel"><ul>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/accesses/edit/allow/'+aData[0]+'/'+roleId+'"';
    sOut += 'onclick="return changeConfirmed(&quot;'+aData[1]+'&quot;)">Allow All</a>';
    sOut += '</li>';

    sOut += '<li>';
    sOut += '<a href="<?php echo $this->base; ?>/accesses/edit/deny/'+aData[0]+'/'+roleId+'"';
    sOut += 'onclick="return changeConfirmed(&quot;'+aData[1]+'&quot;)">Deny All</a>';
    sOut += '</li>';

    for (var i=0; i<4; i++) {
        sOut += '<li>';
        sOut += '<a href="<?php echo $this->base; ?>/accesses/edit/';
        sOut += actions[i].toLowerCase()+'/'+aData[0]+'/'+roleId+'/'+types[i].toLowerCase()+'"';
        sOut += 'onclick="return changeConfirmed(&quot;'+aData[1]+'&quot;)">'+actions[i]+' '+types[i]+'</a>';
        sOut += '</li>';
    }

    sOut += '</ul></div>';

    return sOut;
}

function changeConfirmed(access)
{
    var confirmed = confirm("Are you sure you want to change the access to "+access+"?");
    return confirmed;
}

jQuery(document).ready(function() {
    var oTable = jQuery("#table_id").dataTable( {
        "sPaginationType" : "full_numbers",
        "aoColumnDefs": [
            { "bSearchable": false, "bSortable": false, "aTargets": [2, 3, 4, 5] },
            { "bSearchable": false, "bVisible": false, "bSortable":false, "aTargets": [ 0 ] }
        ],
        "aaSorting" : [[1, 'asc']]
    });

    // for changing between roles
    jQuery("#role").change(function() {
        window.location.href="<?php echo $this->Html->url('/accesses/view')?>/" + jQuery(this).val();
    });

    // event listener for action links
    jQuery('#table_id tbody td').live('click', function() {
        var nTr = jQuery(this).parents('tr')[0];
        if (oTable.fnIsOpen(nTr)) {
            oTable.fnClose(nTr);
        } else {
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'userActionPanel' );
        }
    });
});

</script>
