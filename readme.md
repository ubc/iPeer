# iPeer

iPeer is a peer evaluation app that enables instructors to create forms and rubrics for students to fill out. It is commonly used in Team-Based Learning or project-based courses, where teammates have to evaluate each other's contributions.

> [!IMPORTANT]
> iPeer was first developed in 2002 and has had many contributors over the decades. However, maintenance and improvements have been sporadic over the last few years. While iPeer is still deployable and usable, up-to-date development documentation is limited. If you are interested in using iPeer for your institution, please contact UBC's CTLT and LTIC departments, and we can provide you with the latest operational recommendations and resources. As of 2026, we are considering rebuilding iPeer on a new platform to address its existing issues and encourage contributions from other institutions.

## Local Development

### Setting up the Repo
If you have `docker` and `docker compose` environment setup on your machine, you can start running iPeer for testing / development quickly by following these steps.
```
# get the source
git clone https://github.com/ubc/iPeer.git

# change to iPeer directory
cd iPeer

# pull git submodules
git submodule update --init --recursive

# use the default database config
cp app/config/database.php.default app/config/database.php 

# pull images and start containers
docker compose pull && docker compose up -d

# open a shell into the app container
docker compose exec -it app bash
# within the container, install any missing packages
composer install
# exit the container
exit

# on host, change the permission of tmp folders
chmod -R 777 app/tmp

# create DB schema and load sample data
# (or use another seed file instead of ipeer_samples_data.sql)
docker compose exec -T db bash -c 'mysql -u root -p"$MYSQL_ROOT_PASSWORD" ipeer' < app/config/sql/ipeer_samples_data.sql

# restart containers
docker compose restart
```

Then visit http://localhost:8080/. You can log in to an administrator account using username `admin1`. The password for most sample users is: `ipeeripeer`.

### Running iPeer unit/integration tests
  
To run the unit tests on containers:
- Start up the special test containers with `docker compose --profile test up -d`
- Shell into the test container: `docker compose exec -it app-test bash`
- You may need to reinstall composer dependencies again `composer install`
- Inside of `/var/www/html`, run the command `vendor/bin/phing test` (for all tests) or `vendor/bin/phing test-single -Dtest.type="..." -Dtest.name="..."`. Examples:
    - `vendor/bin/phing test-single -Dtest.type="case" -Dtest.name="controllers/V1Controller"` (see `./app/tests/cases`)
    - `vendor/bin/phing test-single -Dtest.type="group" -Dtest.name="model"` (see `./app/tests/groups`)
    

## Configuring Caliper

In order to enable Caliper, both `CALIPER_HOST` and `CALIPER_API_KEY` must be set.

`CALIPER_HOST`: Set the Caliper LRS url.

`CALIPER_API_KEY`: API key for sending Caliper events to the LRS.

`CALIPER_BASE_URL`: Optionally set a base url to use for all events. This is useful to help keep statement urls consistent if the url of your instance changes over time or is accessible though different routes (ex http+https or multiple sub-domains). (Uses base url of request by default).

You may optionally override the user default IRI (from `$base_url/users/view/$user_id`) to something more identifiable when setting both `CALIPER_ACTOR_BASE_URL` and `CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM`.

`CALIPER_ACTOR_BASE_URL`: Optionally set the actor's homepage to something else. This will be string formated to allow `CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM` to be inserted into it (use `%s` where you want the unique identifier to appear). ex: `http://www.ubc.ca/%s`

`CALIPER_ACTOR_UNIQUE_IDENTIFIER_PARAM`: Optionally set the actor's unique identifier using any column from the `user` table (ex: `username`, `id`, `email`). Will be inserted into the `CALIPER_ACTOR_BASE_URL` string.
