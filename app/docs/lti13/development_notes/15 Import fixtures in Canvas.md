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
pg_dump -U postgres -Fc canvas > /usr/src/app/tmp/canvas.sql.dump; exit
```

```bash
cp ~/Code/ctlt/canvas/.postgres_app_tmp/canvas.sql.dump ~/Code/ctlt/iPeer/.data/
```

## Import

<https://www.postgresql.org/docs/9.5/app-pgrestore.html#APP-PGRESTORE-EXAMPLES>

1. Copy dump file from iPeer to Canvas.
2. Disconnect all users from `canvas` dB with `docker-compose down`.
3. Drop `canvas` dB.
4. Restore `canvas` dB with custom format dump file.

```bash
cd ~/Code/ctlt/canvas
cp ~/Code/ctlt/iPeer/.data/canvas.sql.dump .postgres_app_tmp/
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
