# Import fixtures in Canvas

## Prerequisite

- <https://www.postgresql.org/docs/9.5/backup-dump.html#BACKUP-DUMP-LARGE>
- <https://www.postgresql.org/docs/9.5/app-pgrestore.html#APP-PGRESTORE-OPTIONS>

After having manually inserted fixture data,
dump the `canvas` dB in custom format (-Fc).

```bash
cd ~/Code/ctlt/canvas
docker-compose up -d
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres -Fc canvas > /tmp/canvas.postgresql.dump"
docker cp canvas_postgres_1:/tmp/canvas.postgresql.dump ~/Code/ctlt/iPeer/app/config/lti13/canvas/
```

## Import

<https://www.postgresql.org/docs/9.5/app-pgrestore.html#APP-PGRESTORE-EXAMPLES>

1. Copy dump file from iPeer to Canvas.
2. Disconnect all users from `canvas` dB with `docker-compose down`.
3. Drop & Restore `canvas` dB with custom format dump file.

```bash
cd ~/Code/ctlt/canvas
docker-compose down
docker-compose up -d postgres
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/canvas.postgresql.dump canvas_postgres_1:/tmp/
docker exec -it canvas_postgres_1 dropdb -U postgres canvas
docker exec -it canvas_postgres_1 pg_restore -U postgres -C -d postgres /tmp/canvas.postgresql.dump
docker-compose up -d
```

Refresh <http://canvas.docker>
