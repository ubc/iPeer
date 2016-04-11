<div id="portEvent">
<h2><?php echo __('Instructions', true) ?></h2>
<ul>
    <li><?php echo __('The (column) header row is optional in the import file',true); ?></li>
    <li><?php echo __('To assign groups, either enter * for all groups, or enter them as a <strong>semi-</strong>colon (;) separated list of group <strong>ids</strong>. Group ids are listed below.',true); ?></li>
    <li><?php echo __('Evaluation types are shown by their id: 1=simple; 2=rubric; 3=survey; 4=mixed',true); ?></li>
    <li><?php echo __('Event templates are entered by their id. To get it, go to the template\'s edit page and check the URL for the number.',true); ?></li>
    <li><?php echo __('For Student Result Mode: 0=basic (grades only); 1=detailed (grades and comments)',true); ?></li>
    <li><?php echo __('For the other 0 or 1 fields: 0=no/off; 1=on/yes',true); ?></li>
</ul>
    
<h2><?php echo __('Groups', true) ?></h2>
    <p>Group IDs are listed here for convenience.</p>
    <p><button class="groupslisttoggle">Show / Hide Groups</button></p>
    <script type="text/javascript">
        jQuery().ready(function() {
            jQuery('.groupslisttoggle').click(function() {
                jQuery('.groupslist').toggle();
            });
        });
    </script>
    <table class="groupslist" style="display:none;">
        <?php 
            echo $this->Html->tableHeaders(array('Group ID', 'Name')); 
            foreach($groups as $groupId => $groupName) {
                echo "<tr><td>$groupId</td><td>$groupName</td></tr>";
            }
        ?>
    </table>
    
<h2><?php echo __('Import Events', true) ?></h2>
<p><?php echo __('To generate an import file template, go to the export events page.',true); ?></p>
<?php 
echo $this->Form->create('ImportEvents', array('url' => '/'.$this->params['url']['url'],'type' => 'file'));
echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
?>
<?php echo $this->Form->submit(__('Import', true));
echo $this->Form->end();
?>
    
</div>
