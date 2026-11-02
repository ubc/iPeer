<?php
App::import('Core', 'I18n');

/**
 * Tests for how I18n keys its translation cache when no language is set.
 * L10n::get() then returns null, and I18n uses that value as an array key,
 * which PHP has always treated as ''.
 *
 * @package app.tests.cases.libs
 */
class I18nCacheKeyTestCase extends CakeTestCase
{
    function startTest($method)
    {
        $this->I18n =& I18n::getInstance();
        $this->saved = array(
            'lang' => $this->I18n->__lang,
            'domains' => $this->I18n->__domains,
            'config' => Configure::read('Config.language'),
            'session' => isset($_SESSION['Config']['language']) ? $_SESSION['Config']['language'] : null,
        );
        $this->I18n->__lang = null;
        $this->I18n->__domains = array();
        Configure::delete('Config.language');
        unset($_SESSION['Config']['language']);
    }

    function endTest($method)
    {
        $this->I18n->__lang = $this->saved['lang'];
        $this->I18n->__domains = $this->saved['domains'];
        Configure::write('Config.language', $this->saved['config']);
        if ($this->saved['session'] !== null) {
            $_SESSION['Config']['language'] = $this->saved['session'];
        }
        unset($this->I18n);
    }

    function testUntranslatedStringWithNoLanguage()
    {
        $this->assertIdentical('No translation for this', __('No translation for this', true));
    }

    function testNoLanguageCachesDomainUnderEmptyStringKey()
    {
        __('No translation for this', true);

        $this->assertTrue(empty($this->I18n->__lang));
        $this->assertTrue(isset($this->I18n->__domains['default']));
        $this->assertIdentical(array(''), array_keys($this->I18n->__domains['default']));
    }
}
