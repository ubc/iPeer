# Web tests

- <https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#web-testing-testing-views>
- <http://simpletest.sourceforge.net/en/web_tester_documentation.html>

## Adjust Docker Desktop memory

- Docker Desktop > Preferences... > Disk tab
    - Disk image size: 128GB > Apply button
- Docker Desktop > Preferences... > Advanced tab
    - Memory: 3.0GB > Apply & Restart button

---

## Install Docker Canvas for Integration Testing

<https://github.com/ubc/docker-canvas>

```bash
cd ~/Code/ctlt
git clone https://github.com/ubc/docker-canvas.git
cd ~/Code/ctlt/docker-canvas
docker-compose up -d db
docker-compose run --rm app bundle exec rake db:create db:initial_setup
```
```
...
What email address will the site administrator account use? > steven.marshall@ubc.ca
Please confirm > steven.marshall@ubc.ca
What password will the site administrator use? > *********
Please confirm > *********
unknown OID 3220: failed to recognize type of 'pg_current_xlog_location'. It will be treated as String.
What do you want users to see as the account name? This should probably be the name of your organization. > LTI13-Canvas-Account
To help our developers better serve you, Instructure would like to collect some usage data about your Canvas installation. You can change this setting at any time.:
1. Opt in
2. Only send anonymized data
3. Opt out completely
> 3
You have opted out.
You can change this feature at any time by running the rake task 'rake db:configure_statistics_collection'
................................................................................
Notifications Loaded
No notification files found for Assignment Publishing Reminder
No notification files found for Assignment Grading Reminder
No notification files found for Assignment Due Date Reminder
No notification files found for Rubric Assessment Invitation
No notification files found for Migration Export Ready
No notification files found for Migration Import Finished
No notification files found for Migration Import Failed

Initial data loaded
```

```bash
docker-compose up -d --build
docker ps -a
```
```
CONTAINER ID        IMAGE                    COMMAND                  CREATED             STATUS              PORTS                              NAMES
f2a930f78f94        docker-canvas            "bundle exec script/…"   14 seconds ago      Up 13 seconds       0.0.0.0:8902->80/tcp               docker-canvas_worker_1
2124d4b7eb4a        docker-canvas            "bash -c './wait-for…"   14 seconds ago      Up 13 seconds       0.0.0.0:8900->80/tcp               docker-canvas_app_1
c3d32f87d410        redis:3.2.4              "docker-entrypoint.s…"   2 minutes ago       Up 2 minutes        6379/tcp                           docker-canvas_redis_1
eb1ac4cb3a66        mailhog/mailhog:v1.0.0   "MailHog"                2 minutes ago       Up 2 minutes        1025/tcp, 0.0.0.0:8901->8025/tcp   docker-canvas_mail_1
64bbbe2f0441        postgres:9.6             "docker-entrypoint.s…"   2 minutes ago       Up 2 minutes        0.0.0.0:15432->5432/tcp            docker-canvas_db_1
```

---

## Set up iPeer for Integration Testing

From [readme.md](/readme.md) -> Running integration tests

### Install PHP Webdriver and Sausage

```bash
cd ~/Code/ctlt/iPeer
git submodule init
git submodule update
```

### Setup ubc/docker-canvas container and bridge for iPeer and Canvas

