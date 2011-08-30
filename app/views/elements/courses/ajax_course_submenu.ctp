<table width="100%" class="title2" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td><?php echo $html->image('layout/grey_arrow.gif',array('valign'=>'middle','alt'=>'grey_arrow'))?>&nbsp; <?php echo $submenuTitle?></td>
    <td>
      <div align="right">
           <a href="<?php echo $this->webroot.$this->theme?>courses/update/<?php echo $submenu?>"
            onclick="new Ajax.Updater('<?php echo @ereg_replace(' ','_',$submenuTitle)?>','<?php echo $this->webroot.$this->theme?>courses/update/<?php echo $submenu?>',
                                     {onLoading:function(request){Element.show('loading');},
                                      onComplete:function(request){Element.hide('loading');},
                                      asynchronous:true, evalScripts:true}); return false;">show/hide</a>
        </div></td>
  </tr>
</table>

<div id="<?php echo @ereg_replace(' ','_',$submenuTitle)?>" style="background: #FFF;display:inline;">
<?php $params = array('controller'=>'courses', 'userPersonalize'=>$userPersonalize, 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'course_id'=>$course_id);
echo $this->element('courses/ajax_personalize_'.$submenu, $params);?>
</div>
