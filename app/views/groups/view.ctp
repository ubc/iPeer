<table class='standardtable'>
<tr>
    <th><?php __('Group Number'); ?></th>
    <th><?php __('Group Name'); ?></th>
</tr>
<tr>
    <td><?php echo $data['Group']['group_num']; ?></td>
    <td><?php echo $data['Group']['group_name']; ?></td>
</tr>
</table>

<h2>Group Members</h2>
<table class='standardtable'>
<tr>
    <th>Name</th>
    <th>Write Email</th>
    <th>User Details</th>
</tr>
<?php foreach($data['Member'] as $user):?>
<tr>
    <td><?php echo $user['full_name']?></td>
    <td><?php echo $html->link(
            __('Email to ', true).$user['full_name'],
            array(
                'controller' => 'emailer',
                'action' => 'write',
                'U/'.$user['id']
            ),
            array('escape'=>false)
        )?></td>
    <td><?php echo $html->link(
            $html->image('icons/view.gif', array('alt'=>'Detail')),
            '/users/view/'.$user['id'],
            array('escape' => false)
        )?></td>
</tr>
<?php endforeach; ?>
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
<?php echo $html->link(__('Back to Group Listing', true), '/groups/index/'.$data['Group']['course_id']); ?>
</p>
