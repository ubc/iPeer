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

    function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    $divisionName = "ajaxListDiv";
    // The main div containing the controll
    echo "<div id='$divisionName'>";

    //Check all the parameters
    if (!function_exists('json_encode') || !function_exists('json_decode')) {
        $message = "<b>".__('You need the PHP json extension.', true)."</b><br />";
        $message.= __("  Please use PHP >= 5.2 and enable it in php.ini <br />", true);
        $message.= __("  (uncomment or create the line:<br />", true);
        $message.= "<tt>extension=json.so</tt> )";
        showProgrammerError($message);
    } else if (empty($paramsForList['controller'])) {
        showProgrammerError('$paramsForList[controller]'. __(' variable not set or empty!', true));
    } else if (empty($paramsForList['webroot'])) {
        showProgrammerError('$paramsForList[webroot]'. __(' variable not set or empty!', true));
    } else if (empty($paramsForList['columns'])) {
        showProgrammerError('$paramsForList[columns]'.__(' variable not set or empty!', true));
    } else {

        // Start Up the element
        $variables = json_encode(utf8ize($paramsForList));

        echo $html->script("ajaxList");
        echo $html->scriptBlock("var ajaxList = new AjaxList({$variables},'$divisionName')");

    }

    echo "</div>";
