# Import fixtures in iPeer

## Prerequisite

> This has been done after the first installation of local iPeer.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d db
docker exec -it ipeer_db sh -c "mysqldump ipeer -u ipeer -p > /var/lib/mysql/ipeer.reset.sql"
```

So that `.data/ipeer.reset.sql` exists.

## Before test

Reset data in `ipeer` table.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /var/lib/mysql/ipeer.reset.sql"
```

## Update lti_id of root user

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 psql -U postgres canvas -tc "SELECT lti_id FROM users WHERE name LIKE 'root@canvas';"
```
```
 a028ce99-9f0b-493f-b59e-3e5b90733b41
```

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "UPDATE users SET lti_id = 'a028ce99-9f0b-493f-b59e-3e5b90733b41' WHERE username LIKE 'root';"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -sNe "SELECT lti_id FROM users WHERE username LIKE 'root';"
```
```
a028ce99-9f0b-493f-b59e-3e5b90733b41
```

## Run manual test

Browse to <http://localhost:8080/lti13>



    ## Run controller test

    <https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#testing-controllers>

    ```bash
    cd ~/Code/ctlt/iPeer
    docker-compose up -d
    docker-compose run --rm ipeer_app cake/console/cake -app app testsuite app case models/lti13
    ```
