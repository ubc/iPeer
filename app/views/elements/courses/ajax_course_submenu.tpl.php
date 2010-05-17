		<table width="100%" class="title2" border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td><?php echo $html->image('layout/grey_arrow.gif',array('align'=>'middle','alt'=>'grey_arrow'))?>&nbsp; <?=$submenuTitle?></td>
            <td>
            <div align="right">
               <a href="<?=$this->webroot.$this->themeWeb?>courses/update/<?=$submenu?>"
                onclick="new Ajax.Updater('<?=ereg_replace(' ','_',$submenuTitle)?>','<?=$this->webroot.$this->themeWeb?>courses/update/<?=$submenu?>',
                                         {onLoading:function(request){Element.show('loading');},
                                          onComplete:function(request){Element.hide('loading');},
                                          asynchronous:true, evalScripts:true}); return false;">show/hide</a>
            </div></td>
        </tr>
        </table>
      <div id="<?=ereg_replace(' ','_',$submenuTitle)?>" style="background: #FFF;display:inline;"><?php
      $params = array('controller'=>'courses', 'userPersonalize'=>$userPersonalize, 'submenu'=>$submenu, 'submenuTitle'=>$submenuTitle, 'courseId'=>$rdAuth->courseId);
      echo $this->renderElement('courses/ajax_personalize_'.$submenu, $params);
      ?></div>