# Patch Canvas files

## Minimize Webpack complications

- Bypass `webpack:production` errors

### Diff

```bash
cp ~/Code/ctlt/canvas/Dockerfile ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile
nano ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile
```

Last line:

```diff
- RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ # RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ RUN COMPILE_ASSETS_BUILD_JS=0 bundle exec rake canvas:compile_assets_dev
```

```bash
diff ~/Code/ctlt/canvas/Dockerfile ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile > ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile.diff
cat ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile.diff
```

```diff
105c105,106
< RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
---
> # RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
> RUN COMPILE_ASSETS_BUILD_JS=0 bundle exec rake canvas:compile_assets_dev
```

### Patch

```bash
patch -p0 ~/Code/ctlt/canvas/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile.diff
rm ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile
```

### Revert patch if needed

```bash
patch -R -p0 ~/Code/ctlt/canvas/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/canvas/Dockerfile.diff
```

## Fix postgres container

- Fix missing `pgxs.mk` error.
- Use `psql` version 9.5, not 9.6.

### Diff

```bash
cp ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile
nano ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile
```

In the `apt-get install` lines:

```diff
    postgresql-server-dev-9.5 \
+   postgresql-server-dev-9.6 \
    pgxnclient \
```

In the `apt-get remove` lines:

```diff
    postgresql-server-dev-9.5 \
+   postgresql-server-dev-9.6 \
+   postgresql-client-9.6 \
    pgxnclient \
```

```bash
diff ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile > ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile.diff
cat ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile.diff
```

```diff
18a19
>     postgresql-server-dev-9.6 \
32a34,35
>     postgresql-server-dev-9.6 \
>     postgresql-client-9.6 \
```

### Patch

```bash
patch -p0 ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile.diff
rm ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile
```

### Revert patch if needed

```bash
patch -R -p0 ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile < ~/Code/ctlt/iPeer/app/config/lti13/canvas/postgres-Dockerfile.diff
```