> `docker-compose up -d` twice for `ipeer_worker` to start.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker-compose up -d
docker ps -a
```
```
CONTAINER ID        IMAGE                    COMMAND                  CREATED              STATUS                     PORTS                              NAMES
363fcd396c82        ubcctlt/ipeer-web        "/docker-entrypoint.…"   6 seconds ago        Up 3 seconds               443/tcp, 0.0.0.0:8080->80/tcp      ipeer_web
54363c832c6d        ubcctlt/ipeer-web        "/docker-entrypoint.…"   6 seconds ago        Up 3 seconds               443/tcp, 0.0.0.0:8082->80/tcp      ipeer_web_unittest
cba635517c32        ubcctlt/ipeer-app        "docker-php-entrypoi…"   9 seconds ago        Up 6 seconds               0.0.0.0:9000->9000/tcp             ipeer_app
553bef3b686c        ubcctlt/ipeer-app        "docker-php-entrypoi…"   9 seconds ago        Exited (0) 3 seconds ago                                      ipeer_worker
4826943b3772        ubcctlt/ipeer-app        "docker-php-entrypoi…"   9 seconds ago        Up 6 seconds               0.0.0.0:9001->9000/tcp             ipeer_app_unittest
03806024bfb5        mariadb:10.1             "docker-entrypoint.s…"   10 seconds ago       Up 9 seconds               0.0.0.0:13306->3306/tcp            ipeer_db
f2a930f78f94        docker-canvas            "bundle exec script/…"   About a minute ago   Up About a minute          0.0.0.0:8902->80/tcp               docker-canvas_worker_1
2124d4b7eb4a        docker-canvas            "bash -c './wait-for…"   About a minute ago   Up About a minute          0.0.0.0:8900->80/tcp               docker-canvas_app_1
c3d32f87d410        redis:3.2.4              "docker-entrypoint.s…"   3 minutes ago        Up 3 minutes               6379/tcp                           docker-canvas_redis_1
eb1ac4cb3a66        mailhog/mailhog:v1.0.0   "MailHog"                3 minutes ago        Up 3 minutes               1025/tcp, 0.0.0.0:8901->8025/tcp   docker-canvas_mail_1
64bbbe2f0441        postgres:9.6             "docker-entrypoint.s…"   4 minutes ago        Up 4 minutes               0.0.0.0:15432->5432/tcp            docker-canvas_db_1
```

```bash
docker network create canvas_ipeer_network
docker network connect canvas_ipeer_network ipeer_app
docker network connect canvas_ipeer_network ipeer_app_unittest
docker network connect canvas_ipeer_network docker-canvas_app_1
docker network ls
```

### Run the Selenium + Chrome container

```bash
docker run -d -p 4444:4444 -e SE_OPTS="-enablePassThrough false" -e TZ="Canada/Pacific" --name selenium-local --shm-size 2g selenium/standalone-chrome:3.7.1-argon
docker ps -a
```
```
CONTAINER ID        IMAGE                                    COMMAND                  CREATED              STATUS                          PORTS                              NAMES
c3ad9276d127        selenium/standalone-chrome:3.7.1-argon   "/opt/bin/entry_poin…"   3 seconds ago        Up Less than a second           0.0.0.0:4444->4444/tcp             selenium-local
363fcd396c82        ubcctlt/ipeer-web                        "/docker-entrypoint.…"   About a minute ago   Up About a minute               443/tcp, 0.0.0.0:8080->80/tcp      ipeer_web
54363c832c6d        ubcctlt/ipeer-web                        "/docker-entrypoint.…"   About a minute ago   Up About a minute               443/tcp, 0.0.0.0:8082->80/tcp      ipeer_web_unittest
cba635517c32        ubcctlt/ipeer-app                        "docker-php-entrypoi…"   About a minute ago   Up About a minute               0.0.0.0:9000->9000/tcp             ipeer_app
553bef3b686c        ubcctlt/ipeer-app                        "docker-php-entrypoi…"   About a minute ago   Exited (0) About a minute ago                                      ipeer_worker
4826943b3772        ubcctlt/ipeer-app                        "docker-php-entrypoi…"   About a minute ago   Up About a minute               0.0.0.0:9001->9000/tcp             ipeer_app_unittest
03806024bfb5        mariadb:10.1                             "docker-entrypoint.s…"   About a minute ago   Up About a minute               0.0.0.0:13306->3306/tcp            ipeer_db
f2a930f78f94        docker-canvas                            "bundle exec script/…"   2 minutes ago        Up 2 minutes                    0.0.0.0:8902->80/tcp               docker-canvas_worker_1
2124d4b7eb4a        docker-canvas                            "bash -c './wait-for…"   2 minutes ago        Up 2 minutes                    0.0.0.0:8900->80/tcp               docker-canvas_app_1
c3d32f87d410        redis:3.2.4                              "docker-entrypoint.s…"   5 minutes ago        Up 5 minutes                    6379/tcp                           docker-canvas_redis_1
eb1ac4cb3a66        mailhog/mailhog:v1.0.0                   "MailHog"                5 minutes ago        Up 5 minutes                    1025/tcp, 0.0.0.0:8901->8025/tcp   docker-canvas_mail_1
64bbbe2f0441        postgres:9.6                             "docker-entrypoint.s…"   5 minutes ago        Up 5 minutes                    0.0.0.0:15432->5432/tcp            docker-canvas_db_1
```

In browser: <http://localhost:4444/wd/hub>

### Create a network bridge to connect iPeer, Canvas, and Selenium together

```bash
docker network create canvas_ipeer_network_it
docker network connect canvas_ipeer_network_it ipeer_app_unittest
docker network connect canvas_ipeer_network_it ipeer_web_unittest
docker network connect canvas_ipeer_network_it docker-canvas_app_1
docker network connect canvas_ipeer_network_it selenium-local
docker network ls
```
```
NETWORK ID          NAME                      DRIVER              SCOPE
346cf0130f2c        bridge                    bridge              local
19e134af8815        canvas_ipeer_network      bridge              local
1d86fd812852        canvas_ipeer_network_it   bridge              local
60235e985848        docker-canvas_default     bridge              local
16d0edd60f45        host                      host                local
db2f993beca3        ipeer_default             bridge              local
b53cccf3c22e        none                      null                local
```

### Run tests in container

```bash
docker exec -it ipeer_app_unittest bash
```

`root@62e7ea10f889:/var/www/html#`

```bash
vendor/bin/phing init-test-db
cake/console/cake -app app testsuite app group system
cake/console/cake -app app testsuite app case system/studentSimple
```

---

## Reset

### Down

```bash
cd ~/Code/ctlt/iPeer
docker-compose down
docker network disconnect canvas_ipeer_network_it selenium-local
docker container stop selenium-local
cd ~/Code/ctlt/docker-canvas
docker-compose down
docker network rm canvas_ipeer_network
docker network rm canvas_ipeer_network_it
docker network ls
docker ps -a
```

### Up

```bash
cd ~/Code/ctlt/docker-canvas
docker-compose up -d
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker network create canvas_ipeer_network
docker network connect canvas_ipeer_network ipeer_app
docker network connect canvas_ipeer_network ipeer_app_unittest
docker network connect canvas_ipeer_network docker-canvas_app_1
docker run --rm -d -p 4444:4444 -e SE_OPTS="-enablePassThrough false" -e TZ="Canada/Pacific" --name selenium-local --shm-size 2g selenium/standalone-chrome:3.7.1-argon
docker network create canvas_ipeer_network_it
docker network connect canvas_ipeer_network_it ipeer_app_unittest
docker network connect canvas_ipeer_network_it ipeer_web_unittest
docker network connect canvas_ipeer_network_it docker-canvas_app_1
docker network connect canvas_ipeer_network_it selenium-local
docker network ls
docker ps -a
docker exec -it ipeer_app_unittest bash
```
`root@62e7ea10f889:/var/www/html#`

```bash
cake/console/cake -app app testsuite app case system/lti13_login
```
