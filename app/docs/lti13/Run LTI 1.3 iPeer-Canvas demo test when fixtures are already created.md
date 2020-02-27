# Run LTI 1.3 iPeer-Canvas demo test when fixtures are already created

## Set up Canvas

> Assuming Canvas already built in a dinghy VM.

First terminal tab:

```bash
cd ~/Code/ctlt/canvas
dinghy restart
eval "$(dinghy env)"
docker-compose up -d postgres
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/canvas.postgresql.dump canvas_postgres_1:/tmp/
docker exec -it canvas_postgres_1 dropdb -U postgres canvas
docker exec -it canvas_postgres_1 pg_restore -U postgres -C -d postgres /tmp/canvas.postgresql.dump
docker-compose up -d
```

Browse to <http://canvas.docker>

## Set up iPeer

Second terminal tab:

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

## Run demo test

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
