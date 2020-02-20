# Patch Canvas files

## Minimize Webpack complications

- Bypass `webpack:production` errors

### Diff

```bash
cd ~/Code/ctlt/iPeer
cp ~/Code/ctlt/canvas/Dockerfile app/config/lti13/patches/Dockerfile
nano app/config/lti13/patches/Dockerfile
```

Last line:

```diff
- RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ # RUN COMPILE_ASSETS_NPM_INSTALL=0 bundle exec rake canvas:compile_assets
+ RUN COMPILE_ASSETS_BUILD_JS=0 bundle exec rake canvas:compile_assets_dev
```

```bash
diff ~/Code/ctlt/canvas/Dockerfile app/config/lti13/patches/canvas/Dockerfile > app/config/lti13/patches/canvas/Dockerfile.diff
cat app/config/lti13/patches/canvas/Dockerfile.diff
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
patch -p0 ~/Code/ctlt/canvas/Dockerfile < app/config/lti13/patches/canvas/Dockerfile.diff
rm app/config/lti13/patches/canvas/Dockerfile
```

### Revert patch if needed

```bash
patch -R -p0 ~/Code/ctlt/canvas/Dockerfile < app/config/lti13/patches/canvas/Dockerfile.diff
```

## Fix postgres container

- Fix missing `pgxs.mk` error.
- Use `psql` version 9.5, not 9.6.

### Diff

```bash
cd ~/Code/ctlt/iPeer
cp ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile app/config/lti13/patches/canvas/postgres-Dockerfile
nano postgres-Dockerfile
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
diff ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile app/config/lti13/patches/canvas/postgres-Dockerfile > app/config/lti13/patches/canvas/postgres-Dockerfile.diff
cat app/config/lti13/patches/canvas/postgres-Dockerfile.diff
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
patch -p0 ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile < app/config/lti13/patches/canvas/postgres-Dockerfile.diff
rm app/config/lti13/patches/canvas/postgres-Dockerfile
```

### Revert patch if needed

```bash
patch -R -p0 ~/Code/ctlt/canvas/docker-compose/postgres/Dockerfile < app/config/lti13/patches/canvas/postgres-Dockerfile.diff
```
