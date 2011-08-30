<div id="nav">
   <ul>
      <!-- Sub menu for Evaluation Event Tools -->
      <?php if (!empty($access['EVAL_TOOL'])) { 
              $evalTools = $access['EVAL_TOOL'];    ?>
              <li <?php if ($this->params['controller'] == 'evaltools') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$evalTools['url_link'];?>"><span>All My Tools</span></a> |</li>
      <?php }?>
      <?php if (!empty($access['SIMPLE_EVAL'])) {
              $simpleEvalSysFunc = $access['SIMPLE_EVAL'];    ?>
              <li <?php if ($this->params['controller'] == 'simpleevaluations') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$simpleEvalSysFunc['url_link'];?>"><span><?php echo $simpleEvalSysFunc['function_name']?> </span></a> |</li>
      <?php }?>
      <?php if (!empty($access['RUBRIC'])) {
              $rubricSysFunc = $access['RUBRIC'];    ?>
              <li <?php if ($this->params['controller'] == 'rubrics') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$rubricSysFunc['url_link'];?>"><span><?php echo $rubricSysFunc['function_name']?></span></a>|</li>
      <?php }?>
      <?php if (!empty($access['MIX_EVAL'])) {
              $mixEvalSysFunc = $access['MIX_EVAL'];    ?>
              <li <?php if ($this->params['controller'] == 'mixevals') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$mixEvalSysFunc['url_link'];?>"><span><?php echo $mixEvalSysFunc['function_name']?></span></a>|</li>
      <?php }?>
      <?php if (!empty($access['SURVEY'])) {
              $surveySysFunc = $access['SURVEY'];    ?>
              <li <?php if ($this->params['controller'] == 'surveys') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$surveySysFunc['url_link'];?>"><span><?php echo $surveySysFunc['function_name']?></span></a>|</li>
      <?php }?>
      <?php if (!empty($access['EMAIL'])) {
              $emailsSysFunc = $access['EMAIL'];    ?>
              <li <?php if ($this->params['controller'] == 'emailer') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$emailsSysFunc['url_link'];?>"><span><?php echo $emailsSysFunc['function_name']?></span></a>|</li>
      <?php }?>
      <?php if (!empty($access['EMAIL_TEMPLATE'])) {
              $emailtemplatesSysFunc = $access['EMAIL_TEMPLATE'];    ?>
              <li <?php if ($this->params['controller'] == 'emailtemplates') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.$this->theme.$emailtemplatesSysFunc['url_link'];?>"><span><?php echo $emailtemplatesSysFunc['function_name']?></span></a></li>
      <?php }?>
  </ul>
</div>
