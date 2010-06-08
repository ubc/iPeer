<!-- elements::ajax_event_template_lists end -->
<?php
  //$templateID = null;
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
  		<select name="data[Event][template_id]" id="template_id" style="width:200px;" >
  			<?php foreach($eventTemplates as $row): $template = $row[$model];?>
  		  <option value="<?php echo trim($template['id']) ?>"<?php
  				  if (isset($templateID) && $template['id'] == $templateID) {
  				       echo ' selected';
  				  }
  				  ?>><?php echo $template['name'] ?></option>
  			<?php endforeach; ?>
  		</select>
      
      <script type="text/javascript" language="javascript">
        function getSimpleIndex(obj) {
          index = document.getElementById("template_id").selectedIndex;
          obj.href = "<?php echo $this->webroot.$this->themeWeb;?>simpleevaluations/view/"+(++index)+"/pop_up";
        }
        function getRubricIndex(obj) {
          index = document.getElementById("template_id").selectedIndex;
          obj.href = "<?php echo $this->webroot.$this->themeWeb;?>rubrics/view/"+(++index)+"/pop_up";
        }
        function getMixIndex(obj) {
          index = document.getElementById("template_id").selectedIndex;
          obj.href = "<?php echo $this->webroot.$this->themeWeb;?>mixevals/view/"+(++index)+"/pop_up";
        }
      </script>
    <?php
    }
    //echo '<br><br>';
    if ($model == 'SimpleEvaluation') { ?>
      <a title="Simple Evaluation Preview" href="<?php echo $this->webroot.$this->themeWeb;?>simpleevaluations/view/1/pop_up" onclick="getSimpleIndex(this); wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Preview</a>
    <?php } 
    else if ($model == 'Rubric') { ?>
      <a title="Rubric Preview" href="<?php echo $this->webroot.$this->themeWeb;?>rubrics/view/1/pop_up" onclick="getRubricIndex(this); wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Preview</a>
    <?php } 
    else if ($model == 'Mixeval') { ?>
      <a title="Mix Evaluation Preview" href="<?php echo $this->webroot.$this->themeWeb;?>mixevals/view/1/pop_up" onclick="getMixIndex(this); wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Preview</a>
    <?php } ?>
<?php  }
  else {

    echo $html->selectTag('Event/template_id', array('-1'=>$default), isset($templateID)?$templateID:$templateID=null, null, null, false);

    //echo '<br><br>';
    if ($model == 'SimpleEvaluation')
    { ?>
    <a title="Rubric Preview" href="<?php echo $this->webroot.$this->themeWeb;?>simpleevaluations/view/1/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Preview</a>
    <?php } else  if ($model == 'Rubric'){ ?>
    <a title="Preview Rubric" href="<?php echo $this->webroot.$this->themeWeb;?>rubrics/view/1/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Preview </a>
    <?php } else  if ($model == 'Mixeval'){ ?>
    <a title="Preview Rubric" href="<?php echo $this->webroot.$this->themeWeb;?>mixevals/view/1/pop_up" onclick="wopen(this.href, 'popup', 650, 500); return false;">&nbsp;Preview</a>
    <?php } ?>
<?php  }?>  
<!-- elements::ajax_event_template_lists end -->
