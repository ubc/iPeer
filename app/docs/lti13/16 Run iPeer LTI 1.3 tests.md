# Run iPeer LTI 1.3 tests

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_app_unittest /bin/bash
```

`root@541c6f2b91ec:/var/www/html#`

```bash
vendor/bin/phing init-test-db
cake/console/cake -app app testsuite app case models/lti13
```
