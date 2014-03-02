<?php
class PageFactory
{
    public static function initElements($session, $page)
    {
        require_once(TESTS.'cases/system/pages/'.$page.'Page.php');
        $className = $page.'Page';
        $pageObject = new $className($session);

        self::proxyFields($session, $pageObject);

        return $pageObject;
    }

    /**
     * proxyFields
     * initialize all elements defined in elements array
     *
     * @param mixed $session webdriver session
     * @param mixed $page    current page object
     *
     * @static
     * @access public
     * @return void
     */
    public static function proxyFields($session, $page)
    {
        if (empty($page->elements)) {
            return;
        }

        foreach ($page->elements as $method => $vars) {
            foreach ($vars as $key => $var) {
                $search = is_numeric($key) ? $var : $key;
                $page->$var = $session->elementWithWait($method, $search);
            }
        }
    }
}
