<?php $columns = isset($columns) ? $columns :
    array('identifier' => $identifier, 'note' => $note);
?>

<table class='standardtable'>
    <tr>
    <?php
    foreach ($columns as $title) {
        echo "<th>$title</th>";
    }
    ?>
    </tr>
    <!-- Summary for import record creation -->
    <?php
    foreach ($data as $id => $note) {
        echo "<tr><td width=25%>".$id."</td>";
        echo "<td width=75%>".$note."</td></tr>";
    }
    ?>
</table>