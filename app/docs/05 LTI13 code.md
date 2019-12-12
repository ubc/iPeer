# LTI13 code

## Key files

```bash
cd ~/Code/ctlt/iPeer
mkdir -p app/config/lti13
touch app/config/lti13/tool.private.key
touch app/config/lti13/tool.public.key
```

> The keys are taken from LTI-RI.

## .gitignore

```bash
cd ~/Code/ctlt/iPeer
echo 'app/config/**/*.key' | tee -a .gitignore
```

## Registration JSON

[registration.json](app/config/lti13/registration.json)

```json
{
  "https://lti-ri.imsglobal.org": {
    "client_id": "ipeer-lti13-001",
    "auth_login_url": "https://lti-ri.imsglobal.org/platforms/652/authorizations/new",
    "auth_token_url": "https://lti-ri.imsglobal.org/platforms/652/access_tokens",
    "key_set_url": "https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json",
    "private_key_file": "app/config/lti13/tool.private.key",
    "deployment": [
      "1"
    ]
  }
}
```

## LTI13Database class

<https://book.cakephp.org/1.3/en/The-Manual/Developing-with-CakePHP/Configuration.html#loading-vendor-files>

[LTI13Database.php](app/libs/LTI13Database.php)

## Model

[lti13.php](app/models/lti13.php)

## Controller

[lti13_controller.php](app/controllers/lti13_controller.php)

## Routes

[routes.php](app/config/routes.php)

```diff

```
