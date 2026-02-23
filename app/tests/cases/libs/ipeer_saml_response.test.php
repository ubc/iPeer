<?php

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecEnc;

require_once ROOT . DS . 'app' . DS . 'libs' . DS . 'IPeerSamlResponse.php';

/**
 * Tests for IPeerSamlResponse.
 *
 * Each test generates fresh IdP and SP key pairs so no real credentials are needed.
 * buildResponse() produces a fully signed and encrypted SAMLResponse; its $opts
 * parameter lets individual test cases mutate specific fields to exercise each
 * validation failure path.
 *
 * XML templates are loaded from saml_assertion.xml and saml_response.xml in the
 * same directory, with {{PLACEHOLDER}} tokens substituted at build time.
 *
 * @package    app.tests.cases.libs
 */
class IPeerSamlResponseTestCase extends CakeTestCase
{
    const SP_ENTITY_ID  = 'https://sp.example.com/metadata';
    const SP_ACS_URL    = 'https://sp.example.com/acs';
    const IDP_ENTITY_ID = 'https://idp.example.com';

    // Must exceed ALLOWED_CLOCK_DRIFT (180 s) so timestamp boundary tests reliably fire.
    const TIMESTAMP_SKEW = 600;

    // IdP key pair — used to sign assertions
    private $idpPrivKeyPem;
    private $idpCertPem;

    // SP key pair — used for decryption (and as the nominal SP signing key)
    private $spPrivKeyPem;
    private $spCertPem;

    private $settings;

    public function startTest($method)
    {
        $this->idpPrivKeyPem = $this->genPrivKey();
        $this->idpCertPem    = $this->genCert($this->idpPrivKeyPem, 'Test IdP');

        $this->spPrivKeyPem = $this->genPrivKey();
        $this->spCertPem    = $this->genCert($this->spPrivKeyPem, 'Test SP');

        $this->settings = [
            'strict' => true,
            'sp'     => [
                'entityId'                => self::SP_ENTITY_ID,
                'assertionConsumerService' => [
                    'url'     => self::SP_ACS_URL,
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                ],
                'x509cert'                => $this->certBase64($this->spCertPem),
                'privateKey'              => $this->keyBase64($this->spPrivKeyPem),
                'privateKeyForDecryption' => $this->keyBase64($this->spPrivKeyPem),
            ],
            'idp' => [
                'entityId'            => self::IDP_ENTITY_ID,
                'x509cert'            => $this->certBase64($this->idpCertPem),
                'singleSignOnService' => [
                    'url'     => 'https://idp.example.com/sso',
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                ],
            ],
        ];
    }

