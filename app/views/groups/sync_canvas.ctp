<div id="syncCanvasWrapper"><?php 

if (empty($courseId) || empty($canvasCourseId)) : 
    
    echo $this->Form->create(null, array("id" => "syncCanvasForm", "url" => $formUrl ));
    echo $this->Form->input("canvasCourse", array("label"=>"From Canvas Course", "multiple" => false));
    echo $this->Form->input("Course", array("label"=>"Into iPeer Course", "multiple"=>false, "default" => $courseId));
    ?><label class="defLabel"></label><?php
    echo $this->Form->submit(__("Next", true));
    echo $this->Form->end();

else: 

    echo $this->Form->create(null, array("id" => "syncCanvasForm", "url" => $formUrl));

    $javascript->link(Router::url('/js/synccanvas.js', true), false);

    ?>
    <table id="syncCanvasTable" data-nummembers="<?php echo $numMembersToShow; ?>">
        <thead>
            <tr>
                <th id="iPeerHeading">
                    <h3>iPeer</h3>
                    <a class="selectAll" href="#">select all</a>
                    <a class="selectNone" href="#">select none</a>
                    <a class="collapseAll" href="#">collapse all</a>
                    <a class="expandAll" href="#">expand all</a>
                </th>
                <th>&nbsp;</th>
                <th id="canvasHeading">
                    <h3>Canvas (<?php echo $canvasCourseName; ?>)</h3>
                    <a class="selectAll" href="#">select all</a>
                    <a class="selectNone" href="#">select none</a>
                    <a class="collapseAll" href="#">collapse all</a>
                    <a class="expandAll" href="#">expand all</a>
                </th>
            </tr>
        </thead>
        <tbody><?php

        foreach ($groupsAndUsers as $row) { ?>

            <tr>
                <td>
                    <?php if (isset($row['Group'])): ?>
                    <table class="standardtable iPeerGroup">
                        <thead>
                            <tr>
                                <th class="expanded-after <?php if(isset($row['Group']['justAdded']) && $row['Group']['justAdded']){ echo ' highlight-green'; } ?>">
                                    <?php echo $this->Form->checkbox('iPeerGroup.' . $row['Group']['group_name'], array('hiddenField' => false)); ?>
                                    <?php echo $row['Group']['group_name']; ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($row['Member']) && !empty($row['Member'])): ?>
                                <?php foreach ($row['Member'] as $user) { ?>
                                <tr>
                                    <td <?php if(isset($user['justAdded']) && $user['justAdded']){ echo ' class="highlight-green"'; } ?>>
                                        <?php if ($user['isInCanvasCourse']) : ?>
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
                                <?php } ?>
                                <?php if (count($row['Member']) > $numMembersToShow): ?>
                                    <tr class="showMoreLessMembers">
                                        <td>
                                            <a href="#" class="showMinMembers" style="display:none;">show <?php echo $numMembersToShow; ?></a> &nbsp; 
                                            <a href="#" class="showLessMembers" style="display:none;">show less</a> &nbsp; 
                                            <a href="#" class="showMoreMembers">show more</a> &nbsp; 
                                            <a href="#" class="showAllMembers">show all</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                <tr>
                                    <td>
                                        <span class="disabled">
                                            (empty)
                                        </span>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
                    <table class="standardtable canvasGroup">
                        <thead>
                            <tr>
                                <th class="expanded-after <?php if(isset($row['CanvasGroup']['justAdded']) && $row['CanvasGroup']['justAdded']){ echo ' highlight-green'; } ?>">
                                    <?php echo $this->Form->checkbox('canvasGroup.' . $row['CanvasGroup']['group_name'], array('hiddenField' => false)); ?>
                                    <?php echo $row['CanvasGroup']['group_name']; ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($row['CanvasMember']) && !empty($row['CanvasMember'])): ?>
                            <?php foreach ($row['CanvasMember'] as $user) { ?>
                                <tr>
                                    <td <?php if(isset($user['justAdded']) && $user['justAdded']){ echo ' class="highlight-green"'; } ?>>
                                        <?php if ($user['isIniPeer']) : ?>
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
                            <?php } ?>
                            <?php if (count($row['CanvasMember']) > $numMembersToShow): ?>
                                <tr class="showMoreLessMembers">
                                    <td>
                                        <a href="#" class="showMinMembers" style="display:none;">show <?php echo $numMembersToShow; ?></a> &nbsp; 
                                        <a href="#" class="showLessMembers" style="display:none;">show less</a> &nbsp; 
                                        <a href="#" class="showMoreMembers">show more</a> &nbsp; 
                                        <a href="#" class="showAllMembers">show all</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <span class="disabled">
                                        (empty)
                                    </span>
                                </td>
                            </tr>
                        <?php endif; ?>
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
                    <?php echo $this->Form->button(__("Export selected groups to Canvas <span class='syncIcon'>&rarr;</span>", true), array("class" => "submit", "onclick" => "jQuery('#GroupSyncType').val('export'); jQuery('#syncCanvasForm').submit();")); ?>
                </td>
                <td>&nbsp;</td>
                <td>
                    <?php echo $this->Form->button(__("<span class='syncIcon' style='float:left;'>&larr;</span> Import selected groups from Canvas", true), array("class" => "submit", "onclick" => "jQuery('#GroupSyncType').val('import'); jQuery('#syncCanvasForm').submit();")); ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <?php echo $this->Form->hidden('syncType'); ?>

    <br>

    <script type="text/javascript">
    </script>
    
<?php  

    echo $this->Form->end();
endif; ?>

</div>
