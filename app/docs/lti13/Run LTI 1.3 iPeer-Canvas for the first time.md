# Run LTI 1.3 iPeer-Canvas for the first time

1. Install Canvas locally
2. Build Canvas
3. Import fixtures in Canvas
4. Install iPeer locally
5. Import fixtures in Canvas
6. Run iPeer
7. Run Canvas

---

## 1. Install Canvas locally

Open a first terminal tab.

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

Browse to <http://canvas.docker> to see the login page.

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

Wait 3 minutes.

Refresh <http://canvas.docker>

---

## 4. Install iPeer locally

- <http://ipeer.ctlt.ubc.ca>
- <https://github.com/ubc/iPeer>

Open a second terminal tab.

### Install Lti 1.3 version of iPeer

```bash
mkdir -p ~/Code/ctlt && cd $_
git clone -b lti-1.3-port https://repo.code.ubc.ca:smarsh05/ipeer-lti13.git
```

### Build iPeer

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d --build
git submodule init
git submodule update
```

**Wait 5 minutes for the database to build**

### Run installation wizard

Browse to: <http://localhost:8080>

I see "Installation Wizard"

- Step 1: System Requirements Check
- Step 2: License Agreement
    - Check ON `I Accept the GPL License`
- Step 3: iPeer Database Configuration
    - Check ON `Installation with Sample Data`
- Step 4: System Parameters Configuration
    - Username: `root`
    - Password: `password`
    - Confirm Password: `password`

I see "iPeer Installation Complete!"

Browse to <http://localhost:8080> to see the login page.

### Patch LTI 1.3 PHP library files

```bash
cd ~/Code/ctlt/iPeer/vendor/imsglobal/lti-1p3-tool/src/lti
```

#### Fix EOL

```bash
dos2unix Cache.php
dos2unix Cookie.php
dos2unix LTI_Deep_Link.php
dos2unix LTI_Message_Launch.php
dos2unix LTI_OIDC_Login.php
dos2unix LTI_Service_Connector.php
```

#### Patch Cookie class

- Add `setcookie()` code compatible with PHP < 7.3

```bash
cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/Cookie.php.diff .
patch -p0 Cookie.php < Cookie.php.diff
rm Cookie.php.diff
```

### Import iPeer schema and data

```bash
cd ~/Code/ctlt/iPeer
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

### Update users.lti_id of root user

#### Get lti_id from Canvas

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 psql -U postgres canvas -tc "SELECT lti_id FROM users WHERE name LIKE 'root@canvas';"
```
```
 f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd
```

#### Update lti_id in iPeer

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "UPDATE users SET lti_id = 'f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd' WHERE username LIKE 'root';"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -sNe "SELECT lti_id FROM users WHERE username LIKE 'root';"
```
```
f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd
```

### Dump original data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db sh -c "mysqldump ipeer -u ipeer -p > /tmp/ipeer.reset.sql"
docker exec -it ipeer_db ls -lAFh /tmp
docker cp ipeer_db:/tmp/ipeer.reset.sql ~/Code/ctlt/iPeer/app/config/lti13/ipeer/
```

---

## 5. Import fixtures in iPeer

### Reset data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db ls -lAFh /tmp
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

---

## 6. Run iPeer

Browse to <http://localhost:8080/login>

- username: `root`
- password: `password`

Open a new browser tab to look at page of students enrolled in courses:

- [MECH 328 enrolment](http://localhost:8080/users/goToClassList/1)
- [APSC 201 enrolment](http://localhost:8080/users/goToClassList/2)

---

## 7. Run Canvas

Browse to <http://canvas.docker>

- username: `root@canvas`
- password: `password`

Browse to <http://canvas.docker/courses/1/external_tools/1> for OIDC login and launch for the MECH 328 course.
