<div id='footer' class='pagewidth'>
  <p>
  <?php __('Powered by iPeer and TeamMaker - Created by UBC and Rose-Hulman')?>
  </p>
  <?php if (!empty($ipeerCommitHash)): ?>
  <p>
      Container image built from Git commit <?php echo $ipeerCommitHash; ?>
      <a href="https://github.com/ubc/iPeer/commit/<?php echo $ipeerCommitHash; ?>">(Github)</a>
  </p>
  <?php endif; ?>
</div>
