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
        <script>
            var groupHasSubmittedEvaluation = <?php echo !empty($groupHasSubmittedEvaluation) ?
                $groupHasSubmittedEvaluation : "false" ?>;

            // Prevents group changes after submission
            function checkedAssign(a, b) {
                // Make sure something is selected in the sourse list
                if (a.selectedIndex >= 0) {
                    if (groupHasSubmittedEvaluation) {
                        var msg = "Are you sure you wish to add the person(s) to this group?\n";
                        msg += "Since this group has submitted evaluations, you will not be able\n";
                        msg += "to remove them afterwards! \n\nSo please be sure before hitting 'OK', and\n";
                        msg += "*absolutely* sure before you press the 'Update Group' button.\n";
                        if (confirm(msg)) {
                            itemMove(a,b);
                        }
                    } else {
                        itemMove(a,b);
                    }
                }
            }

            // Prevents group changes after submission
            function checkedRemove(a, b) {
                if (a.selectedIndex >= 0) {
                    if (groupHasSubmittedEvaluation) {
                        var msg = "Sorry, since this group has submitted evaluations,\n";
                        msg += "it can not have members removed. Members can only be added.\n\n";
                        msg += "However, if you did not save your changes, yet \n";
                        msg += "'you can still press Cancel Changes' to undo.";
                        alert(msg);
                    } else {
                        itemMove(a,b);
                    }
                }
            }

        </script>
        <input type="button" style="width:100px;" onClick="checkedAssign($('all_groups'), $('selected_groups'))" value="Assign >>" />
        <br><br>
        <input type="button" style="width:100px;" onClick="checkedRemove($('selected_groups'), $('all_groups'))" value="<< Remove " /></td>
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