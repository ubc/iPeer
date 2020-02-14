# Porting LTI 1.1 controller

## Checkout new branch

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
git checkout -b lti-1.3-port
```

## LTI 1.1 files

- [app/controllers/lti_controller.php](app/controllers/lti_controller.php)
- [app/controllers/components/lti_requester.php](app/controllers/components/lti_requester.php)
- [app/controllers/components/lti_verifier.php](app/controllers/components/lti_verifier.php)

`LtiController::index()` is:

- validating LTI response
- fetching roster
- fetching course info
- updating roster
- logging in as user from LTI response

## LTI 1.3 files

- [app/models/lti13.php](app/models/lti13.php)
- [app/controllers/lti13_controller.php](app/controllers/lti13_controller.php)
- [app/libs/lti13/lti13_bootstrap.php](app/libs/lti13/lti13_bootstrap.php)
- [app/libs/lti13/LTI13Database.php](app/libs/lti13/LTI13Database.php)
- [app/tests/cases/models/lti13.test.php](app/tests/cases/models/lti13.test.php)
