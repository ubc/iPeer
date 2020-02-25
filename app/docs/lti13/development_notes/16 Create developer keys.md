# Create developer keys

- <https://canvas.instructure.com/doc/api/file.oauth.html#accessing-lti-advantage-services>
- <https://community.canvaslms.com/docs/DOC-16729-42141110178>
- <https://community.canvaslms.com/docs/DOC-16730-42141110273>

## iPeer's public and private keys

Based on: [Configure LTI 1.3 RI Tool for Canvas](https://confluence.it.ubc.ca/pages/viewpage.action?spaceKey=LTHub&title=Configure+LTI+1.3+RI+Tool+for+Canvas)

Update `~/Code/ctlt/iPeer/app/config/lti13/registration.json`

```json
{
    "https://canvas.instructure.com": {
        "client_id": "10000000000001",
        "auth_login_url": "http://canvas.docker/api/lti/authorize",
        "auth_token_url": "http://canvas.docker/login/oauth2/token",
        "key_set_url": "http://canvas.docker/api/lti/security/jwks",
        "private_key_file": "app/config/lti13/tool.private.key",
        "deployment": [
            "1:4dde05e8ca1973bcca9bffc13e1548820eee93a3"
        ]
    }
}
```

- iPeer's private key `~/Code/ctlt/iPeer/app/config/lti13/tool.private.key` is the `"private_key_file"` path in `registration.json`.
- iPeer's public key `~/Code/ctlt/iPeer/app/config/lti13/tool.public.key` is converted from the `"key_set_url"` Canvas URL.

## Add Canvas Developer Key

Go to <http://canvas.docker/accounts/site_admin/developer_keys>

- Click on `+ Developer Key` button
- Select `+ LTI Key`
- On `Key Settings` modal window:
    - Configure > Method: `Manual Entry`
    - Key Name: `iPeer LTI 1.3`
    - Redirect URIs: `http://localhost:8080/lti13/launch`
    - Title: `iPeer LTI 1.3 test`
    - Description: `iPeer LTI 1.3 integration test`
    - Target Link URI: `http://localhost:8080/lti13/launch`
    - OpenID Connect Initiation Url: `http://localhost:8080/lti13/login`
    - JWK Method: `Public JWK`
        - textarea: (see below)

textarea:

- Go to <https://8gwifi.org/jwkconvertfunctions.jsp>
- Paste `~/Code/ctlt/iPeer/app/config/lti13/tool.public.key` content in testarea
- Convert PEM-to-JWK (RSA Only)
- Append `,"alg":"RS256","use":"sig"` before the end `}`

```json
{"kty":"RSA","e":"AQAB","kid":"8db214e5-64b1-456f-8357-6bde59b67144","n":"0p6MHsFSIKZsX0ABU2UEy08JYJOSBu3Pb5XErkqWdO0AZ9MZi3hW94im6qr3aC7ZCgKHbyY9RwSCKolUY_HUdI_LF4389hg6jobFlQtagcIkgOF4F0d75ygPb8_ihWi8uQAB0S0H2GDzldj2FL6SRx7Nob_1A1LY63NXEXwIkXIdQfFR3fjkptGSy4PhJOb6o498lV4AOVC8GMW0kJdjH0SsXm1clga3QSaFyyAylnE-0cyxdb8osH6v2_iLUSpZ0rnmX6AOJZwePB4bp1ne-c6JokBiumw9bZdyXGFG0tuHkwZGyCVN-mcgfHnz3IQy956hVIkprpkCM_Pu5EADuw","alg":"RS256","use":"sig"}
```

- Still on `Key Settings` modal window:
    - Expand `LTI Advantage Services`
    - Check ON `Can retrieve user data associated with the context the tool is installed in.`
    - Expand `Additional Settings`:
    - Privacy Level: `Public`
    - Click on `Placements`:
    - Select `Course Navigation`
        - It adds the `Course Navigation` tag to the list
    - Click the `Save` button

- On `Developer Keys` page:
    - State: `On`
    - Copy `Details` number: this is the **Client ID** to put in `registration.json` > `https://canvas.instructure.com` > `client_id`
    - Click the `Show Key` button
    - Copy the hash that appears in popup modal
    - Paste this hash in `~/Code/ctlt/iPeer/app/config/lti13/canvas/canvas.private.key`

## Dump new data

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /tmp/canvas_1.sql"
```
