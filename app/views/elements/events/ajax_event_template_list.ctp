<!-- elements::ajax_event_template_lists begin -->
<?php
  //$templateID = null;
  $url = $this->webroot.$this->theme;
  $templates = array();
  if ($eventTemplates!=null) {
    if (isset($view) && $view) {
		  foreach($eventTemplates as $row): $template = $row[$model];
				  if (isset($templateID) && $template['id'] == $templateID) {
				     echo $template['name'];
				  }
			endforeach;
    }
    else {?>
<!--  		<select name="data[Event][template_id]" id="template_id" style="width:200px;" >
  			<?php foreach($eventTemplates as $row): $template = $row[$model];?>
  		    <option value="<?php echo trim($template['id']) ?>" <?php echo (isset($templateID) && $template['id'] == $templateID)?' selected':''?>>
            <?php echo $template['name'] ?>
          </option>
  			<?php endforeach; ?>
  		</select>-->
                <?php 
                  echo $this->Form->input('Event.template_id', array(
                  'type' => 'select',
                  'id' => 'template_id',
                  'style' => 'width:200px;',
                  'label' => false,
                  'options' => $eventTemplates,
                  'escape'=>false,
                  'format' => array('input')
              ));
                ?>

      <?php if ($model == 'SimpleEvaluation'):?>
        <a title=<?php __("Simple Evaluation Preview", true)?> target="_blank" href="javascript:void;" onclick="getIndex(this,'simpleevaluations', '<?php echo $url?>'); wopen(this.href, 'popup', 650, 500); return false;">&nbsp;<?php __('Preview This Simple Evaluation')?></a>
      <?php elseif ($model == 'Rubric'):?>
        <a title=<?php __("Rubric Preview")?> target="_blank" href="javascript:void;" onclick="getIndex(this,'rubrics', '<?php echo $url?>'); wopen(this.href, 'popup', 650, 500); return false;">&nbsp;<?php __('Preview This Rubric')?></a>
      <?php elseif ($model == 'Mixeval'):?>
        <a title=<?php __("Mix Evaluation Preview")?> target="_blank" href="javascript:void;" onclick="getIndex(this,'mixevals', '<?php echo $url?>'); wopen(this.href, 'popup', 650, 500); return false;">&nbsp;<?php __('Preview this Mixed Evaluaiton')?></a>
      <?php endif;?> 
    <?php
    }
  } else {
    if ($model == 'SimpleEvaluation') {
        $evalTypeString = __("Simple", true);
    } else if ($model == 'Rubric') {
        $evalTypeString = __("Rubric", true);
    } else if ($model == 'Mixeval') {
        $evalTypeString = __("Mixed", true);
    } else {
        $evalTypeString = "(".__('Unknown Evaluation Type')." : $model)";
    }

    printf (__("No %s Evaluations available. You need to create a %s Evaluation first! <br />", true), $evalTypeString, $evalTypeString);
    printf (__("<ul><li>Just Click on <i>Add %s Evaluation</i> above.</li></ul><br />", true), $evalTypeString);
    echo $form->hidden('ModelType', array('value' => $model));
}

?>
<!-- elements::ajax_event_template_lists end -->
