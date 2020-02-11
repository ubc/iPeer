# Ultimate iPeer-Canvas test

1. Install iPeer locally
2. Install Canvas locally
3. Import fixtures in Canvas
4. Run iPeer LTI 1.3 tests

## Install iPeer locally

- <http://ipeer.ctlt.ubc.ca>
- <https://github.com/ubc/iPeer>

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

### Installation wizard

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

## Install Canvas locally

<https://github.com/instructure/canvas-lms/wiki/Quick-Start>

## Wipe-out if necessary

```bash
docker rm -vf $(docker ps -a -q)
docker rmi -f $(docker images -a -q)
docker system prune -a -f --volumes
dinghy destroy
```

> And restart Docker Desktop for macOS!
> And restart Terminal!

```bash
cd ~/Code/ctlt
git clone -b stable https://github.com/instructure/canvas-lms.git canvas
cd ~/Code/ctlt/canvas
git reset --hard dc6478a81bbcfa85f9886d7d6d7ff5dcfbaf5686
```

Edit `Dockerfile`

```diff
- RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ RUN COMPILE_ASSETS_BUILD_JS=0 bundle exec rake canvas:compile_assets_dev
```

Save `Dockerfile`

```bash
./script/docker_dev_setup.sh
```
```
OK to run 'brew install dinghy'? [y/n] y
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
```
First, run:

  eval "$(dinghy env)"

This will set up environment variables for docker to work with the dinghy VM.

Running Canvas:

  docker-compose up -d
  open http://canvas.docker
```

> SUCCESS!

```bash
eval "$(dinghy env)"
docker-compose up -d
open http://canvas.docker
```

Browse to <http://canvas.docker>

## Import fixtures in Canvas

1. Disconnect all users from `canvas` dB with `docker-compose down`.
2. Drop `canvas` dB.
3. Restore `canvas` dB with custom format dump file.

```bash
cd ~/Code/ctlt/canvas
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

## Run iPeer LTI 1.3 tests

