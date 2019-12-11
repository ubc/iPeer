# LTI RI test platform

Log in and follow instructions at <https://lti-ri.imsglobal.org>

## Step 1: Generate public / private key pair

## Step 2: Create Platform (LMS)

- Name: `ubc-ipeer-lti13-test-platform`
- Client: `ipeer-lti13-001` (1)
- Audience: `https://lti-ri.imsglobal.org`
- Tool Deep Link Service Endpoint: blank

- Platform Public Key: ...
- Platform Private Key: ...
- Tool public key: ...

> After Save, the platform URL is: <https://lti-ri.imsglobal.org/platforms/652>

## Step 3: Add Deployment to Platform

- Name: `Key 1`
- Deployment ID: `1` (3)

## Step 4: Add a Tool

- Name: `ubc-ipeer-lti13-test-tool`
- Client: `ipeer-lti13-001` (1)
    - OAuth2 client id, same value as Platform Client ID above: `ipeer-lti13-001`
- Deployment: `1` (3)
    - same value as Platform Deployment ID above: `1`
- Keyset url: `https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json`
- Oauth2 url: `https://lti-ri.imsglobal.org/platforms/652/access_tokens`
- Platform oidc auth url: `https://lti-ri.imsglobal.org/platforms/652/authorizations/new`

- Tool private key: ...

> After Save, the tool URL is: <https://lti-ri.imsglobal.org/lti/tools/652>

## Step 5: In Platform tab, view your Platform

### 5.1 Add course

<https://lti-ri.imsglobal.org/platforms/652/contexts>

- Label: `Test iPeer course label`
- Title: `Test iPeer course title`
- Type of context: `Test iPeer course type`

### 5.5 Add resource links

<https://lti-ri.imsglobal.org/platforms/652/resource_links/new>

- Context: `Test iPeer course title`
- Title: `Test iPeer resource link title` (4)
- Description: `Test iPeer resource link description`
- Tool link url: `http://localhost:8080/lti13/launch` (2)
    - URL for a local iPeer installation using the LtiController route
- Login initiation url: `http://localhost:8080/lti13/login`
- Role: blank
- User identifier only for launch, no PII such as first name, email etc.: OFF
- Custom claim content: blank

### 5.11 Add course line item

<https://lti-ri.imsglobal.org/platforms/652/contexts/4287/line_items/new>

- Resource link: `Test iPeer resource link title` _10261_ (4)
- Resourceid: `1`
- Score maximum: `100`
- Label: `Line item 1 label`
- Tag: `Line item 1 tag`
- Start date time: `2019-12-10`
- End date time: `2020-12-10`

### 5.17 Add launch URL

<https://lti-ri.imsglobal.org/platforms/652/edit>

- Tool Deep Link Service Endpoint: `http://localhost:8080/lti13/launch` (2)

### 5.20 Add user for launch

<https://lti-ri.imsglobal.org/platforms/652/resource_links/10261/rosters>

Prepare localhost iPeer:

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
```

- Select first user's "Launch Resource Link (OIDC)" button.
- Click "Post request" button.

The browser goes to <http://localhost:8080/home>

```
Error: You do not have permission to access the page.
```
