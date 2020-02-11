# Import fixtures in Canvas

## Prerequisite

- <https://www.postgresql.org/docs/9.5/backup-dump.html#BACKUP-DUMP-LARGE>
- <https://www.postgresql.org/docs/9.5/app-pgrestore.html#APP-PGRESTORE-OPTIONS>

After having manually inserted fixture data,
dump the `canvas` dB in custom format (-Fc).

```bash
cd ~/Code/ctlt/canvas
docker-compose up -d
docker exec -it canvas_postgres_1 bash
```

`root@27c698737093:/#`

```bash
pg_dump -U postgres -Fc canvas > /usr/src/app/tmp/canvas.sql.dump
exit
```

## Import

<https://www.postgresql.org/docs/9.5/app-pgrestore.html#APP-PGRESTORE-EXAMPLES>

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

---

## Make a SQL file of diff

We just want to test the LTI 1.3 connection, so:

- Input directly in dB
    - Hardcode keys in web test
    - Just add users to course
- Code a test that logs in to iPeer and generates the LTI 1.3 launch sequence

Canvas:

    - run rake to reset/start the .data for canvas
    - pg_dump
    - http://canvas.docker to populate the accounts in the course(s)
    - pg_dump
    - diff the two dumps
    - add a developer key
    - make a SQL for a migration file specifically for this test

iPeer:

- add logging to the test
- in test, log the list of ipeer roster before launch
- test launch and log it
- log the list of ipeer roster after launch

