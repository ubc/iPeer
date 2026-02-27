<?php
/** @noinspection PhpComposerExtensionStubsInspection */

require_once ROOT . DS . 'vendor' . DS . 'autoload.php';

use OneLogin\Saml2\Constants;
use OneLogin\Saml2\Response;
use OneLogin\Saml2\Settings;
use OneLogin\Saml2\Utils;
use RobRichards\XMLSecLibs\XMLSecEnc;

/**
 * We extend `OneLogin\Saml2\Response` to override some problematic methods that don't work
 * with our IdP, plus add new ones where overrides aren't possible.
 *
 * The main changes include:
 * - how the decryption key is loaded (the library assumes the same private key is used for
 *   both signing requests and decrypting responses, whereas we use two separate keys)
 * - a workaround for a key algorithm mismatch (https://github.com/SAML-Toolkits/php-saml/issues/627):
 *   the parent's decryptAssertion() passes the AES symmetric key to Utils::decryptElement()
 *   (via its private decryptNameId() method) where an RSA key is expected, triggering a mismatch.
 *   We override decryptAssertion() to perform the same decryption without calling decryptNameId().
 * - a workaround for a conflict the library has between the response-level signature and
 *   assertion-level signatures (due to how the library stores the decrypted xml subtree, the
 *   response-level signature always fails)
 */
class IPeerSamlResponse extends Response
{
    /**
     * @param array $config Full SAML settings array (e.g. from SAML_SETTINGS env var).
     *                         sp.privateKeyForDecryption is extracted and used to patch
     *                         sp.privateKey before passing to Settings; falls back to
     *                         IPEER_SECRET_KEY env var.
     * @param string|null $response Base64-encoded SAMLResponse
     * @throws Exception if no decryption key is available
     */
    public function __construct(array $config, string|null $response)
    {
        if (empty($response)) {
            throw new Exception('Empty SAMLResponse received');
        }

        $decryptKey = getenv('IPEER_SECRET_KEY') ?: ($config['sp']['privateKeyForDecryption'] ?? null);
        if (!$decryptKey) {
            throw new Exception('No decryption key available (sp.privateKeyForDecryption or IPEER_SECRET_KEY)');
        }

        // Patch sp.privateKey so our decryptAssertion() override uses the decryption key.
        $config['sp']['privateKey'] = $decryptKey;
        // privateKeyForDecryption is our own custom setting (not intended for the library)
        unset($config['sp']['privateKeyForDecryption']);

        parent::__construct(new Settings($config), $response);
    }

    public function validate()
    {
        $this->checkStatus();
        $this->validateDestination();
        $this->validateAssertionSignature();
        $this->validateTimestamps();
        $this->validateIssuers();
        $this->validateAudiences();
        $this->validateRecipient();
    }

    protected function validateAssertionSignature()
    {
        $idpCert = $this->_settings->getIdPData()['x509cert'] ?? null;
        if (!$idpCert) {
            throw new Exception('IdP certificate not configured (idp.x509cert missing from SAML_SETTINGS)');
        }

        $doc   = clone $this->decryptedDocument;
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
        $xpath->registerNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
        foreach ($xpath->query('/samlp:Response/ds:Signature') as $node) {
            // The Response-level signature was computed over the original encrypted XML,
            // but after the library decrypts it, it's stale since the document structure
            // was mutated. This causes "Reference validation failed" exceptions to be thrown.
            $node->parentNode->removeChild($node);
        }

        if (!Utils::validateSign($doc, Utils::formatCert($idpCert))) {
            throw new Exception('Assertion signature validation failed — signature does not match IdP certificate');
        }
    }

    protected function validateIssuers()
    {
        $expectedIssuer = $this->_settings->getIdPData()['entityId'] ?? null;
        if (!$expectedIssuer) {
            CakeLog::write('warning', 'SAML: idp.entityId not configured — skipping issuer validation');
            return;
        }

        $issuers = $this->getIssuers();
        if (empty($issuers)) {
            throw new Exception('No Issuer element found in response');
        }

        $normalizedExpected = rtrim($expectedIssuer, '/');
        foreach ($issuers as $issuer) {
            if (rtrim($issuer, '/') !== $normalizedExpected) {
                throw new Exception("Issuer mismatch (expected=$expectedIssuer, got=$issuer)");
            }
        }
    }

    /**
     * Validate that the SP entityId appears in the assertion's AudienceRestriction.
     *
     * @throws Exception
     */
    public function validateAudiences()
    {
        $spEntityId = $this->_settings->getSPData()['entityId'] ?? null;
        if (empty($spEntityId)) {
            CakeLog::write('warning', 'SAML: sp.entityId not configured — skipping audience validation');
            return;
        }

        $audiences = $this->getAudiences();
        if (empty($audiences)) {
            throw new Exception('No AudienceRestriction found in assertion');
        }

        if (!in_array($spEntityId, $audiences)) {
            throw new Exception("Audience mismatch — SP entityId '$spEntityId' not in assertion audiences: " . implode(', ', $audiences));
        }
    }

