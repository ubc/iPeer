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
            // TODO: add checking if user has Canvas OAuth2 token
            if (User::hasPermission('controllers/Courses/add')) {
                echo $html->link( __('Link Course with Canvas', true),
                    '/courses/link', array('class' => 'add-button'));
            }
            ?>
            </li>
        </ul>
    </div>

    <div>
    <?php echo $this->element("list/ajaxList", $paramsForList);?>
    </div>
</div>
