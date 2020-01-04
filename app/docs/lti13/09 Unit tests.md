# Unit tests

## Run in console

```bash
cd ~/Code/ctlt/iPeer
cake/console/cake testsuite app case models/lti13
```

## Check the MySQL schema and data

In `app/tests/fixtures/course_fixture.php`,
it shows `public $import = array('model' => 'Course', 'records' => true);`
so it imports the schema and data from the default settings in `app/config/database.php`

```bash
cd ~/Code/ctlt/iPeer
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
*************************** 4. row ***************************
           id: 4
       course: CPSC 404
        title: Advanced Software Engineering
     homepage: http://www.ugrad.cs.ubc.ca/~cs404/
  self_enroll: off
     password: NULL
record_status: A
   creator_id: 1
      created: 2014-12-15 00:00:00
   updater_id: NULL
     modified: 2014-12-15 00:00:00
    canvas_id: NULL
*************************** 5. row ***************************
           id: 5
       course: APSC 201 001
        title: Technical Communication
     homepage:
  self_enroll: off
     password: NULL
record_status: A
   creator_id: 1
      created: 2019-11-21 22:53:19
   updater_id: 1
     modified: 2019-11-21 22:53:19
    canvas_id: NULL
5 rows in set (0.00 sec)
```

## Run unit tests in Docker

In `docker-compose.yml`,
edit `services.web-unittest.ports` from `- "8081:80"` to `- "8082:80"`

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_app_unittest /bin/bash
```

`root@f56a39a0172f:/var/www/html#`

```bash
vendor/bin/phing test
```
