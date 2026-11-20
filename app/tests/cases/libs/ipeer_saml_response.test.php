<?php

require_once ROOT . DS . 'app' . DS . 'libs' . DS . 'IPeerSamlResponse.php';
require_once __DIR__ . DS . 'saml_test_kit.php';

/**
 * Tests for IPeerSamlResponse.
 *
 * Each test gets fresh IdP and SP key pairs from SamlTestKit, so no real
 * credentials are needed. SamlTestKit::buildResponse() makes a signed and
 * encrypted SAMLResponse; its options change single fields to make each
 * validation fail.
 *
 * @package    app.tests.cases.libs
 */
class IPeerSamlResponseTestCase extends CakeTestCase
{
    private $samlTestKit;
    private $settings;

    public function startTest($method)
    {
        $this->samlTestKit = new SamlTestKit();
        $this->settings    = $this->samlTestKit->settings;
    }

    public function testHappyPath()
    {
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse());
        $response->validate();

        $attrs = [];
        foreach ($response->getAttributes() as $name => $values) {
            $attrs[$name] = $values[0];
        }
        $this->assertEqual('testuser123', $attrs['urn:oid:1.3.6.1.4.1.60.6.1.6']);
    }

    public function testSignedWithUnknownKey()
    {
        $wrongKey  = SamlTestKit::genPrivKey();
        $wrongCert = SamlTestKit::genCert($wrongKey, 'Unknown IdP');
        $response  = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
            'notOnOrAfter' => gmdate('Y-m-d\TH:i:s\Z', time() - SamlTestKit::TIMESTAMP_SKEW),
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
            'notBefore' => gmdate('Y-m-d\TH:i:s\Z', time() + SamlTestKit::TIMESTAMP_SKEW),
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
        $response = new IPeerSamlResponse($this->settings, $this->samlTestKit->buildResponse([
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
            new IPeerSamlResponse($settings, $this->samlTestKit->buildResponse());
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
        putenv('IPEER_SECRET_KEY=' . SamlTestKit::keyBase64($this->samlTestKit->spPrivKeyPem));

        try {
            $response = new IPeerSamlResponse($settings, $this->samlTestKit->buildResponse());
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
}
