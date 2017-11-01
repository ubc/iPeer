<div class="content-container">
    <div class="button-row">
        <ul>
            <li>
            <?php
            if (User::hasPermission('controllers/Courses/add')) {
                echo $html->link( __('Add Course', true),
                    '/courses/add', array('class' => 'add-button'));
            }
            ?>
            </li>
            <li>
            <?php
            if (User::hasPermission('controllers/Courses/add') &&
                $canvasEnabled) {
                echo $html->link( __('Add Course Based on Canvas', true),
                    '/courses/add/selCanvas', array('class' => 'add-button'));
            }
            ?>
            </li>
        </ul>
    </div>

    <div>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
    </div>
</div>
