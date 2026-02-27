<?php
require_once ROOT . DS . 'app' . DS . 'libs' . DS . 'IPeerSamlResponse.php';

/**
 * Using this component is an alternative to using: `OneLogin\Saml2\Auth->processResponse()`
 *
 * We use this as there are some bugs in OneLogin\Saml2 that affect our specific IdP configuration.
 * See the comments on `IPeerSamlResponse` for more details.
 */
class SamlComponent extends CakeObject
{
    const USERNAME_OID    = 'urn:oid:1.3.6.1.4.1.60.6.1.6';
    const STUDENT_NO_OID  = 'urn:oid:1.3.6.1.4.1.60.6.1.6.1';
    const EMAIL_OID       = 'urn:oid:0.9.2342.19200300.100.1.3';
    const GIVEN_NAME_OID  = 'urn:mace:dir:attribute-def:givenNameLthub';
    const LAST_NAME_OID   = 'urn:mace:dir:attribute-def:snLthub';

    /**
     * Process a base64-encoded SAMLResponse from the IdP.
     *
     * @param string|null $samlResponseBase64
     * @return array|null null on failure, otherwise an array(username => 'string', attributes => array(string => string))
     */
    public function processResponse($samlResponseBase64 = null)
    {
        $samlResponseBase64 = $samlResponseBase64 ?: ($_POST['SAMLResponse'] ?? null);
        $settings = json_decode(getenv('SAML_SETTINGS'), true);

        if (empty($settings)) {
            CakeLog::write('error', 'SAML: SAML_SETTINGS are missing');
            return null;
        }

        try {
            $response = new IPeerSamlResponse($settings, $samlResponseBase64);
            $response->validate();

            $attributes = [];
            foreach ($response->getAttributes() as $name => $values) {
                $attributes[$name] = $values[0];
            }
        } catch (Exception $e) {
            // OneLogin\Saml2 unfortunately throws bare `Exception`s, so we had to make the catch broad.
            // (Then we made our wrapper code also do the same to match)
            CakeLog::write('warning', 'SAML: ' . $e->getMessage());
            return null;
        }

        $username = $attributes[self::USERNAME_OID] ?? null;

        if (!$username) {
            CakeLog::write('error', 'SAML: No username found in assertion (missing ' . self::USERNAME_OID . ')');
            return null;
        }

        CakeLog::write('info', "SAML: Received username '$username' from SAML response");
        CakeLog::write('debug', 'SAML: Assertion attributes'
            . ' | username='   . $username
            . ' | given_name=' . ($attributes[self::GIVEN_NAME_OID] ?? '')
            . ' | last_name='  . ($attributes[self::LAST_NAME_OID] ?? '')
            . ' | student_no=' . ($attributes[self::STUDENT_NO_OID] ?? '')
            . ' | email='      . ($attributes[self::EMAIL_OID] ?? '')
        );

        return array('username' => $username, 'attributes' => $attributes);
    }
}
