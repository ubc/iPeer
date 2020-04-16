## Run LTI 1.3 iPeer-Canvas demo test

**This will work only with the git branch that contains the demo, not the final git branch pushed on UBC's github.**

### Before

Go to <http://localhost:8080/login>

- username: `root`
- password: `password`

Open a new tab to look at page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)

### Run

Go to <http://localhost:8080/lti13>

### After

Refresh page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)

Check iPeer LTI 1.3 test logs:

- `~/Code/ctlt/iPeer/app/tmp/logs/lti13/launch.log`
- `~/Code/ctlt/iPeer/app/tmp/logs/lti13/roster.log`
- `~/Code/ctlt/iPeer/app/tmp/logs/lti13/user.log`