    protected function validateDestination()
    {
        $destination = $this->document->documentElement->getAttribute('Destination');
        if (empty($destination)) {
            CakeLog::write('warning', 'SAML: Response has no Destination attribute');
            return;
        }

        $acsUrl = $this->_settings->getSPData()['assertionConsumerService']['url'] ?? null;
        if (!$acsUrl) {
            CakeLog::write('warning', 'SAML: sp.assertionConsumerService.url not configured — skipping Destination validation');
            return;
        }

        if (rtrim($destination, '/') !== rtrim($acsUrl, '/')) {
            throw new Exception("Destination mismatch (expected=$acsUrl, got=$destination)");
        }
    }

    protected function validateRecipient()
    {
        $acsUrl = $this->_settings->getSPData()['assertionConsumerService']['url'] ?? null;
        if (!$acsUrl) {
            CakeLog::write('warning', 'SAML: sp.assertionConsumerService.url not configured — skipping Recipient validation');
            return;
        }

        $xpath = new DOMXPath($this->decryptedDocument);
        $xpath->registerNamespace('saml2', 'urn:oasis:names:tc:SAML:2.0:assertion');
        $nodes = $xpath->query('//saml2:SubjectConfirmationData/@Recipient');

        if ($nodes->length === 0) {
            throw new Exception('No SubjectConfirmationData Recipient found in assertion');
        }

        $normalizedAcs = rtrim($acsUrl, '/');
        foreach ($nodes as $node) {
            if (rtrim($node->value, '/') !== $normalizedAcs) {
                throw new Exception("SubjectConfirmationData Recipient mismatch (expected=$acsUrl, got={$node->value})");
            }
        }
    }

    /**
     * Overrides the parent's decryptAssertion() to avoid a bug in its private
     * decryptNameId() helper, which passes the AES symmetric key to
     * Utils::decryptElement() where an RSA key is expected, causing an algorithm
     * mismatch error (https://github.com/SAML-Toolkits/php-saml/issues/627).
     *
     * The logic here is otherwise identical to the parent, minus the decryptNameId call.
     */
    protected function decryptAssertion(DomNode $dom)
    {
        $objenc  = new XMLSecEnc();
        $encData = $objenc->locateEncryptedData($dom);
        if (!$encData) {
            throw new Exception('Cannot locate encrypted assertion');
        }

        $objenc->setNode($encData);
        $objenc->type = $encData->getAttribute('Type');

        $objKey = $objenc->locateKey();
        if (!$objKey) {
            throw new Exception('Unknown algorithm');
        }

        $key = null;
        if ($objKeyInfo = $objenc->locateKeyInfo($objKey)) {
            if ($objKeyInfo->isEncrypted) {
                $objencKey = $objKeyInfo->encryptedCtx;
                $objKeyInfo->loadKey(Utils::formatPrivateKey($this->_settings->getSPkey()), false, false);
                $key = $objencKey->decryptKey($objKeyInfo);
            } else {
                $objKeyInfo->loadKey(Utils::formatPrivateKey($this->_settings->getSPkey()), false, false);
            }
        }

        if (empty($objKey->key)) {
            $objKey->loadKey($key);
        }

        $decryptedXML = $objenc->decryptNode($objKey, false);
        $decrypted    = new DOMDocument();
        if (!Utils::loadXML($decrypted, $decryptedXML)) {
            throw new Exception('Decrypted assertion could not be loaded as XML');
        }

        if ($encData->parentNode instanceof DOMDocument) {
            return $decrypted;
        }

        $decrypted          = $decrypted->documentElement;
        $encryptedAssertion = $encData->parentNode;
        $container          = $encryptedAssertion->parentNode;

        if (!$decrypted->hasAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:saml')
            && !$decrypted->hasAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:saml2')
            && !$decrypted->hasAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns')
            && !$container->hasAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:saml')
            && !$container->hasAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:saml2')
        ) {
            if (strpos($encryptedAssertion->tagName, 'saml2:') !== false) {
                $ns = 'xmlns:saml2';
            } elseif (strpos($encryptedAssertion->tagName, 'saml:') !== false) {
                $ns = 'xmlns:saml';
            } else {
                $ns = 'xmlns';
            }
            $decrypted->setAttributeNS('http://www.w3.org/2000/xmlns/', $ns, Constants::NS_SAML);
        }

        Utils::treeCopyReplace($encryptedAssertion, $decrypted);

        $domOut = new DOMDocument();
        return Utils::loadXML($domOut, $container->ownerDocument->saveXML());
    }
}
