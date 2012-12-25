<?php

/**
 * Vocabulary
 *
 * @uses AppHelper
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class VocabularyHelper extends AppHelper
{
    /**
     * translate
     * Translate a word from vacabulary settings
     *
     * @param mixed $voc word to translate
     *
     * @access public
     * @return void
     */
    function translate($voc)
    {
        $sysParam = ClassRegistry::init('SysParameter');

        return $sysParam->get('display.vocabulary.'.$voc, $voc);
    }
}
