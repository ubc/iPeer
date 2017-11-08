<?php 

if (empty($courseId) || empty($canvasCourseId)) : 
    
    echo $this->Form->create(null, array("id" => "syncCanvasForm", "url" => $formUrl ));
    echo $this->Form->input("canvasCourse", array("label"=>"From Canvas Course", "multiple" => false));
    echo $this->Form->input("Course", array("label"=>"Into iPeer Course", "multiple"=>false, "default" => $courseId));
    ?><label class="defLabel"></label><?php
    echo $this->Form->submit(__("Next", true));
    echo $this->Form->end();
    
elseif ($showSuccessMessage): 

    echo 'Click <a href="' . $formUrl . '">here</a> to go back to the sync wizad.';

elseif ($showImportInterface): 

    echo $this->Form->create(null, array("id" => "syncCanvasForm", "url" => $formUrl));

    ?>
    <table id="syncCanvasTable">
        <thead>
            <tr>
                <th>
                    <h3>iPeer</h3>
                    <a id="selectAlliPeerGroups" href="#">select all</a>
                    <a id="selectNoneiPeerGroups"  href="#">select none</a>
                </th>
                <th>&nbsp;</th>
                <th>
                    <h3>Canvas</h3>
                    <a id="selectAllCanvasGroups" href="#">select all</a>
                    <a id="selectNoneCanvasGroups"  href="#">select none</a>
                </th>
            </tr>
        </thead>
        <tbody><?php

        foreach ($groupsAndUsers as $row) { ?>

            <tr>
                <td>
                    <?php if (isset($row['Group'])): ?>
                    <table class="standardtable">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo $this->Form->checkbox('iPeerGroup.' . $row['Group']['group_name'], array('hiddenField' => false)); ?>
                                    <?php echo $row['Group']['group_name']; ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody><?php
                    foreach ($row['Member'] as $user) {
                        ?>
                            <tr>
                                <td>
                                    <?php if ($user['isInCanvasCourse']) : ?>
                                        <input type="checkbox" readonly="readonly" onclick="return false;"/>
                                        <span title="<?php echo $user['username']; ?>">
                                            <?php echo $user['full_name']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="disabled" title="<?php echo $user['username']; ?>">
                                            <?php echo $user['full_name'] . ' *'; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php
                    }
                    ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </td>
                <td class="syncIcon">
                    <?php if (isset($row['CanvasGroup']) && isset($row['Group'])): ?>
                        &harr;
                    <?php elseif (isset($row['Group'])): ?>
                        &rarr;
                    <?php else: ?>
                        &larr;
                    <?php endif; ?>    
                </td>
                <td>
                    <?php if (isset($row['CanvasGroup'])): ?>
                    <table class="standardtable">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo $this->Form->checkbox('canvasGroup.' . $row['CanvasGroup']['group_name'], array('hiddenField' => false)); ?>
                                    <?php echo $row['CanvasGroup']['group_name']; ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody><?php
                    foreach ($row['CanvasMember'] as $user) {
                        ?>
                            <tr>
                                <td>
                                    <?php if ($user['isIniPeer']) : ?>
                                        <input type="checkbox" readonly="readonly" onclick="return false;"/>
                                        <span title="<?php echo $user[$canvasUserKey]; ?>">
                                            <?php echo $user['full_name']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="disabled" title="<?php echo $user[$canvasUserKey]; ?>">
                                            <?php echo $user['full_name'] . ' *'; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php
                    }
                    ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </td>
            </tr>

        <?php } ?>

        </tbody>
        <tfoot>
            <tr>
                <td>
                    <span style="warning">* Students marked with an asterisk do not have a corresponding account in Canvas and therefore will not be exported into the group.</span>
                    <br>
                    <?php echo $this->Form->input('updateCanvasGroups', array('label'=>'Replace group, rather than merge.', 'type'=>'checkbox')); ?>
                    <br>&nbsp;<br>
                    <?php echo $this->Form->input("canvasGroupCategories", array("label"=>"Group set to export to", "multiple" => false)); ?>
                </td>
                <td>&nbsp;</td>
                <td>
                    <span style="warning">* Students marked with an asterisk do not have an associated iPeer account and therefore will not be imported into the group.</span>
                    <br>
                    <?php echo $this->Form->input('updateGroups', array('label'=>'Replace group, rather than merge.', 'type'=>'checkbox')); ?>
                </td>
            </tr>
            <tr class="submit-buttons">
                <td>
                    <?php echo $this->Form->button(__("-&gt; Export selected groups to Canvas", true), array("onclick" => "jQuery('#GroupSyncType').val('export'); jQuery('#syncCanvasForm').submit();")); ?>
                </td>
                <td>&nbsp;</td>
                <td>
                    <?php echo $this->Form->button(__("&lt;- Import selected groups from Canvas", true), array("onclick" => "jQuery('#GroupSyncType').val('import'); jQuery('#syncCanvasForm').submit();")); ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <?php echo $this->Form->hidden('syncType'); ?>

    <br>

    <style type="text/css">
        #syncCanvasTable {
            width: 100%;
        }

        #syncCanvasTable > thead tr th:first-child,
        #syncCanvasTable > thead tr th:last-child {
            width: 48%;
        }

        #syncCanvasTable > thead th a {
            font-size: 0.8em;
        }
        
        #syncCanvasTable > * > tr td {
            vertical-align: top;
        }
        
        #syncCanvasTable > tbody tr td .disabled {
            color: #999;
        }

        #syncCanvasTable > tbody tr td.syncIcon {
            text-align: center;
            font-size: 2em;
            line-height: 1.2em;
        }
        
        #syncCanvasTable input[type="checkbox"] {
            float: left;
            margin: 0 -1em 0 1em;
        }
        
        #syncCanvasTable tfoot input[type="checkbox"] {
            margin: 9px 8px 0 1em;
        }
        
        #syncCanvasTable tfoot .checkbox {
            margin: 1em auto;
            float: none;
            max-width: 18.5em;
        }
        #syncCanvasTable tfoot .checkbox label {
            clear: none;
            width: 15em;
            text-align: left;
        }
        
        #syncCanvasTable .submit-buttons td {
            text-align: center;
            padding-top: 1em;
        }

    </style>

    <script type="text/javascript">
        jQuery('#syncCanvasTable table th input[type="checkbox"]').change(function(){
            jQuery(this).parents('table.standardtable').find('tr input[type="checkbox"]').prop('checked', (jQuery(this).is(':checked')));
        });
    </script>
    
<?php  

    echo $this->Form->end();
endif; ?>

</div>
