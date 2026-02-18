<?php

require_once 'vendor/autoload.php'; // Load OneLogin SAML2

/////// CWL LOGIN 2 //////////


class HomeUBCSamlController extends AppController
{
    /**
     * This controller does not use a model
     *
     * @public $uses
     */
    public $uses =  array( 'Group', 'GroupEvent',
        'User', 'UserCourse', 'Event', 'EvaluationSubmission',
        'Course', 'Role', 'UserEnrol', 'Rubric', 'Penalty');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    function connect_to_db() {
        // Database connection settings
        $DB_HOST = getenv("IPEER_DB_HOST") ?: "db-host";
        $DB_PORT = getenv("DB_IPEER_DATABASE_PORT") ?: "3306";
        $DB_NAME = getenv("DB_IPEER_DATABASE") ?: "ipeer";
        $DB_USER = getenv("IPEER_DB_USER") ?: "ipeer";
        $DB_PASSWORD = getenv("IPEER_DB_PASSWORD") ?: "password";

        try {
            // Establish connection
            $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $connection = new PDO($dsn, $DB_USER, $DB_PASSWORD, $options);
            //$this->log("======== Connection to the database was successful! ===================", 'debug');
            return $connection;
        } catch (PDOException $e) {
            $this->log("=============== Error connecting to the database: " . $e->getMessage() );
            return null;
        }
    }


