# Install Canvas container

The `Dockerfile` for `docker-canvas` is fetching `instructure/canvas-lms:stable` on Docker Hub.
This version has not been updated in 8 months.
It's not the current stable version.

> We need to install a stable branch directly from instructure.

<https://github.com/instructure/canvas-lms/wiki/Quick-Start>

## Which operating system?

- For macOS: this page shows `dinghy` that applies only to macOS.
- For Linux: use `dory`; follow the Linux instructions on [Quick-Start](https://github.com/instructure/canvas-lms/wiki/Quick-Start)

---

## Automated setup

<https://github.com/instructure/canvas-lms/wiki/Quick-Start#automated-setup>

> commit dc6478a81bbcfa85f9886d7d6d7ff5dcfbaf5686  doesn't have bugs!

```bash
mkdir -p ~/Code/ctlt && cd $_
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

- Fix missing `pgxs.mk` error.
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

### Destroy Dinghy

Try this first, this might be enough:

```bash
dinghy destroy -f
```

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

> And restart Docker Desktop for macOS!
> And restart Terminal!

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
```
First, run:

  eval "$(dinghy env)"

This will set up environment variables for docker to work with the dinghy VM.

Running Canvas:

  docker-compose up -d
  open http://canvas.docker
```

> SUCCESS!

### Start Canvas

```bash
eval "$(dinghy env)"
docker-compose up -d
```

Browse to <http://canvas.docker>

```bash
docker container ls
```
```
CONTAINER ID        IMAGE                               COMMAND                  CREATED             STATUS              PORTS                                                                           NAMES
431045129fd5        canvas_web                          "/tini -- /usr/src/e…"   19 seconds ago      Up 17 seconds       80/tcp                                                                          canvas_web_1
01dba4e3549f        canvas_jobs                         "bundle exec script/…"   19 seconds ago      Up 17 seconds       80/tcp                                                                          canvas_jobs_1
0267ffd47a04        canvas_webpack                      "yarn run webpack"       19 seconds ago      Up 17 seconds       80/tcp                                                                          canvas_webpack_1
858a660eeff6        redis:alpine                        "docker-entrypoint.s…"   16 minutes ago      Up 16 minutes       6379/tcp                                                                        canvas_redis_1
7096d75c1767        canvas_postgres                     "docker-entrypoint.s…"   16 minutes ago      Up 16 minutes       5432/tcp                                                                        canvas_postgres_1
25bb59254989        codekitchen/dinghy-http-proxy:2.5   "/app/docker-entrypo…"   44 minutes ago      Up 44 minutes       0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp, 19322/tcp, 0.0.0.0:19322->19322/udp   dinghy_http_proxy
```

### Dump original data

In case you need to reset data.

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /tmp/canvas_0.sql"
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres -Fc canvas > /tmp/canvas.postgresql.reset.dump"
docker cp canvas_postgres_1:/tmp/canvas.postgresql.reset.dump ~/Code/ctlt/iPeer/app/config/lti13/canvas/
docker exec -it canvas_postgres_1 ls -lAFh /tmp
```
