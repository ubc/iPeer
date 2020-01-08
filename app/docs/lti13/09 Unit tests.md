# Unit tests

## Run in console

```bash
cd ~/Code/ctlt/iPeer
cake/console/cake testsuite app case models/lti13
```
```
PHP Warning:  mysqli_connect(): (HY000/2002): No such file or directory in
/Users/steven/Code/ctlt/iPeer/cake/libs/model/datasources/dbo/dbo_mysqli.php on line 63
```

## Run basic PHPUnit

```bash
cd ~/Code/ctlt/iPeer
wget -O phpunit.phar https://phar.phpunit.de/phpunit-5.phar
php phpunit.phar app/tests/cases/models/lti13.test.php
```

Many notices of missing files.

## Check the MySQL schema and data

In `app/tests/fixtures/course_fixture.php`,
it shows `public $import = array('model' => 'Course', 'records' => true);`
so it imports the schema and data from the default settings in `app/config/database.php`

> In `docker-compose.yml`,
> edit `services.web-unittest.ports`
> from `- "8081:80"` to `- "8082:80"`

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db /bin/bash
mysql ipeer -h db -u ipeer -p
```

`MariaDB [ipeer]>`

```bash
SHOW TABLES;
SHOW CREATE TABLE courses\G
Create Table: CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(80) DEFAULT NULL,
  `homepage` varchar(100) DEFAULT NULL,
  `self_enroll` varchar(3) DEFAULT 'off',
  `password` varchar(25) DEFAULT NULL,
  `record_status` varchar(1) NOT NULL DEFAULT 'A',
  `creator_id` int(11) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updater_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `canvas_id` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course` (`course`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8
```

```
SELECT * FROM courses\G
*************************** 1. row ***************************
           id: 1
       course: MECH 328
        title: Mechanical Engineering Design Project
     homepage: http://www.mech.ubc.ca
  self_enroll: off
     password: NULL
record_status: A
   creator_id: 1
      created: 2006-06-20 14:14:45
   updater_id: NULL
     modified: 2006-06-20 14:14:45
    canvas_id: NULL
*************************** 2. row ***************************
           id: 2
       course: APSC 201
        title: Technical Communication
     homepage: http://www.apsc.ubc.ca
  self_enroll: off
     password: NULL
record_status: A
   creator_id: 1
      created: 2006-06-20 14:15:38
   updater_id: NULL
     modified: 2006-06-20 14:39:31
    canvas_id: NULL
*************************** 3. row ***************************
           id: 3
       course: CPSC 101
        title: Connecting with Computer Science
     homepage: http://www.ugrad.cs.ubc.ca/~cs101/
  self_enroll: off
     password: NULL
record_status: I
   creator_id: 1
      created: 2006-06-20 00:00:00
   updater_id: NULL
     modified: NULL
    canvas_id: NULL
...
```

## Run unit tests in Docker

> In `docker-compose.yml`,
> edit `services.web-unittest.ports`
> from `- "8081:80"` to `- "8082:80"`

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_app_unittest /bin/bash
```

`root@f56a39a0172f:/var/www/html#`

### Missing ipeer_test database

A `could not find driver` error seems to be because the database named `ipeer_test` is missing.

```bash
vendor/bin/phing test
IPEER_DB_NAME=ipeer_test vendor/bin/phing test
IPEER_DB_NAME=ipeer_test vendor/bin/phing init-test-db
IPEER_DB_NAME=ipeer vendor/bin/phing test
IPEER_DB_NAME=ipeer vendor/bin/phing init-test-db
```
```
BUILD FAILED
/var/www/html/build.xml:57:116: /var/www/html/build.xml:57:116: could not find driver
```

```bash
IPEER_DB_NAME=ipeer_test cake/console/cake testsuite app case models/lti13
```
```
Warning: mysqli_connect(): (HY000/1044): Access denied for user 'ipeer'@'%' to database 'ipeer_test' 
in /var/www/html/cake/libs/model/datasources/dbo/dbo_mysqli.php on line 63
```
```bash
exit
```

---

## Create test database table

`app/config/sql/ipeer_samples_data.sql` does not contain a `CREATE DATABASE IF NOT EXISTS ipeer_test;` statement.

`phing` is trying to import `ipeer_samples_data.sql` in a non-existant `ipeer_test` table.

So we have to manually create the `ipeer_test` table.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db /bin/bash
```

`root@bdae36de7a7d:/#`

Use root password found in `docker-compose.yml`.

```bash
mysql -h db -u root -p -e "CREATE DATABASE ipeer_test;"
mysql -h db -u root -p -e "SHOW DATABASES;"
mysql -h db -u root -p -e "SHOW GRANTS FOR ipeer;"
```
```
+------------------------------------------------------------------------------------------------------+
| Grants for ipeer@%                                                                                   |
+------------------------------------------------------------------------------------------------------+
| GRANT USAGE ON *.* TO 'ipeer'@'%' IDENTIFIED BY PASSWORD '*D4BA1077F133A335E045EAC769AAEDFFC639F5E3' |
| GRANT ALL PRIVILEGES ON `ipeer`.* TO 'ipeer'@'%'                                                     |
+------------------------------------------------------------------------------------------------------+
```
```bash
mysql -h db -u root -p -e "GRANT ALL PRIVILEGES ON ipeer_test.* TO 'ipeer'@'%'"
mysql -h db -u root -p -e "SHOW DATABASES;"
mysql -h db -u root -p -e "SHOW GRANTS FOR ipeer;"
```
```
+------------------------------------------------------------------------------------------------------+
| Grants for ipeer@%                                                                                   |
+------------------------------------------------------------------------------------------------------+
| GRANT USAGE ON *.* TO 'ipeer'@'%' IDENTIFIED BY PASSWORD '*D4BA1077F133A335E045EAC769AAEDFFC639F5E3' |
| GRANT ALL PRIVILEGES ON `ipeer`.* TO 'ipeer'@'%'                                                     |
| GRANT ALL PRIVILEGES ON `ipeer_test`.* TO 'ipeer'@'%'                                                     |
+------------------------------------------------------------------------------------------------------+
```
```bash
exit
```

```bash
docker exec -it ipeer_app_unittest /bin/bash
```

`root@f56a39a0172f:/var/www/html#`

```bash
vendor/bin/phing test
```
```
BUILD FAILED
/var/www/html/build.xml:57:116: /var/www/html/build.xml:57:116: could not find driver
```

```bash
cake/console/cake testsuite app case models/lti13
```
```
Error: Missing database table 'users' for model 'User'
```

---

## Importing sample SQL file manually

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db /bin/bash
```

`root@9acf1ec69d64:/#`

```bash
mysql ipeer-test -h db -u ipeer -p < app/config/sql/ipeer_samples_data.sql
```
```bash
exit
```

```bash
docker exec -it ipeer_app_unittest /bin/bash
```

`root@7885d4d2f2fc:/var/www/html#`

```bash
cake/console/cake testsuite app case models/lti13
```

---

## Fixing phing

