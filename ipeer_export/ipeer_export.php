<?php
  define("FORMAT_XML", true);
  
  // helper function to get rows from a table, returns associative arrays for each row
  // returns an array with the results
  // returns an empty array if no results
  function getRows($db, $table)
  {
    $rows = array();
    $results = mysql_query('SELECT * FROM ' . $table);
    if($results) 
    {
      while($row = mysql_fetch_array($results, MYSQL_ASSOC))
      {
        $rows[] = $row;
      }
    }
    return $rows;
  }
  
  // checks if all tables passed in exist in the database
  // returns true if all tables exist in the database
  // returns false if at least one table does not exist in the database
  function tables_exist($db, $tables)
  {
    $db_tables = mysql_listtables($db);
    
    
    foreach($tables as $table)
    {
      foreach($db_tables as $db_table)
      {
        if($table != $db_table)
        {
          return false;
        } 
      }
    }
    return true;
  }
  
  function exportPhp4($db, $dbname, $tables)
  {
    $doc = domxml_new_doc('1.0');
    
    // create root node = database name
    $dbname_element = $doc->create_element($dbname);
    
    // create a new element for each table
    foreach($tables as $table)
    {
      $table_rows = getRows($db, $table);
      foreach($table_rows as $row)
      {
        $table_element = $doc->create_element($table);
        foreach($row as $fieldname => $value)
        {
          $row_element = $doc->create_element($fieldname);
          $row_element->append_child($doc->create_text_node($value));
          $table_element->append_child($row_element);
        }
        $dbname_element->append_child($table_element);
      }
    }
    $doc->append_child($dbname_element);
    
    $xml_content=$doc->dump_mem(FORMAT_XML);
    $xml_filename="ipeer_dump.xml";
    $xml_filesize=strlen($xml_content);
    
    header("HTTP/1.1 200 OK");
    header("Content-Disposition: attachment; filename=$xml_filename");
    header("Content-Length: $xml_filesize");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $xml_content;
  }
  
  function exportPhp5($db, $dbname, $tables)
  {
    $doc = new DOMDocument('1.0');
    
    // create root node = database name
    $dbname_element = $doc->createElement($dbname);
    
    
    // create a new element for each table
    foreach($tables as $table)
    {
      
      // get all rows
      $table_rows = getRows($db, $table);
      foreach($table_rows as $row)
      {
        $table_element = $doc->createElement($table);
        foreach($row as $fieldname => $value)
        {
          $row_element = $doc->createElement($fieldname);
          $row_element->appendChild($doc->createTextNode($value));
          $table_element->appendChild($row_element);
        }
        $dbname_element->appendChild($table_element);
      }
    }
    $doc->appendChild($dbname_element);
    
    $xml_content=$doc->dump_mem(FORMAT_XML);
    $xml_filename="ipeer_dump.xml";
    $xml_filesize=strlen($xml_content);
    
    header("HTTP/1.1 200 OK");
    header("Content-Disposition: attachment; filename=$xml_filename");
    header("Content-Length: $xml_filesize");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $xml_content;
  }




  /*
   * Beginning of form processing block
   */
  
  if($_POST['export_submit']=='Export iPeer Data')
  {
    // get database details
    $db_name = $_POST['db_name'];
    $db_host = $_POST['db_host'];
    $db_username = $_POST['db_username'];
    $db_password = $_POST['db_password'];
    
    // connect to database
    $db = mysql_connect($db_host, $db_username, $db_password);
    mysql_select_db($db_name);
    
    // some hardcoded data...for tables and such
    $tables = array('users', 'evaluation_types', 'simple_evaluations', 'assignments', 'courses', 
                    'rubrics', 'rubrics_criteria', 'rubrics_lom', 'rubrics_criteria_comments', 'enrols');
    
    // call the appropriate export handler depending on php version
    if(phpversion() < 5)
    {
      exportPhp4($db, $db_name, $tables);
    }
    else 
    {
      exportPhp5($db, $db_name, $tables); 
    }

    // cleanup
    mysql_close($db);
  }
  else
  {
?>

<html>
  <head>
    <title>iPeer Export Tool</title>
    <style type="text/css">
      body {
        text-align: center;
      }
      #content {
        margin-left: auto;
        margin-right: auto;
      }
    </style>      
  </head>
  <body>
    <div id="content">
      <h1>iPeer Export Tool</h1>
      <p>This tool is used to export data from iPeer 1.6 in xml format.</p>
      <div id="database_details">
        <form name="db" action="ipeer_export.php" method="post">
          <table>
            <tr>
              <td><label>Database host</label></td>
              <td><input type="text" name="db_host" value="<?php echo $db_host; ?>" /></td>
            </tr>
            <tr>
              <td><label>Database name</label></td>
              <td><input type="text" name="db_name" value="<?php echo $db_name; ?>" /></td>
            </tr>
            <tr>
              <td><label>Database User</label></td>
              <td><input type="text" name="db_username" value="<?php echo $db_username; ?>" /></td>
            </tr>
            <tr>
              <td><label>Database password</label></td>
              <td><input type="password" name="db_password" value="<?php echo $db_password; ?>" />
            </tr>
            <tr>
              <td colspan="2"><input type="submit" value="Export iPeer Data" name="export_submit" /></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </body>
</html>
<?php
}
?>