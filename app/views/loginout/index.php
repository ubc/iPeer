<?php
  /**
   * @file cwl_xmlrpc_example.php
   * @author Daniel McLaren
   *
   * an example of CWL authentication in PHP.  Uses PEAR's XML_RPC module
   * and requires SSL support (OpenSSL)
   */

  require_once('XML/RPC.php');


  /// Local application settings

  /**
   * the application's ID/name and password as given by the CWL team
   */
//  $applicationID = 'cis_ipeer_vsa';
  $applicationID = 'cis_ipeer_psa';

//  $applicationPassword = 'p33rl355';
  $applicationPassword = 'p33k4b00';



  /**
   * the URL to redirect to after login
   */
  $redirectURL = 'http://ipeer.apsc.ubc.ca/ipeer/index.php';

  /// CWL settings

  /**
   * the URL of the CWL login page
   */
  $CWLLoginURL = 'https://www.auth.cwl.ubc.ca/auth/login';

  // CWL XML-RPC interface URLs: https://www.auth.verf.cwl.ubc.ca/auth/rpc (for verification)
  //                             https://www.auth.cwl.ubc.ca/auth/rpc
  $CWLRPCURL = "https://www.auth.cwl.ubc.ca";
  $CWLRPCPath = "/auth/rpc";

  /**
   * the name of the function being called through XML-RPC. this is
   * prepended with 'session.' later
   */
  //$CWLFunctionName = 'getLoginName';
  $CWLFunctionName = 'getIdentities';
  
  /**
   * receive the ticket passed from the CWL LoginServlet
   */
  $ticket = null;
  if (isset($_GET['ticket']))
  {
echo 'get ticket';
    $ticket = $_GET['ticket'];
  }

?>
<!doctype html public "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" lang="en">
  <head>
    <title>CWL XML-RPC Example</title>
    <meta name="Author" content="Daniel McLaren (www.ponderbox.com)" />
  </head>

  <body>

    <h1>CWL XML-RPC Example</h1>

    <?php

      if ($ticket == null)
      {
echo 'empty';      	
        ?>
          <p>
            <a href="<?= $CWLLoginURL ?>?serviceName=<?= $applicationID ?>&amp;serviceURL=<?= $redirectURL ?>">Login</a>
          </p>
        <?php
      }
      else
      {
echo 'success';       	
        ?><p>Success!  Received session ticket "<?= $ticket ?>" from the CWL LoginServlet.</p><?php

        // now get some info about the session
          
        // the parameters passed to the RPC interface.  the ticket is the
        // first argument for all functions
        $params = array( new XML_RPC_Value($ticket, 'string') );

        // note that the function name is prepended with the string 'session.'
		    $msg = new XML_RPC_Message("session.$CWLFunctionName", $params);


        $cli = new XML_RPC_Client($CWLRPCPath, $CWLRPCURL);
        $cli->setCredentials($applicationID, $applicationPassword);

    		//print_r ($cli);
        //$cli->setDebug(1);

		    $resp = $cli->send($msg);

        if (!$resp)
        {
          echo 'Communication error: ' . $cli->errstr;
          exit;
        }

        // print the raw response data

        echo "<b>Raw Response:</b><br /><pre>";
        print_r($resp);
        echo "</pre>";

        if (!$resp->faultCode())
        {
          // an encoded response value
          $val = $resp->value();

          // the actual data we requested
          $data = XML_RPC_decode($val);

          echo "<b>Response Data:</b><br /><pre>";
          print_r($data);
print $val->scalarval() . "\n";           
          echo "</pre>";
        }
        else
        {
          // error
          echo '<b>Fault Code:</b> ' . $resp->faultCode() . "<br />\n";
          echo '<b>Fault Reason:</b> ' . $resp->faultString() . "<br />\n";
        }

      }

    ?>

  </body>
</html>
