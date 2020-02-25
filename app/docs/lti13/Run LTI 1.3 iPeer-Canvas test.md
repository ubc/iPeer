# Run LTI 1.3 iPeer-Canvas test

## Steps

1. Install Canvas locally.
2. Build Canvas.
3. Import fixtures in Canvas.
4. Install iPeer locally.
5. Import fixtures in Canvas.
6. Run iPeer LTI 1.3 tests.

---

## 1. Install Canvas locally

### Install stable branch of Canvas

<https://github.com/instructure/canvas-lms/wiki/Quick-Start>

```bash
mkdir -p ~/Code/ctlt && cd $_
git clone -b stable https://github.com/instructure/canvas-lms.git canvas
cd ~/Code/ctlt/canvas
git reset --hard dc6478a81bbcfa85f9886d7d6d7ff5dcfbaf5686
```

### Patch Canvas files

#### Minimize Webpack complications

- Bypass `webpack:production` errors

```bash
patch -p0 ~/Code/ctlt/canvas/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile.diff
```

#### Fix postgres container

- Fix missing `pgxs.mk` error.
- Use `psql` version 9.5, not 9.6.

```bash
patch -p0 ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile.diff
```

---

## 2. Build Canvas

### Destroy Dinghy

If you have dinghy installed and running:

```bash
dinghy destroy -f
```

### Run setup script

Expect 30 minutes for this build process.

```bash
cd ~/Code/ctlt/canvas
./script/docker_dev_setup.sh
```
```
OK to run 'brew install dinghy'? [y/n] y
OK to create a dinghy VM? [y/n] y
How much memory should I allocate to the VM (in MB)? [8192]
How many CPUs should I allocate to the VM? [4]
How big should the VM's disk be (in GB)? [150]
```
```
Starting NFS daemon, this will require sudo
Password:
```

> Enter computer admin password.

```
OK to run 'cp docker-compose/config/* config/'? [y/n] y
...
OK to run 'chmod a+rw Gemfile.lock'? [y/n] y
...
OK to run 'rm Gemfile.lock'? [y/n] y
```
```
What email address will the site administrator account use? > root@canvas
...
What password will the site administrator use? > password
...
What do you want users to see as the account name? This should probably be the name of your organization. > (leave blank)
...
3. Opt out completely
> 3
```

> SUCCESS!

### Start Canvas

```bash
eval "$(dinghy env)"
docker-compose up -d
```

Browse to <http://canvas.docker>

### Dump original data

In case you need to reset data.

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /tmp/canvas_0.sql"
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres -Fc canvas > /tmp/canvas.postgresql.reset.dump"
docker cp canvas_postgres_1:/tmp/canvas.postgresql.reset.dump ~/Code/ctlt/iPeer/app/config/lti13/canvas/
docker exec -it canvas_postgres_1 ls -lAFh /tmp
```

---

## 3. Import fixtures in Canvas

1. Copy dump file from iPeer to Canvas.
2. Disconnect all users from `canvas` dB with `docker-compose down`.
3. Drop & Restore `canvas` dB with custom format dump file.

```bash
cd ~/Code/ctlt/canvas
docker-compose down
docker-compose up -d postgres
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/canvas.postgresql.dump canvas_postgres_1:/tmp/
docker exec -it canvas_postgres_1 dropdb -U postgres canvas
docker exec -it canvas_postgres_1 pg_restore -U postgres -C -d postgres /tmp/canvas.postgresql.dump
docker-compose up -d
```

Refresh <http://canvas.docker>

---

## 4. Install iPeer locally

- <http://ipeer.ctlt.ubc.ca>
- <https://github.com/ubc/iPeer>

### Install Lti 1.3 version of iPeer

```bash
mkdir -p ~/Code/ctlt && cd $_
git clone -b lti-1.3-port https://repo.code.ubc.ca:smarsh05/ipeer-lti13.git
```

### Build iPeer

```bash
cd ~/Code/ctlt/iPeer
curl -sS https://getcomposer.org/installer | php
php composer.phar install
docker-compose up -d
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "ALTER TABLE users MODIFY lti_id VARCHAR(64) NULL DEFAULT NULL;"
git submodule init
git submodule update
```

### Run installation wizard

Browse to: <http://localhost:8080>

I see Installation Wizard.

- Step 1: Example app
- Step 2
- Step 3:
    - root
    - password

iPeer Installation Complete!

Browse to: <http://localhost:8080/login>

- root
- password

OK. I'm logged in.

### Dump original data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db sh -c "mysqldump ipeer -u ipeer -p > /tmp/ipeer.reset.sql"
docker exec -it ipeer_db ls -lAFh /tmp
docker cp ipeer_db:/tmp/ipeer.reset.sql ~/Code/ctlt/iPeer/app/config/lti13/canvas/
```

---

## 5. Import fixtures in iPeer

### Reset data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db ls -lAFh /tmp
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

### Update lti_id of root user

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 psql -U postgres canvas -tc "SELECT lti_id FROM users WHERE name LIKE 'root@canvas';"
```
```
 f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd
```

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "UPDATE users SET lti_id = 'f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd' WHERE username LIKE 'root';"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -sNe "SELECT lti_id FROM users WHERE username LIKE 'root';"
```
```
f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd
```

---

## 6. Run iPeer LTI 1.3 tests

### Reset data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db ls -lAFh /tmp
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

Open a new tab to look at page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)

### Run manual test

Go to <http://localhost:8080/login>

- username: `root`
- password: `password`

Go to <http://localhost:8080/lti13>

### After test

Refresh page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)

Check iPeer LTI 1.3 test logs:

- `~/Code/ctlt/iPeer/app/tmp/logs/lti13/launch.log`
- `~/Code/ctlt/iPeer/app/tmp/logs/lti13/roster.log`
- `~/Code/ctlt/iPeer/app/tmp/logs/lti13/user.log`
