<table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
  <?php if (!empty($errmsg)): ?>
  <div class="error_message"><?php echo $errmsg; ?></div>
  <?php endif; ?>
<form action="<?php echo $html->url('/bakers/index'); ?>" method="POST">

    <h4>Model name:</h4>
    <p>
        <?php echo $html->input('Baker/name', ''); ?> <span>(Use singular name. Example: Post)</span>
        <?php echo $html->tagErrorMsg('Baker/name', 'Model name is required!'); ?>
    </p>

    <h4>Bake type:</h4>
    <ul>
        <?php
            $opts = array(
                'empty' => 'Empty <span>(Will create empty view files.)</span>',
                'scaffold' => 'Scaffold <span>(Will use "scaffold" in the controller file and don\'t create any view file.)</span>',
                'popular' => 'Popular <span>(Will create popular CRUD controller codes and view files.)</span>',
                );
            foreach ($opts as $value => $title) {
                echo '<li><input type="radio" name="data[Baker][type]" id="type_'.$value.'" value="'.$value.'"';
                if ($value == $params['data']['Baker']['type'])
                    echo ' checked="checked"';
                echo ' />&nbsp;<label for="type_'.$value.'">'.$title.'</label></li>';
            }   //end foreach
        ?></ul>
    <h4>Bake files:</h4>
    <ul>
        <li><?php echo $html->checkbox('Baker/bakeModel'); ?> <label for="tag_bakeModel">Model</label></li>
        <li><?php echo $html->checkbox('Baker/bakeController'); ?> <label for="tag_bakeController">Controller</label></li>
        <li><?php echo $html->checkbox('Baker/bakeView'); ?> <label for="tag_bakeView">View</label>
        <li><b>Actions:</b> <?php echo $html->input('Baker/actions', array('size'=>60))?><br />
        <span>(For controller and view. Separated by a comma)</span></li>
    </ul>
    <h4>If file exists:</h4>
    <ul>
        <li><?php echo $html->checkbox('Baker/overwrite'); ?> <label for="tag_overwrite">Overwrite!</label>
          <br />
    </li>
    </ul>
    <div>
        <?php echo $html->submit('Bake !')  ?>
    </div>
</form></td>
  </tr>
</table>
