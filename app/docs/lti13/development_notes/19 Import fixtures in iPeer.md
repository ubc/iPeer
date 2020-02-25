# Import fixtures in iPeer

## Prerequisite

> This has been done after the first installation of local iPeer.

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db sh -c "mysqldump ipeer -u ipeer -p > /tmp/ipeer.reset.sql"
docker exec -it ipeer_db ls -lAFh /tmp
docker cp ipeer_db:/tmp/ipeer.reset.sql ~/Code/ctlt/iPeer/app/config/lti13/canvas/
```

## Reset data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker cp ~/Code/ctlt/iPeer/app/config/lti13/canvas/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db ls -lAFh /tmp
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

## Update lti_id of root user

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 psql -U postgres canvas -tc "SELECT lti_id FROM users WHERE name LIKE 'root@canvas';"
```
```
 f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd
```

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "UPDATE users SET lti_id = 'f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd' WHERE username LIKE 'root';"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -sNe "SELECT lti_id FROM users WHERE username LIKE 'root';"
```
```
f26afacc-9f3a-4c8e-8c82-85a9b2eee1cd
```

Refresh <http://localhost:8080/lti13>