    public function testHappyPath()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse());
        $response->validate();

        $attrs = [];
        foreach ($response->getAttributes() as $name => $values) {
            $attrs[$name] = $values[0];
        }
        $this->assertEqual('testuser123', $attrs['urn:oid:1.3.6.1.4.1.60.6.1.6']);
    }

    public function testSignedWithUnknownKey()
    {
        $wrongKey  = $this->genPrivKey();
        $wrongCert = $this->genCert($wrongKey, 'Unknown IdP');
        $response  = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'idpPrivKey' => $wrongKey,
            'idpCert'    => $wrongCert,
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for unknown signing key');
        } catch (Exception $e) {
            $this->assertPattern('/signature does not match IdP certificate/', $e->getMessage());
        }
    }

    public function testTamperedAssertionContent()
    {
        // Assertion is signed then its content is mutated before encryption;
        // decryption succeeds but signature validation must catch the tampering.
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'tamperAfterSign' => true,
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for tampered assertion');
        } catch (Exception $e) {
            $this->assertPattern('/Reference validation failed/', $e->getMessage());
        }
    }

    public function testFailureStatusCode()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'statusCode' => 'urn:oasis:names:tc:SAML:2.0:status:Responder',
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for non-Success status');
        } catch (Exception $e) {
            $this->assertPattern('/The status code of the Response was not Success/', $e->getMessage());
        }
    }

    public function testExpiredAssertion()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'notOnOrAfter' => gmdate('Y-m-d\TH:i:s\Z', time() - self::TIMESTAMP_SKEW),
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for expired assertion');
        } catch (Exception $e) {
            $this->assertPattern('/timestamp: expired/', $e->getMessage());
        }
    }

    public function testAssertionNotYetValid()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'notBefore' => gmdate('Y-m-d\TH:i:s\Z', time() + self::TIMESTAMP_SKEW),
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for not-yet-valid assertion');
        } catch (Exception $e) {
            $this->assertPattern('/timestamp: not yet valid/', $e->getMessage());
        }
    }

    public function testIssuerMismatch()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'issuer' => 'https://evil.example.com',
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for issuer mismatch');
        } catch (Exception $e) {
            $this->assertPattern('/[Ii]ssuer/', $e->getMessage());
        }
    }

    public function testAudienceMismatch()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'audience' => 'https://other-sp.example.com',
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for audience mismatch');
        } catch (Exception $e) {
            $this->assertPattern('/[Aa]udience/', $e->getMessage());
        }
    }

    public function testDestinationMismatch()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'destinationUrl' => 'https://evil.example.com/acs',
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for destination mismatch');
        } catch (Exception $e) {
            $this->assertPattern('/[Dd]estination/', $e->getMessage());
        }
    }

    public function testRecipientMismatch()
    {
        $response = new IPeerSamlResponse($this->settings, $this->buildResponse([
            'recipientUrl' => 'https://evil.example.com/acs',
        ]));
        try {
            $response->validate();
            $this->fail('Expected exception not thrown for recipient mismatch');
        } catch (Exception $e) {
            $this->assertPattern('/[Rr]ecipient/', $e->getMessage());
        }
    }

    public function testMissingDecryptionKey()
    {
        $settings = $this->settings;
        unset($settings['sp']['privateKeyForDecryption']);
        putenv('IPEER_SECRET_KEY'); // ensure env var is not set

        try {
            new IPeerSamlResponse($settings, $this->buildResponse());
            $this->fail('Expected exception not thrown for missing decryption key');
        } catch (Exception $e) {
            $this->assertPattern('/decryption key/i', $e->getMessage());
        }
    }

    public function testEmptyResponse()
    {
        try {
            new IPeerSamlResponse($this->settings, '');
            $this->fail('Expected exception not thrown for empty response');
        } catch (Exception $e) {
            $this->assertPattern('/Empty SAMLResponse/i', $e->getMessage());
        }
    }

    public function testDecryptionKeyFromEnv()
    {
        $settings = $this->settings;
        unset($settings['sp']['privateKeyForDecryption']);
        putenv('IPEER_SECRET_KEY=' . $this->keyBase64($this->spPrivKeyPem));

        try {
            $response = new IPeerSamlResponse($settings, $this->buildResponse());
            $response->validate();

            $attrs = [];
            foreach ($response->getAttributes() as $name => $values) {
                $attrs[$name] = $values[0];
            }

            $this->assertTrue(isset($attrs['urn:oid:1.3.6.1.4.1.60.6.1.6']));
            $this->assertEqual('testuser123', $attrs['urn:oid:1.3.6.1.4.1.60.6.1.6']);
        } finally {
            putenv('IPEER_SECRET_KEY');
        }
    }

    private function buildResponse(array $opts = []): string
    {
        $now          = time();
        $issuer         = $opts['issuer']         ?? self::IDP_ENTITY_ID;
        $audience       = $opts['audience']       ?? self::SP_ENTITY_ID;
        $notBefore      = $opts['notBefore']      ?? gmdate('Y-m-d\TH:i:s\Z', $now - 300);
        $notOnOrAfter   = $opts['notOnOrAfter']   ?? gmdate('Y-m-d\TH:i:s\Z', $now + 300);
        $statusCode     = $opts['statusCode']     ?? 'urn:oasis:names:tc:SAML:2.0:status:Success';
        $idpPrivKey     = $opts['idpPrivKey']     ?? $this->idpPrivKeyPem;
        $idpCert        = $opts['idpCert']        ?? $this->idpCertPem;
        $tamper         = $opts['tamperAfterSign'] ?? false;
        $recipientUrl   = $opts['recipientUrl']   ?? self::SP_ACS_URL;
        $destinationUrl = $opts['destinationUrl'] ?? self::SP_ACS_URL;

        $issueInstant = gmdate('Y-m-d\TH:i:s\Z', $now);
        $responseId   = '_' . bin2hex(random_bytes(16));
        $assertionId  = '_' . bin2hex(random_bytes(16));
        $dir          = __DIR__;

        // 1. Load and populate the assertion template
        $assertionXml = strtr(file_get_contents("$dir/saml_assertion.xml"), [
            '{{ASSERTION_ID}}'    => $assertionId,
            '{{ISSUE_INSTANT}}'   => $issueInstant,
            '{{ISSUER}}'          => $issuer,
            '{{AUDIENCE}}'        => $audience,
            '{{NOT_BEFORE}}'      => $notBefore,
            '{{NOT_ON_OR_AFTER}}' => $notOnOrAfter,
            '{{ACS_URL}}'         => $recipientUrl,
        ]);

        $assertionDoc = new DOMDocument();
        $assertionDoc->loadXML($assertionXml);

        // 2. Sign the assertion with the IdP key
        $this->signNode($assertionDoc->documentElement, $idpPrivKey, $idpCert);

        // 3. Optionally tamper with the content after signing so the signature
        //    digest no longer matches — decryption succeeds but validateSign fails
        if ($tamper) {
            $vals = $assertionDoc->getElementsByTagNameNS('urn:oasis:names:tc:SAML:2.0:assertion', 'AttributeValue');
            if ($vals->length > 0) {
                $vals->item(0)->textContent = 'tampered_value';
            }
        }

        // 4. Load and populate the response wrapper template
        $responseXml = strtr(file_get_contents("$dir/saml_response.xml"), [
            '{{RESPONSE_ID}}'   => $responseId,
            '{{ISSUE_INSTANT}}' => $issueInstant,
            '{{ISSUER}}'        => $issuer,
            '{{STATUS_CODE}}'   => $statusCode,
            '{{ACS_URL}}'       => $destinationUrl,
        ]);

        // 5. Encrypt the assertion in its own standalone document.
        //    If we instead imported the assertion node into the response document first,
        //    DOMDocument::saveXML($node) would omit xmlns:saml2 (already in scope on the
        //    response root), producing a namespace-invalid encrypted payload.
        $assertionOnlyDoc = new DOMDocument();
        $assertionOnlyDoc->loadXML($assertionDoc->saveXML($assertionDoc->documentElement));
        $this->encryptNode($assertionOnlyDoc->documentElement, $this->spCertPem);
        $encDataXml = $assertionOnlyDoc->saveXML($assertionOnlyDoc->documentElement);

        // 6. Inject the EncryptedData into the response XML string and return.
        $responseXml = str_replace(
            '<saml2:EncryptedAssertion/>',
            '<saml2:EncryptedAssertion>' . $encDataXml . '</saml2:EncryptedAssertion>',
            $responseXml
        );

        return base64_encode($responseXml);
    }

    private function signNode(DOMElement $node, string $privKeyPem, string $certPem): void
    {
        $dsig = new XMLSecurityDSig();
        $dsig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        $dsig->addReference(
            $node,
            XMLSecurityDSig::SHA256,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature', XMLSecurityDSig::EXC_C14N],
            ['id_name' => 'ID', 'overwrite' => false]
        );

        $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
        $key->loadKey($privKeyPem, false, false);
        $dsig->sign($key);
        $dsig->add509Cert($certPem, true, false);
        $dsig->appendSignature($node);
    }

    private function encryptNode(DOMElement $node, string $certPem): void
    {
        $symKey = new XMLSecurityKey(XMLSecurityKey::AES128_CBC);
        $symKey->generateSessionKey();

        $transportKey = new XMLSecurityKey(XMLSecurityKey::RSA_OAEP_MGF1P, ['type' => 'public']);
        $transportKey->loadKey($certPem, false, true);

        $objenc = new XMLSecEnc();
        $objenc->setNode($node);
        $objenc->type = XMLSecEnc::Element;
        $objenc->encryptKey($transportKey, $symKey);
        $objenc->encryptNode($symKey);
    }

    private function genPrivKey(): string
    {
        $res = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export($res, $pem);
        return $pem;
    }

    private function genCert(string $privKeyPem, string $cn): string
    {
        $key  = openssl_pkey_get_private($privKeyPem);
        $csr  = openssl_csr_new(['CN' => $cn], $key, ['digest_alg' => 'sha256']);
        $cert = openssl_csr_sign($csr, null, $key, 365, ['digest_alg' => 'sha256']);
        openssl_x509_export($cert, $pem);
        return $pem;
    }

    private function certBase64(string $certPem): string
    {
        return preg_replace('/-----[^-]+-----|[\r\n\s]+/', '', $certPem);
    }

    private function keyBase64(string $privKeyPem): string
    {
        return preg_replace('/-----[^-]+-----|[\r\n\s]+/', '', $privKeyPem);
    }
}
