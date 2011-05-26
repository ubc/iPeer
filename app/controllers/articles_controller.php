<?php 
class ArticlesController extends AppController { 
   var $name = 'Articles'; 
   var $helpers = array('Ajax', 'Form', 'Html'); 
   
   function index($short = null) { 
     if (!empty($this->data)) { 
       $this->Article->save($this->data); 
     } 
     if (!empty($short)) { 
       $result = $this->Article->findAll(null, array('id', 
          'title')); 
     } else { 
       $result = $this->Article->findAll(); 
     } 
 
     if (isset($this->params['requested'])) { 
       return $result; 
     } 
 
     $this->set('title', 'Articles'); 
     $this->set('articles', $result); 
   } 
} 
?>