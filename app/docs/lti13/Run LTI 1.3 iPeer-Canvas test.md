# Run LTI 1.3 iPeer-Canvas test

0. Delete Docker items if necessary.
1. Install Canvas locally.
2. Import fixtures in Canvas.
3. Install iPeer locally.
4. Run iPeer LTI 1.3 tests.

## Delete Docker items if necessary

> If experiencing problems with the local installation of Canvas,
it's best to delete all docker containers, images, volumes and networks,
and start over.

```bash
docker rm -vf $(docker ps -a -q)
docker rmi -f $(docker images -a -q)
docker system prune -a -f --volumes
dinghy destroy -f
brew uninstall dinghy
```

- And restart Docker Desktop for macOS!
- And restart Terminal!

---

## Install Canvas locally

1. Install stable branch of Canvas.
2. Edit Dockerfile.
3. Run setup script.
4. Start Canvas.

<https://github.com/instructure/canvas-lms/wiki/Quick-Start>

```bash
cd ~/Code/ctlt
git clone -b stable https://github.com/instructure/canvas-lms.git canvas
cd ~/Code/ctlt/canvas
git reset --hard dc6478a81bbcfa85f9886d7d6d7ff5dcfbaf5686
```

### Patch Canvas files

#### Minimize Webpack complications

- Bypass `webpack:production` errors

```bash
patch -p0 ~/Code/ctlt/canvas/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/patches/canvas/Dockerfile.diff
```

#### Fix postgres container

- Fix missing `pgxs.mk` error.
- Use `psql` version 9.5, not 9.6.

```bash
patch -p0 ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/patches/canvas/postgres-Dockerfile.diff
```

### Run setup script

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

### Mount volume to access data

Edit `docker-compose.override.yml`

```diff
  postgres:
    volumes:
      - pg_data:/var/lib/postgresql/data
+     - ./.postgres_app_tmp:/usr/src/app/tmp
```

```bash
cd ~/Code/ctlt/canvas
docker-compose up -d --build postgres
```

---

## Import fixtures in Canvas

1. Copy dump file from iPeer to Canvas.
2. Disconnect all users from `canvas` dB with `docker-compose down`.
3. Drop `canvas` dB.
4. Restore `canvas` dB with custom format dump file.

```bash
cd ~/Code/ctlt/canvas
cp ~/Code/ctlt/iPeer/app/config/lti13/canvas.sql.dump .postgres_app_tmp/
docker-compose down
docker-compose up -d postgres
docker exec -it canvas_postgres_1 bash
```

`root@27c698737093:/#`

```bash
dropdb -U postgres canvas
pg_restore -U postgres -C -d postgres /usr/src/app/tmp/canvas.sql.dump
exit
```

```bash
docker-compose up -d
```

Refresh <http://canvas.docker>

---

## Install iPeer locally

1. Install Lti 1.3 version of iPeer.
2. Run installation wizard.

- <http://ipeer.ctlt.ubc.ca>
- <https://github.com/ubc/iPeer>

### Install Lti 1.3 version of iPeer

```bash
mkdir -p ~/Code/ctlt && cd $_
git clone -b lti-1.3-port https://repo.code.ubc.ca:smarsh05/ipeer-lti13.git
cd ~/Code/ctlt/iPeer
curl -sS https://getcomposer.org/installer | php
php composer.phar install
docker-compose up -d
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

---

## Run iPeer LTI 1.3 tests

### Before test

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db bash
```

`root@0ae4f272871c:/#`

```bash
mysql ipeer -u ipeer -p
```

`MariaDB [ipeer]>`

```
SELECT u.id, u.username, u.first_name, u.last_name, u.student_no, u.email, u.record_status, u.lti_id, e.course_id, c.course, c.title, c.canvas_id FROM users AS u INNER JOIN user_enrols AS e ON e.user_id = u.id LEFT JOIN courses AS c on c.id = e.course_id;
+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
| id | username     | first_name | last_name | student_no | email | record_status | lti_id | course_id | course   | title                            | canvas_id |
+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
|  8 | redshirt0004 | Chris      | Student   | 16585158   |       | A             | NULL   |         3 | CPSC 101 | Connecting with Computer Science | NULL      |
| 33 | redshirt0029 | Joe        | Student   | 51516498   |       | A             | NULL   |         3 | CPSC 101 | Connecting with Computer Science | NULL      |
+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
2 rows in set (0.01 sec)
```

### Run manual test

Browse to <http://localhost:8080/lti13>


---------------------------------------------------------------------------------------------------

## Make a SQL file of diff

We just want to test the LTI 1.3 connection, so:

- Input directly in dB
    - Hardcode keys in web test
    - Just add users to course
- Code a test that logs in to iPeer and generates the LTI 1.3 launch sequence

Canvas:

    - run rake to reset/start the .data for canvas
    - pg_dump
    - http://canvas.docker to populate the accounts in the course(s)
    - pg_dump
    - diff the two dumps
    - add a developer key
    - make a SQL for a migration file specifically for this test

iPeer:

    - add logging to the test
    - in test, log the list of ipeer roster before launch
    - test launch and log it
    - log the list of ipeer roster after launch

