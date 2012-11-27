<table class='standardtable'>
<tr>
    <th><?php __('Group Number'); ?></th>
    <th><?php __('Group Name'); ?></th>
    <th><?php __('Status'); ?></th>
</tr>
<tr>
    <td><?php echo $data['Group']['group_num']; ?></td>
    <td><?php echo $data['Group']['group_name']; ?></td>
    <td>
    <?php 
    if( $data['Group']['record_status'] == "A" ) 
        echo __("Active", true); 
    else 
        echo __("Inactive", true); 
    ?>
    </td>
</tr>
</table>

<?php 
    // data processing for each group member
    $groupMembers = array();
    foreach($group_data as $row) {
        $user = $row['User'];
        $tmp = array();

        $tmp[] = $user['full_name'];
        // second column is email link
        $tmp[] = $html->link(
            __('Email to ', true).$user['full_name'],
            array(
                'controller' => 'emailer',
                'action' => 'write',
                'U/'.$user['id']
            ),
            array('escape'=>false)
        );
        // third column is user details view link
        $tmp[] = $html->link(
            $html->image('icons/view.gif', array('alt'=>'Detail')),
            '/users/view/'.$user['id'],
            array('escape' => false)
        );
        $groupMembers[] = $tmp;
    }
?>

<h2>Group Members</h2>
<table class='standardtable'>                
<tr>
    <th>Name</th>
    <th>Write Email</th>
    <th>User Details</th>
</tr>
<?php echo $html->tableCells($groupMembers); ?>
<tr>
    <td colspan='3'>
    <?php 
    echo $html->link(
        $html->image('icons/email.gif', array('alt'=>'Email')) .
            __(' Email To All Members', true),
        array(
            'controller' => 'emailer',
            'action' => 'write',
            'G/'.$data['Group']['id']
        ),
        array('escape'=>false)
    );
    ?>
    </td>
</tr>
</table>

<p>
<?php echo $html->link(__('Edit this Group', true), '/groups/edit/'.$data['Group']['id']); ?> |
<?php echo $html->link(__('Back to Group Listing', true), '/groups/index/'.$course_id); ?>
</p>
