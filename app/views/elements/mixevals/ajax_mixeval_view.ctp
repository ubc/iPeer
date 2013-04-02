<?php echo $html->script('evalviewer');?>
<div id="evalviewer"></div>

<script type="text/javascript">
document.observe('dom:loaded', function() { 
  var editor = new EvalViewer("evalviewer",
                              <?php echo json_encode($data['Question'])?>,
                              <?php echo $data['Mixeval']['zero_mark']?>
                              );
});
</script>
