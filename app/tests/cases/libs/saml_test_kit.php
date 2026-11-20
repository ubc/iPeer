<?php

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecEnc;

/**
 * Test IdP and SP key pairs, SAML settings that use them, and signed and
 * encrypted SAMLResponses, for the tests of IPeerSamlResponse and of the
 * SAML controller. No real credentials are needed.
 *
 * buildResponse() loads saml_assertion.xml and saml_response.xml from this
 * directory and fills in their {{PLACEHOLDER}} tokens. Its $opts change single
 * fields, so tests can make each validation fail.
 *
 * @package app.tests.cases.libs
 */
class SamlTestKit
{
    const SP_ENTITY_ID  = 'https://sp.example.com/metadata';
    const SP_ACS_URL    = 'https://sp.example.com/acs';
    const IDP_ENTITY_ID = 'https://idp.example.com';

    // Must exceed ALLOWED_CLOCK_DRIFT (180 s) so timestamp boundary tests reliably fire.
    const TIMESTAMP_SKEW = 600;

    // IdP key pair: signs the assertions
    public $idpPrivKeyPem;
    public $idpCertPem;

    // SP key pair: decrypts the assertions (and is the nominal SP signing key)
    public $spPrivKeyPem;
    public $spCertPem;

    public $settings;

    public function __construct()
    {
        $this->idpPrivKeyPem = self::genPrivKey();
        $this->idpCertPem    = self::genCert($this->idpPrivKeyPem, 'Test IdP');

        $this->spPrivKeyPem = self::genPrivKey();
        $this->spCertPem    = self::genCert($this->spPrivKeyPem, 'Test SP');

        $this->settings = [
            'strict' => true,
            'sp'     => [
                'entityId'                => self::SP_ENTITY_ID,
                'assertionConsumerService' => [
                    'url'     => self::SP_ACS_URL,
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                ],
                'x509cert'                => self::certBase64($this->spCertPem),
                'privateKey'              => self::keyBase64($this->spPrivKeyPem),
                'privateKeyForDecryption' => self::keyBase64($this->spPrivKeyPem),
            ],
            'idp' => [
                'entityId'            => self::IDP_ENTITY_ID,
                'x509cert'            => self::certBase64($this->idpCertPem),
                'singleSignOnService' => [
                    'url'     => 'https://idp.example.com/sso',
                    'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
                ],
            ],
        ];
    }

    /**
     * A base64-encoded SAMLResponse with a signed and encrypted assertion.
     *
     * @param array $opts fields to change: username, issuer, audience, notBefore,
     *                    notOnOrAfter, statusCode, idpPrivKey, idpCert,
     *                    tamperAfterSign, recipientUrl, destinationUrl
     */
    public function buildResponse(array $opts = []): string
    {
        $now            = time();
        $username       = $opts['username']       ?? 'testuser123';
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
            '{{USERNAME}}'        => htmlspecialchars($username, ENT_XML1),
        ]);

        $assertionDoc = new DOMDocument();
        $assertionDoc->loadXML($assertionXml);

        // 2. Sign the assertion with the IdP key
        self::signNode($assertionDoc->documentElement, $idpPrivKey, $idpCert);

        // 3. Optionally tamper with the content after signing so the signature
        //    digest no longer matches — decryption succeeds but validateSign fails.
        //    The NameID changes, not the username, so a response that skipped the
        //    signature check would still name a real user.
        if ($tamper) {
            $names = $assertionDoc->getElementsByTagNameNS('urn:oasis:names:tc:SAML:2.0:assertion', 'NameID');
            $names->item(0)->textContent = 'tampered_value';
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
        self::encryptNode($assertionOnlyDoc->documentElement, $this->spCertPem);
        $encDataXml = $assertionOnlyDoc->saveXML($assertionOnlyDoc->documentElement);

        // 6. Inject the EncryptedData into the response XML string and return.
        $responseXml = str_replace(
            '<saml2:EncryptedAssertion/>',
            '<saml2:EncryptedAssertion>' . $encDataXml . '</saml2:EncryptedAssertion>',
            $responseXml
        );

        return base64_encode($responseXml);
    }

    public static function genPrivKey(): string
    {
        $res = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        openssl_pkey_export($res, $pem);
        return $pem;
    }

    public static function genCert(string $privKeyPem, string $cn): string
    {
        $key  = openssl_pkey_get_private($privKeyPem);
        $csr  = openssl_csr_new(['CN' => $cn], $key, ['digest_alg' => 'sha256']);
        $cert = openssl_csr_sign($csr, null, $key, 365, ['digest_alg' => 'sha256']);
        openssl_x509_export($cert, $pem);
        return $pem;
    }

    public static function certBase64(string $certPem): string
    {
        return preg_replace('/-----[^-]+-----|[\r\n\s]+/', '', $certPem);
    }

    public static function keyBase64(string $privKeyPem): string
    {
        return preg_replace('/-----[^-]+-----|[\r\n\s]+/', '', $privKeyPem);
    }

    private static function signNode(DOMElement $node, string $privKeyPem, string $certPem): void
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

    // The same algorithms as the UBC IdP: RSA-OAEP-MGF1P key transport, AES128-CBC data.
    private static function encryptNode(DOMElement $node, string $certPem): void
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
}
