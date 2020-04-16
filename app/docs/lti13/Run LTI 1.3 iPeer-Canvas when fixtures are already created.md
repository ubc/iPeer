# Run LTI 1.3 iPeer-Canvas when fixtures are already created

## Set up Canvas

> Assuming Canvas already built in a dinghy VM.

First terminal tab:

```bash
cd ~/Code/ctlt/canvas
dinghy start
eval "$(dinghy env)"
docker-compose up -d
```

OR

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

Wait 3 minutes.

Browse to <http://canvas.docker>

## Set up iPeer

Second terminal tab:

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

## Run iPeer

Go to <http://localhost:8080/login>

- username: `root`
- password: `password`

Open a new tab to look at page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)
