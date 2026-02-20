<?php
/**
 * Using this component is an alternative to using:
 * `OneLogin\Saml2\Auth->processResponse()`
 *
 * We use this as there are some bugs in OneLogin\Saml2
 * that affect our specific IdP configuration, so we
 * can't use it in the Assertion Consumer Service.
 * (e.g. https://github.com/SAML-Toolkits/php-saml/issues/627)
 * However, the library works well and correctly for the
 * other SAML endpoints.
 *
 * This component replaced a previously fully hand-rolled
 * SAML response management. Instead of calling low-level
 * OpenSSL functions directly, now we use utilities from
 * `OneLogin\Saml2` and `RobRichards\XMLSecLibs`
 */

require_once 'vendor/autoload.php';

use RobRichards\XMLSecLibs\XMLSecEnc;
use OneLogin\Saml2\Utils;

class SamlComponent extends CakeObject
{
    const USERNAME_OID = 'urn:oid:1.3.6.1.4.1.60.6.1.6';

    /**
     * Process a SAML response from the IdP
     *
     * @param string $samlResponseBase64 Base64-encoded SAMLResponse from POST
     * @return array|null Array with 'username' and 'attributes', or null on failure
     */
    public function processResponse($samlResponseBase64)
    {
        if (empty($samlResponseBase64)) {
            CakeLog::write('error', 'SAML: Empty SAMLResponse received');
            return null;
        }

        // Decode and parse response
        $samlXml = base64_decode($samlResponseBase64);
        if (!$samlXml) {
            CakeLog::write('error', 'SAML: Failed to base64 decode SAMLResponse');
            return null;
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        if (!$doc->loadXML($samlXml, LIBXML_NONET | LIBXML_NOBLANKS)) {
            $errors = libxml_get_errors();
            CakeLog::write('error', 'SAML: Invalid XML: ' . print_r($errors, true));
            libxml_clear_errors();
            return null;
        }

        // 1. Validate signature
        if (!$this->validateSignature($doc)) {
            CakeLog::write('error', 'SAML: Signature validation failed');
            return null;
        }

        // 2. Validate InResponseTo (CSRF protection)
        if (!$this->validateInResponseTo($doc)) {
            CakeLog::write('error', 'SAML: InResponseTo validation failed - possible CSRF');
            return null;
        }

        // 3. Validate Issuer
        if (!$this->validateIssuer($doc)) {
            CakeLog::write('error', 'SAML: Issuer validation failed');
            return null;
        }

        // 4. Decrypt the encrypted assertion
        $decryptedAssertion = $this->decryptAssertion($doc);
        if (!$decryptedAssertion) {
            CakeLog::write('error', 'SAML: Failed to decrypt assertion');
            return null;
        }

        // 5. Parse decrypted assertion
        $assertionDoc = new DOMDocument();
        if (!$assertionDoc->loadXML($decryptedAssertion)) {
            CakeLog::write('error', 'SAML: Failed to parse decrypted assertion');
            return null;
        }

        // 6. Validate timestamps
        if (!$this->validateTimestamps($assertionDoc)) {
            CakeLog::write('error', 'SAML: Timestamp validation failed - assertion expired or not yet valid');
            return null;
        }

        // 7. Extract attributes
        $attributes = $this->extractAttributes($assertionDoc);
        $username = $attributes[self::USERNAME_OID] ?? null;

        if (!$username) {
            CakeLog::write('error', 'SAML: No username found in assertion (missing ' . self::USERNAME_OID . ')');
            return null;
        }

        CakeLog::write('info', "SAML: Response processed successfully for user: $username");

        return [
            'username' => $username,
            'attributes' => $attributes
        ];
    }

    /**
     * Validate SAML Response or Assertion signature
     *
     * @param DOMDocument $doc
     * @return bool
     */
    private function validateSignature($doc)
    {
        $settings = $this->getSettings();
        $idpCert = $settings['idp']['x509cert'] ?? null;

        if (!$idpCert) {
            CakeLog::write('error', 'SAML: IdP certificate not configured');
            return false;
        }

        try {
            // Validate signature on Response or Assertion
            return Utils::validateSign($doc, $idpCert, '/samlp:Response', '/saml:Assertion');
        } catch (Exception $e) {
            CakeLog::write('error', 'SAML: Signature validation exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate InResponseTo matches the stored request ID (CSRF protection)
     *
     * Requires CakePHP Session component to be available via controller.
     * If no controller context, skips validation.
     *
     * @param DOMDocument $doc
     * @return bool
     */
    private function validateInResponseTo($doc)
    {
        $response = $doc->documentElement;
        $inResponseTo = $response->getAttribute('InResponseTo');

        // If no InResponseTo in response, allow (IdP-initiated SSO)
        if (empty($inResponseTo)) {
            CakeLog::write('info', 'SAML: Response missing InResponseTo - allowing IdP-initiated SSO');
            return true;
        }

        // Try to get session from controller if available
        if (isset($this->controller) && isset($this->controller->Session)) {
            $expectedId = $this->controller->Session->read('SAML_RequestId');
            if (empty($expectedId)) {
                CakeLog::write('warning', 'SAML: No SAML_RequestId in session to validate InResponseTo');
                return true;
            }

            if ($inResponseTo !== $expectedId) {
                CakeLog::write('error', "SAML: InResponseTo mismatch: expected=$expectedId, got=$inResponseTo");
                return false;
            }

            // Clear the request ID (single use)
            $this->controller->Session->delete('SAML_RequestId');
        }

        return true;
    }

    /**
     * Validate the Issuer matches expected IdP
     *
     * @param DOMDocument $doc
     * @return bool
     */
    private function validateIssuer($doc)
    {
        $settings = $this->getSettings();
        $expectedIssuer = $settings['idp']['entityId'] ?? null;

        if (!$expectedIssuer) {
            CakeLog::write('warning', 'SAML: IdP entityId not configured - skipping issuer validation');
            return true;
        }

        $issuerNodes = $doc->getElementsByTagName('Issuer');
        if ($issuerNodes->length === 0) {
            CakeLog::write('error', 'SAML: No Issuer element found in response');
            return false;
        }

        $issuer = $issuerNodes->item(0)->nodeValue;
        if ($issuer !== $expectedIssuer) {
            CakeLog::write('error', "SAML: Issuer mismatch: expected=$expectedIssuer, got=$issuer");
            return false;
        }

        return true;
    }

    /**
     * Decrypt an encrypted SAML assertion
     *
     * Uses manual decryption to avoid onelogin/php-saml v4.3.1 EncryptedID bug.
     *
     * @param DOMDocument $doc
     * @return string|null Decrypted assertion XML
     */
    private function decryptAssertion($doc)
    {
        $settings = $this->getSettings();
        $privateKey = $settings['sp']['privateKeyForDecryption']
            ?? getenv('IPEER_SECRET_KEY')
            ?? $settings['sp']['privateKey']
            ?? null;

        if (!$privateKey) {
            CakeLog::write('error', 'SAML: SP private key not configured');
            return null;
        }

        // Ensure PEM format
        $privateKeyPem = $this->ensurePemFormat($privateKey);

        try {
            // Use XMLSecEnc to locate encrypted data
            $objenc = new XMLSecEnc();
            $encData = $objenc->locateEncryptedData($doc);

            if (!$encData) {
                CakeLog::write('error', 'SAML: No EncryptedAssertion found in response');
                return null;
            }

            $objenc->setNode($encData);
            $objenc->type = $encData->getAttribute("Type");

            // Locate the symmetric key (AES)
            $objKey = $objenc->locateKey();
            if (!$objKey) {
                CakeLog::write('error', 'SAML: Could not locate symmetric key in EncryptedData');
                return null;
            }

            // Locate key info (how AES key is encrypted)
            $objKeyInfo = $objenc->locateKeyInfo($objKey);
            if (!$objKeyInfo) {
                CakeLog::write('error', 'SAML: Could not locate KeyInfo');
                return null;
            }

            // Decrypt the encrypted AES key using our RSA private key
            if ($objKeyInfo->isEncrypted) {
                $objencKey = $objKeyInfo->encryptedCtx;
                $objKeyInfo->loadKey($privateKeyPem, false, false);
                $symmetricKey = $objencKey->decryptKey($objKeyInfo);
                $objKey->loadKey($symmetricKey);
            }

            // Decrypt the assertion data
            $decrypted = $objenc->decryptNode($objKey, false);

            CakeLog::write('info', 'SAML: Successfully decrypted assertion');
            return $decrypted;

        } catch (Exception $e) {
            CakeLog::write('error', 'SAML: Decryption failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate NotBefore and NotOnOrAfter timestamps
     *
     * @param DOMDocument $assertionDoc
     * @return bool
     */
    private function validateTimestamps($assertionDoc)
    {
        $conditions = $assertionDoc->getElementsByTagName('Conditions')->item(0);
        if (!$conditions) {
            CakeLog::write('info', 'SAML: No Conditions element found - skipping timestamp validation');
            return true;
        }

        $now = time();
        $clockSkew = 60; // Allow 60 seconds clock skew

        $notBefore = $conditions->getAttribute('NotBefore');
        if ($notBefore) {
            $notBeforeTime = strtotime($notBefore);
            if ($notBeforeTime > $now + $clockSkew) {
                CakeLog::write('error', "SAML: Assertion not yet valid: NotBefore=$notBefore");
                return false;
            }
        }

        $notOnOrAfter = $conditions->getAttribute('NotOnOrAfter');
        if ($notOnOrAfter) {
            $notOnOrAfterTime = strtotime($notOnOrAfter);
            if ($notOnOrAfterTime < $now - $clockSkew) {
                CakeLog::write('error', "SAML: Assertion expired: NotOnOrAfter=$notOnOrAfter");
                return false;
            }
        }

        return true;
    }

    /**
     * Extract attributes from decrypted assertion
     *
     * @param DOMDocument $assertionDoc
     * @return array Attribute name => value
     */
    private function extractAttributes($assertionDoc)
    {
        $attributes = [];

        foreach ($assertionDoc->getElementsByTagName('Attribute') as $attribute) {
            $name = $attribute->getAttribute('Name');
            $valueNode = $attribute->getElementsByTagName('AttributeValue')->item(0);
            $value = $valueNode ? $valueNode->nodeValue : null;

            if ($name && $value !== null) {
                $attributes[$name] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Get SAML settings from environment
     *
     * @return array
     */
    private function getSettings()
    {
        $samlSettings = getenv('SAML_SETTINGS');
        if (empty($samlSettings)) {
            CakeLog::write('critical', 'SAML: SAML_SETTINGS env var not set');
            return [];
        }

        $settings = json_decode($samlSettings, true);
        if (!$settings) {
            CakeLog::write('critical', 'SAML: Failed to parse SAML_SETTINGS JSON');
            return [];
        }

        return $settings;
    }

    /**
     * Ensure a private key has proper PEM headers
     *
     * @param string $key
     * @return string
     */
    private function ensurePemFormat($key)
    {
        if (empty($key)) {
            return $key;
        }

        // Already has PEM headers
        if (strpos($key, '-----BEGIN') !== false) {
            return $key;
        }

        // Raw base64 - wrap with PEM headers
        $base64 = trim($key);
        $wrapped = chunk_split($base64, 64, "\n");
        return "-----BEGIN PRIVATE KEY-----\n" . $wrapped . "-----END PRIVATE KEY-----\n";
    }
}
