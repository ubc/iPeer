---------------------------------------------------------------------------------------------------

## DID NOT WORK!
## TRY NEW [INSTALL](/app/docs/lti13/12%20Install%20Canvas%20container.md) INSTEAD.

---------------------------------------------------------------------------------------------------

# Update docker-canvas container

The `Dockerfile` for `docker-canvas` is fetching `instructure/canvas-lms:stable` on Docker Hub.
This version has not been update in 8 months.
It's not the current stable version.

> We need to update!

---

## Wipe-out if necessary

```bash
docker system prune -a -f --volumes
docker rm -vf $(docker ps -a -q)
docker rmi -f $(docker images -a -q)
```

> And restart Docker Desktop!
> And restart Terminal!

---

## Edit Dockerfile

```
FROM instructure/canvas-lms:stable

# Update Canvas from repository
USER root
RUN git clone -n -b stable https://github.com/instructure/canvas-lms.git ~/canvas-lms \
    && mv ~/canvas-lms/.git . \
    && rmdir ~/canvas-lms \
    && git fetch --all \
    && git reset --hard origin/stable \
    && git config user.email "root@canvas" \
    && git config user.name "Root Canvas" \
    && git add . \
    && git commit -m "Update canvas" \
    && git status

# Update PostgreSQL
USER root
RUN apt-get update \
    && apt-get -y install postgresql-client-9.6

# Install QTIMigrationTool
USER root
RUN mkdir vendor \
    && cd vendor \
    && git clone https://github.com/instructure/QTIMigrationTool.git QTIMigrationTool \
    && cd QTIMigrationTool \
    && chmod +x migrate.py
```

## Update Canvas

```bash
cd ~/Code/ctlt/docker-canvas
docker-compose up -d --build
docker exec -it docker-canvas_app_1 bash
```

`root@364fa8305b5f:/usr/src/app#`

## Run build to update

```bash
./script/canvas_update
```
```
Bringing Canvas up to date ...
  Log file is /usr/src/app/log/canvas_update.log
  Checking out canvas-lms master ...
  Rebasing canvas-lms on HEAD ...
  Checking your gems (bundle check) ...
  Gems are up to date, no need to bundle install ...
  Installing Node packages ...
  Migrating development DB ...
  Migrating test DB ...
  Compiling assets (css and js only, no docs or styleguide) ...

  \o/ Success!
```

> "Compiling assets" will take a long time.

docker-compose run --rm app more log/canvas_update.log
```bash
more log/canvas_update.log
```

> Errors to ignore:
> - ActiveRecord::NoDatabaseError: FATAL:  database "canvas_test" does not exist
> - Parallel::UndumpableException: RuntimeError: Error running js:webpack_production:

## Migrate dB

    docker-compose run --rm app bundle exec rake db:initial_setup

```bash
bundle exec rake db:initial_setup
```
```
...
What email address will the site administrator account use? > root@canvas
...
What password will the site administrator use? > password
...
What do you want users to see as the account name? This should probably be the name of your organization. > (leave blank)
...
3. Opt out completely
> 3
...
```

## Generate branding

    docker-compose run --rm app bundle exec rake brand_configs:generate_and_upload_all

```bash
bundle exec rake brand_configs:generate_and_upload_all
```

```bash
exit
```

## Check in browser

Go to <http://localhost:8900>

If Phusion Passenger error:

```bash
docker-compose run --rm app more /usr/src/app/log/development.log
```

## Final images

```bash
docker image ls
```
```
REPOSITORY               TAG                 IMAGE ID            CREATED             SIZE
docker-canvas            latest              fb406afbf37a        2 days ago          3.64GB
postgres                 9.6                 cb2889ab0680        8 days ago          250MB
instructure/canvas-lms   stable              c9fb7b9bef4a        8 months ago        3.64GB
mailhog/mailhog          v1.0.0              09b680955aed        2 years ago         19.3MB
redis                    3.2.4               190ed8a61620        3 years ago         183MB
```
