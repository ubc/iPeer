 <?php  
   class Article extends AppModel { 
          var $name = 'Article'; 
           
          function published($fields = null) { 
              $params = array( 
                    'conditions' => array(
                          $this->name . '.published' => 1 
                    ),
                    'fields' => $fields
              ); 
               
              return $this->find('all',$params); 
          }

            function getByUsername($id) {
    return $this->find('first', array('conditions' => array('id' => $id,
                                                            )));
  }
          
  	function weeWa($stuff)
  	{
  		$this->log($stuff);
  	}
   
   } 
 ?> 
