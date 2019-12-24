# Porting LTI 1.1 controller

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

