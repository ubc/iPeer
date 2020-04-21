# Run LTI 1.3 iPeer-Canvas when fixtures are already created

- 1. Set up Canvas
- 2. Set up iPeer
- 3. Run iPeer
- 4. Run Canvas

Open 2 terminal tabs.

## 1. Set up Canvas

> Assuming Canvas already built in a dinghy VM.

Open a first terminal tab.

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

Browse to <http://canvas.docker> to see the login page.

---

## 2. Set up iPeer

Open a second terminal tab.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

---

## 3. Run iPeer

Browse to <http://localhost:8080/login>

- username: `root`
- password: `password`

Open a new browser tab to look at page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)

---

## 4. Run Canvas

Browse to <http://canvas.docker>

- username: `root@canvas`
- password: `password`

Browse to <http://canvas.docker/courses/1/external_tools/1> for OIDC login and launch for the MECH 328 course.

