<?php
/**
 * Breadcrumb manage breadcrumb on the page
 *
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 * @version   Release: 3.0
 */
class Breadcrumb
{
    protected $breadcrumb = array();
    protected $urlMapping = array(
        // mapping without display_field and key, mapping key will be used for
        // display text
        'courses' => array(
            'url' => '/courses',
        ),
        'course' => array(
            'url' => '/courses/home/',
            'display_field' => 'full_name',
            'key' => 'id',
        ),
        'groups' => array(
            'url' => '/groups/index/',
            'key' => 'course_id'
        ),
        'mixevals' => array(
            'display' => 'Mixed Evaluations',
            'url' => '/mixevals',
        ),
        'surveys' => array(
            'url' => '/surveys',
        ),
        'survey' => array(
            'url' => '/surveys/index/',
            'display_field' => 'name',
            'key' => 'course_id'
        ),
        'event' => array(
            'url' => '/events/index/',
            'display_field' => 'title',
            'key' => 'course_id'
        ),
        'event_student' => array(
            'url' => '/home',
            'display_field' => 'title',
        ),
        'home_student' => array(
            'url' => '/home',
            'display' => 'Home',
        ),
        'simple_evaluations' => array(
            'display' => 'Simple Evaluations',
            'url' => '/simpleevaluations'
        ),
        'rubrics' => array(
            'display' => 'Rubrics',
            'url' => '/rubrics'
        ),
    );

    /**
     * create factory method
     *
     * @static
     * @access public
     * @return Breadcrumb
     */
    static public function create()
    {
        return new Breadcrumb();
    }

    /**
     * push push one level in breadcrumb
     *
     * @param mixed $data
     *
     * @access public
     * @return self
     */
    public function push($data)
    {
        array_push($this->breadcrumb, $data);
        return $this;
    }

    /**
     * pop pop one leve out
     *
     * @access public
     * @return data for last level
     */
    public function pop()
    {
        return array_pop($this->breadcrumb);
    }

    /**
     * render render the breadcrumb into html
     *
     * @param mixed $html
     *
     * @access public
     * @return string the rendered html
     */
    public function render($html)
    {
        $result = array();

        foreach ($this->breadcrumb as $data) {
            // primitive variables, display directly
            if (!is_array($data) && !array_key_exists($data, $this->urlMapping)) {
                $result[] = $data;
            } else {
                if (!is_array($data)) {
                    $data = array($data => null);
                }
                $display = $url = "";
                $key = array_keys($data);
                $key = $key[0];
                if (isset($this->urlMapping[$key])) {
                    // we have a pre-defined mapping
                    if (isset($this->urlMapping[$key]['display'])) {
                        $display = $this->urlMapping[$key]['display'];
                    } else {
                        $display = isset($this->urlMapping[$key]['display_field']) ?
                            $data[$key][$this->urlMapping[$key]['display_field']] : Inflector::humanize($key);
                    }
                    $url = (isset($this->urlMapping[$key]['url']) ? $this->urlMapping[$key]['url'] : "").
                        (isset($this->urlMapping[$key]['key']) && isset($data[$key][$this->urlMapping[$key]['key']]) ? $data[$key][$this->urlMapping[$key]['key']] : "");
                } else {
                    // not defined in mapping, use custom parameters
                    $display = Inflector::humanize($key);
                    $url = $data[$key];
                }
                $result[] = $html->link($display, $url);
            }
        }

        return join(' > ', $result);
    }
}
