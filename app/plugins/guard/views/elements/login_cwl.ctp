<?php if(!$is_logged_in):?>
<table id="cwl-login" class="standardtable">
<tr>
    <td>For Students</td>
    <td>
    <a href="<?php echo $login_url?>">
      <IMG SRC="https://www.auth.cwl.ubc.ca/CWL_login_button.gif " WIDTH="76" HEIGHT="25" ALT="CWL Login" BORDER="0">
    </a>
    </td>
</tr>
<tr>
    <td>For Others</td>
    <td>
    <a href="<?php echo $html->url('/login?auth_method=default')?>">
        <?php echo $html->image("layout/ipeer_login.gif",array("align"=>"absmiddle", "alt"=>"iPeer Login", "border"=>"0"))?>
    </a>
    </td>
</tr>
</table>
<?php else: ?>
    <?php echo $html->link('Logout', Router::url(array('plugin' => 'guard', 'controller' => 'guard', 'action' => 'logout'), true))?>
<?php endif;?>
