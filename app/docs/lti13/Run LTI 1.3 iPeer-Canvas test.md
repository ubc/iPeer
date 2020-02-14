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

### Edit Dockerfiles

#### Minimize Webpack complications

Edit the last line of `Dockerfile`.

```diff
- RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ # RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ RUN COMPILE_ASSETS_BUILD_JS=0 bundle exec rake canvas:compile_assets_dev
```

Save `Dockerfile`.

#### Fix postgres container

- Fix missing pgxs.mk error.
- Use `psql` version 9.5, not 9.6.

Edit `docker-compose/postgres/Dockerfile`

In the `apt-get install` lines:

```diff
    postgresql-server-dev-9.5 \
+   postgresql-server-dev-9.6 \
    pgxnclient \
```

In the `apt-get remove` lines:

```diff
    postgresql-server-dev-9.5 \
+   postgresql-server-dev-9.6 \
+   postgresql-client-9.6 \
    pgxnclient \
```

Save `docker-compose/postgres/Dockerfile`

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
cp ~/Code/ctlt/iPeer/.data/canvas.sql.dump .postgres_app_tmp/
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