    function get_user_id_by_username($username) {

        $connection = $this->connect_to_db();

        if (!$connection) {
            return null;
        }

        try {
            $query = "SELECT id, username FROM users WHERE username = :username";
            $stmt = $connection->prepare($query);
            $stmt->execute(['username' => $username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['id'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    function process_user($username) {
        if (!$username) {
            //echo "No username provided.";
            $this->log('NO USERNAME provided:');
            return null;
        }




        $user_id = $this->get_user_id_by_username($username);

        if ($user_id) {
            $this->log( "User ID for username '" . $username . "': " . $user_id . "<br>" , 'debug');
            return $username;
        } else {
            $this->log( "No user found with username '" . $username . "'<br>", 'debug');
            
            //FOR ONBOARDING STUDENTS MUST BE DONE by iPeer thru Import Groups from Canvas

        }
        return null;
    }


    function extractCipherValues($samlResponseBase64) {
        // Decode the Base64 SAML Response
        $samlXML = base64_decode($samlResponseBase64);

        // Define the regex pattern to capture all <xenc:CipherValue> values
        $pattern = '/<xenc:CipherValue[^>]*>(.*?)<\/xenc:CipherValue>/s';

        // Match all occurrences
        preg_match_all($pattern, $samlXML, $matches);

        if (empty($matches[1])) {
            $this->log("------extractCipherValues------ No CipherValue elements found.");
            //die("No CipherValue elements found.");
        }

        // Return only the last CipherValue
        return end($matches[1]);
    }


    function isBase64Encoded($data)
    {
        if (base64_encode(base64_decode($data, true)) === $data) {
            return true;
        }
        return false;
    }

    function convertToPem($privateKey) {
        $pem = "-----BEGIN PRIVATE KEY-----\n";
        $pem .= chunk_split($privateKey, 64, "\n");
        $pem .= "-----END PRIVATE KEY-----\n";
        return $pem;
    }


    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        ///////////////////////////////////////////////////////////////
        // Initialize SAML authentication

        $datetime = date("YmdHis");


        $samlResponse = $_POST['SAMLResponse'];  // Raw SAML Response from IDP


        if ( $this->isBase64Encoded($samlResponse)) {
            $decodedResponse = base64_decode($samlResponse);

            libxml_use_internal_errors(true);
            $xml = new DOMDocument();
            if (!$xml->loadXML($decodedResponse)) {
                $this->log("Error: Decoded SAMLResponse is not valid XML.", 'debug');
            }

            $xmlContent = $xml->saveXML();


            $rawKey = $GLOBALS['IPEER_SECRET_KEY'] ?? getenv('IPEER_SECRET_KEY') ?? null;
            if (!$rawKey) {
                $this->log('IPEER_SECRET_KEY missing');

            }

            $spPem = "-----BEGIN PRIVATE KEY-----\n" .
                chunk_split($rawKey, 64, "\n") .
                "-----END PRIVATE KEY-----\n";

            $privRes = openssl_pkey_get_private($spPem);
            if (!$privRes) {
                $this->log('openssl_pkey_get_private failed: ' . openssl_error_string(), 'info');
            } else {
                $pubKeyDetails = openssl_pkey_get_details($privRes);
                $pubKeyPem = $pubKeyDetails['key'] ?? '(unknown)';
                $pubKeyMd5 = md5(base64_decode(trim(str_replace(
                    ['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n"],
                    '',
                    $pubKeyPem
                ))));
                $this->log('IPEER_SECRET_KEY public key MD5: ' . $pubKeyMd5, 'info');
            }


            $xml = $decodedResponse;
            if ($xml === false) {
                $this->log('Base‑64 decode of SAMLResponse failed');

            }

            $doc = new DOMDocument();
            $doc->loadXML($xml, LIBXML_NONET | LIBXML_NOBLANKS);

            $xp = new DOMXPath($doc);
            $xp->registerNamespace('saml2', 'urn:oasis:names:tc:SAML:2.0:assertion');
            $xp->registerNamespace('xenc',  'http://www.w3.org/2001/04/xmlenc#');


            $encKeyNode  = $xp->query('//xenc:EncryptedKey/xenc:CipherData/xenc:CipherValue')->item(0);
            $encDataNode = $xp->query('//xenc:EncryptedData/xenc:CipherData/xenc:CipherValue')->item(0);

            if (!$encKeyNode || !$encDataNode) {
                $this->log('EncryptedKey or EncryptedData element not found');

            }


            $encKeyBin = base64_decode($encKeyNode->nodeValue, true);
            if ($encKeyBin === false) {
                $this->log('EncryptedKey: strict base64 decode failed (whitespace in CipherValue?), retrying non-strict', 'info');
                $encKeyBin = base64_decode($encKeyNode->nodeValue);
            }
            $this->log(sprintf('EncryptedKey: decoded_length=%d first16hex=%s',
                strlen($encKeyBin),
                bin2hex(substr($encKeyBin, 0, 16))
            ), 'info');
            $oaepOk = openssl_private_decrypt($encKeyBin, $aesKey, $privRes, OPENSSL_PKCS1_OAEP_PADDING);
            if (!$oaepOk) {
                $this->log('RSA-OAEP decrypt failed: ' . openssl_error_string(), 'info');
            } else {
                $this->log('RSA-OAEP decrypt succeeded, aesKey_len=' . strlen($aesKey), 'info');
            }

            $this->log('aesKey before normalization: len=' . strlen($aesKey) . ' hex=' . bin2hex($aesKey), 'info');
            if (strlen($aesKey) !== 16) {                                // not 16 bytes?
                $try = base64_decode($aesKey, true);                     // maybe still b64
                if ($try !== false && strlen($try) === 16) {
                    $aesKey = $try;
                    $this->log('aesKey normalized via base64 decode', 'info');
                } else {                                                 // last fallback
                    $aesKey = substr($aesKey, 0, 16);
                    $this->log('aesKey truncated to 16 bytes', 'info');
                }
            }
            $this->log('aesKey after normalization: len=' . strlen($aesKey), 'info');


            $cipherBin = base64_decode($encDataNode->nodeValue, true);
            if ($cipherBin === false || strlen($cipherBin) < 16) {
                $this->log('EncryptedData: decode error');

            }

            $iv         = substr($cipherBin, 0, 16);
            $cipherBody = substr($cipherBin, 16);

            /* First attempt: normal PKCS#7 padding (OpenSSL default) */
            $this->log(sprintf('AES-CBC attempt: cipherBody_len=%d aesKey_len=%d', strlen($cipherBody), strlen($aesKey)), 'info');
            $plain = openssl_decrypt($cipherBody, 'aes-128-cbc', $aesKey,
                OPENSSL_RAW_DATA, $iv);

            /* Retry with ZERO_PADDING + manual PKCS#7 strip if first failed */
            if ($plain === false) {
                $this->log('First SAML decrypt failed, retrying with zero padding', 'info');
                $plain = openssl_decrypt($cipherBody, 'aes-128-cbc', $aesKey,
                    OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
                if ($plain !== false) {
                    $pad = ord($plain[strlen($plain) - 1]);
                    if ($pad > 0 && $pad <= 16) {
                        $plain = substr($plain, 0, -$pad);
                    }
                }
            }

            if ($plain === false) {
                $this->log('AES‑128‑CBC decrypt still failing: ' . openssl_error_string());

            }
            $decryptedAssertion = $plain;

            if (!$decryptedAssertion) {
                $this->log("Error: Failed to decrypt SAML Assertion..........................", 'debug');

                $this->redirect('/login?defaultlogin=true');
                exit;

            }else{
                $this->log("Decryption OK.", 'debug');

                $decryptedXml = new DOMDocument();
                $decryptedXml->loadXML($decryptedAssertion);
                // Extract Attributes from Decrypted Assertion
                $attributes = [];
                foreach ($decryptedXml->getElementsByTagName('Attribute') as $attribute) {
                    $name = $attribute->getAttribute('Name');
                    $value = $attribute->getElementsByTagName('AttributeValue')->item(0)->nodeValue;
                    $attributes[$name] = $value;

                    $this->log("ATTIBBB:::" . $name . ":" . $value , 'debug');

                }


                //TODO: ENV VAR THESE ATTRIBUTES
                $strGivenName = $attributes['urn:mace:dir:attribute-def:givenNameLthub'] ?? "";
                $strLastName = $attributes['urn:mace:dir:attribute-def:snLthub'] ?? "";
                $username = $attributes['urn:oid:1.3.6.1.4.1.60.6.1.6'] ?? "";
                $strEmail = $attributes['urn:oid:0.9.2342.19200300.100.1.3'] ?? "";

                $strStudentNo = $attributes['urn:oid:1.3.6.1.4.1.60.6.1.6.1'] ?? "";

                $defaultRoleID = 1;

                // Call the process_user function with the gathered values
                $this->log(
                    "username::::::::".($username) . ":" .
                    ($defaultRoleID ?? 5) . ":" .
                    ($strGivenName ?? "GivenName") . ":" .
                    ($strLastName ?? "LastName") . ":" .
                    ($strStudentNo ?? (string) $datetime) . ":" .
                    ($strEmail ?? "no-reply@example.com") . ":"
                );


                if (!empty($username)) {
                    $processUser = $this->process_user(
                        $username,
                    );
                }


                if (!empty($processUser)) {
                    $username = $processUser;

                    $userId = $this->User->field('id', array('username' => $username ));
                    $userRoleId = $this->User->field('role_id', array('username' => $username ));

                    if (!$this->Auth->login($userId)) {
                        $this->log('Invalid username '.$userId.' from session transfer.', 'debug');
                        //return false;
                    }else{
                        $this->log('Valid username '.$userId.' from session transfer.', 'debug');
                    }
                }else{
                    $this->log("PROCESS USER:EXISTING-USER::" . $name . ":" . $value , 'debug');
                    
                    $this->_afterLogout();

                    $this->redirect('/public/saml/logout.php');
                   
                    exit;
                }

            }


        } else {
            $this->log("Error: SAMLResponse is not properly Base64-encoded.", 'debug');
            $this->log($samlResponse);

        }

        $this->_afterLogin();

        parent::beforeFilter();
    }


    /**
     * index
     *
     *
     * @access public
     * @return void
     */
    function index()
    {

        $this->log("HOME:UBC SAML Controller:");

    }
}
