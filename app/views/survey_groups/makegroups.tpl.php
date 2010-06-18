<?php if (count($data) > 0) { ?>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
    <td align="center">
    <form id="searchForm" action="">
    <center>
    <!-- Ensure that there's at least on Suevey to choose from -->
        <h3>Please Select a Survey to make Groups: </h3>
        <select name="survey_select">
        <?php
        for ($i=0; $i < count($data); $i++) {
            echo '<option value="'.$data[$i]['Survey']['id'].'">'.$data[$i]['Survey']['name'].'</option>';
        }
        ?>
        </select>
    </center>
    <br /> <br />
    <?php echo $ajax->observeForm('searchForm', array('update'=>'survey_makegroups_table', 'url'=>"makegroupssearch", 'frequency'=>1, 'loading'=>"Element.show('loading');", 'complete'=>"Element.hide('loading');"))  ?>  </form>
    <a name="list"></a>
    <div id='survey_makegroups_table'>
        <?php
        $params = array('controller'=>'surveygroups', 'paging'=>!empty($paging)? $paging: null);
        echo $this->renderElement('survey_groups/ajax_survey_makegroups', $params);
        ?>
    </div>
    </td>
    </tr>
    </table>
<?php } else {  // if (count($data) > 0) ?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"><tr><td>
    <center><h3> No Surveys found for this course!</h3>
    Please create a survey first, and have your students complete it.
    <br /><br />
    <input type="button" value="Go Back" onClick="javascript:window.history.back()">
</td></tr></table>
<?php } // if (count($data) > 0)?>