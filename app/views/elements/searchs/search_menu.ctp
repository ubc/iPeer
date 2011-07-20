<div id="nav">
   <ul>
      <!-- Sub menu for Evaluation Event Tools -->
      <li><?php echo $html->image('magnify.png', array('alt'=>'Magnify Icon'));?> <b>Search For:</b> </li>
      <li <?php if ($this->params['action'] == 'searchEvaluation') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.'searchs/searchEvaluation'?>"><span><?php __('Evaluation Events')?></span></a> |</li>
      <li <?php if ($this->params['action'] == 'searchResult') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.'searchs/searchResult'?>"><span><?php __('Evaluation Results')?></span></a> |</li>
      <li <?php if ($this->params['action'] == 'searchInstructor') echo 'class="current"'; ?> ><a href="<?php echo $this->webroot.'searchs/searchInstructor'?>"><span><?php __('Instructors')?></span></a></li>

  </ul>
</div>