# Run iPeer-Canvas LTI 1.3 test

## Before test

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

## Run manual test

Browse to <http://localhost:8080/lti13>

## Run controller test

<https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#testing-controllers>

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker-compose run --rm ipeer_app cake/console/cake -app app testsuite app case models/lti13
```


## After test

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



+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
```
