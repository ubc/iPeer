<?php echo $html->script('evaleditor');?>

<div id="evaleditor"></div>

<script type="text/javascript">
document.observe('dom:loaded', function() { 
  var editor = new EvalEditor("evaleditor",
                              <?php echo json_encode($data['Question'])?>,
                              {delete_question_url: "<?php echo $this->Html->url('deleteQuestion')?>/",
                               delete_descriptor_url: "<?php echo $this->Html->url('deleteDescriptor')?>/",
                              });
});
</script>
