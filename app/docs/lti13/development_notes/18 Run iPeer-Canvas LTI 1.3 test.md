# Run iPeer-Canvas LTI 1.3 test

## Prerequisite

> This has been done after the first installation of local iPeer.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d db
docker exec -it ipeer_db sh -c "mysqldump ipeer -u ipeer -p > /var/lib/mysql/ipeer.sql"
```

So that `.data/ipeer.sql` exists.

## Before test

Reset data in `ipeer` table.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /var/lib/mysql/ipeer.sql"
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
