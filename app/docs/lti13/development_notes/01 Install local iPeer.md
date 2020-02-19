# Install local iPeer

- <http://ipeer.ctlt.ubc.ca>
- <https://github.com/ubc/iPeer>

## Local installation of iPeer

<https://github.com/ubc/iPeer/blob/master/readme.md>

## Prerequisites

<https://github.com/ubc/compair#development-prerequisites>

- Docker Desktop for Mac contains Docker Engine and Docker Compose
- npm is installed

## Install

In Mac terminal:

```bash
mkdir -p ~/Code/ctlt && cd $_
git clone https://github.com/ubc/iPeer.git
cd ~/Code/ctlt/iPeer
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

## Run Docker

In Mac terminal:

```bash
docker-compose up -d
```
```
ERROR: for 7627c664aa24_ipeer_web_unittest  
Cannot start service web-unittest: b'driver failed programming external connectivity on endpoint ipeer_web_unittest 
ERROR: for web-unittest  Cannot start service web-unittest: b'driver failed programming external connectivity on endpoint ipeer_web_unittest 
(102a703dbc60ddcbda1fdf3fa11371553dc720ceac3ceef3d552d81dbbda68ac): 
Error starting userland proxy: listen tcp 0.0.0.0:8081: bind: address already in use'
ERROR: Encountered errors while bringing up the project.
```

### Fix port in ipeer_web_unittest

> McAfee Security Endpoint for Mac uses port 8081.

Change ports in `docker-compose.yml`: `services.web-unittest.ports`.

```diff
- - "8081:80"
+ - "8082:80"
```

```bash
docker-compose down
docker-compose up -d
```

Success.

### Install PHP Webdriver and Sausage under `vendors` directory.

```bash
git submodule init
git submodule update
```

## Installation wizard

Browse to: <http://localhost:8080>

I see Installation Wizard.

- Step 1: Example app
- Step 2
- Step 3: Installation with Sample Data
- Step 4
    - root
    - password

iPeer Installation Complete!

Browse to: <http://localhost:8080/login>

- root
- password

OK. I'm logged in.
