<?php  
/**
 * AttributeBehavior Attribute behavior (http://bakery.cakephp.org/articles/taylor.luk/2008/11/04/attributebehavior-dry-and-powerful)
 * 
 * @uses ModelBehavior
 * @package 
 * @version $id$
 * @copyright Copyright (C) 2006 Andrei Zmievski
 * @author Tobias Schlitt <toby@php.net> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */

class AttributeBehavior extends ModelBehavior { 
    function setup(&$model, $config = array()) { 
        if (is_string($config)) 
            $config = array($config); 

        $this->settings[$model->alias] = $config;    
    } 
     
    function afterFind(&$model, $results = array(), $primary = false) { 
        $attributes = $this->settings[$model->alias]; 
         
        if ($primary && isset($results[0][$model->alias])) { 
            foreach($results as $i => $result) { 
                foreach ($attributes as $attr) { 
                    if (method_exists($model, $attr) && !is_null($tmp = $model->$attr($result))) { 
                        $results[$i][$model->alias][$attr] = $tmp; 
                    }  
                } 
            } 
        }  
        elseif (isset($results[$model->alias])) { 
            foreach ($attributes as $attr) { 
                if (method_exists($model, $attr) &&  !is_null($tmp = $model->$attr($result))) { 
                    $results[$model->alias][$attr] = $tmp;  
                } 
            } 
        } 
        return $results; 
    } 
}  
