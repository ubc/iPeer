<!-- This element renders 2 list of groups, selected and unselected.-->

<?php
    function groupListChooser_fillGroupList($withArray, $itemName, $listStrings) {
        if (!empty($withArray) && !empty($itemName) && !empty($listStrings)) {
            foreach ($withArray as $row) {
                $item = $row[$itemName];
                echo "<option value=$item[id]>";
                for ($i = 0; $i < count($listStrings); $i++) {
                    if (isset($item[$listStrings[$i]])) {
                        echo $item[$listStrings[$i]];
                    } else {
                        echo $listStrings[$i];
                    }
                }
                echo "</option>";
            }
        }
    }
?>

<?php
    $listStyle = empty($listSize) ? "min-width:13em; height:13em;" :
        "min-width:13em; height:" . $listSize . "em;";
?>
<center>
<table><tr align="center">
    <td align="center">
        <h3><?php echo empty($allName) ? "Groups" : $allName ?></h3>
        <select name="all_groups" id="all_groups" style="<?php echo $listStyle?>" multiple>
            <?php
                groupListChooser_fillGroupList ($all, $itemName, $listStrings);
            ?>
        </select>
    </td>
    <td width="100" align="center">
        <input type="button" style="width:100px;" onClick="itemMove($('all_groups'), $('selected_groups'))" value="Assign >>" />
        <br><br>
        <input type="button" style="width:100px;" onClick="itemMove($('selected_groups'), $('all_groups'))" value="<< Remove " /></td>
        <td align="center">

        <h3><?php echo empty($selectedName) ? "Selected Groups" : $selectedName ?></h3>
        <select name="selected_groups" id="selected_groups" style="<?php echo $listStyle?>" multiple>
            <?php
                $selected = empty($selected) ? array() : $selected;
                groupListChooser_fillGroupList ($selected, $itemName, $listStrings);
            ?>
        </select>
    </td>
</tr></table></center>