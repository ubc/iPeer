<!-- Load the JSON script for IE-->
<script src="<?php echo $this->webroot ?>js/json2.js"></script>


<!-- If this is ie6, set a JS variable for this-->
<script>var isIE6 = false;</script>
<!--[if IE 6]>
<script>isIE6 = true;</script>
<![endif]-->
<!-- =-=-=-=-=-=-=-==-=-=-=-=-=-=-=-=-= -->

<?php

    function showProgrammerError ($message) {
        echo "<span style='color:red'>ajaxList: $message</span><br />";
    }

    $divisionName = "ajaxListDiv";

    // The main div containing the controll
    echo "<div id='$divisionName' style='text-align: left; margin: 0 1em 1em 1em'>";

    //Check all the parameters
    if (!function_exists('json_encode') || !function_exists('json_decode')) {
        $message = "<b>You need the PHP json extension.</b><br />";
        $message.= "  Please use PHP >= 5.2 and enable it in php.ini <br />";
        $message.= "  (uncomment or create the line:<br />";
        $message.= "<tt>extension=json.so</tt> )";
        showProgrammerError($message);
    } else if (empty($paramsForList['controller'])) {
        showProgrammerError('$paramsForList[controller] variable not set or empty!');
    } else if (empty($paramsForList['webroot'])) {
        showProgrammerError('$paramsForList[webroot] variable not set or empty!');
    } else if (empty($paramsForList['columns'])) {
        showProgrammerError('$paramsForList[columns] variable not set or empty!');
    } else {

        // Start Up the element
        $variables = json_encode($paramsForList);

        echo $html->script("ajaxList");
        echo $html->scriptBlock("var ajaxList = new AjaxList($variables,'$divisionName')");

    }

    echo "</div>";

?>
