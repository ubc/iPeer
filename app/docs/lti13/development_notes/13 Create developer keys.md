# Create developer keys

- <https://canvas.instructure.com/doc/api/file.oauth.html#accessing-lti-advantage-services>
- <https://community.canvaslms.com/docs/DOC-16729-42141110178>
- <https://community.canvaslms.com/docs/DOC-16730-42141110273>

## Add public and private keys

Based on: [Configure LTI 1.3 RI Tool for Canvas](https://confluence.it.ubc.ca/pages/viewpage.action?spaceKey=LTHub&title=Configure+LTI+1.3+RI+Tool+for+Canvas)

Create `ctlt/iPeer/app/config/lti13/registration.json`

```json
{
    "https://canvas.instructure.com": {
        "client_id": "10000000000001",
        "auth_login_url": "http://canvas.docker/api/lti/authorize",
        "auth_token_url": "http://canvas.docker/login/oauth2/token",
        "key_set_url": "http://canvas.docker/api/lti/security/jwks",
        "private_key_file": "app/config/lti13/canvas.private.key",
        "deployment": [
            "2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2"
        ]
    }
}
```

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
- Paste public key in testarea
- Convert PEM-to-JWK (RSA Only)
- Add `,"alg":"RS256","use":"sig"` before the end `}`

```
{"kty":"RSA","e":"AQAB","kid":"3a401ed7-6cb3-4a43-a86c-df1d8b56ac04","n":"1iwWmGv5hqUpnJjcvB0_fFsWiy1P01oXZ2jngyJZ1uEX_s2al-IwCo9ckztoMUaFtN-Jb811AVWjNOfCie_LAa5JcY01P9mGnJLiBdlD-ett4yYzvLPdmYqVHGpuj_mRYD4GiviTkrvK0SHDXASmiyWhAiIHAnHKQlUv-uw7GAvznAd-wTS_GvGu9AtjWfhM6jZ3kzwMc8WfGxI-YCxvg9JwySbLEDxV8UiNQ2OrPZnVXn2tnJ_0zrGK5TvH4ftHFs0brYEHhZFGWl2iw65FsT25PG2Vl8fBUeFdbosUJCFJAu_MqN8mguebHzIDYmUXTttoIY0p_mDFsB-_WwthVQ","alg":"RS256","use":"sig"}
```

- Still on `Key Settings` modal window:
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
    - Paste this hash in `app/config/lti13/canvas.private.key`

Go to <http://canvas.docker/accounts/site_admin/settings/configurations>

- On `Apps` tab:
    - Click on `+ App` button
    - On `Add App` modal window:
        - Configuration Type: `By Client ID`
        - Client ID: `10000000000001`
        - Click the `Submit` button
        - Tool "iPeer LTI 1.3 test" found for client ID 10000000000001. Would you like to install it?
            - Click the `Install` button
    - When `iPeer LTI 1.3 test` appears in the list:
        - Click the cog icon on the right
        - Select `Deployment Id`
        - Copy the hash that appears in the popup modal
        - Close the popup modal
        - Paste in `registration.json` > `https://canvas.instructure.com` > `deployment` array

## Dump new data

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /usr/src/app/tmp/canvas_1.sql"
```
