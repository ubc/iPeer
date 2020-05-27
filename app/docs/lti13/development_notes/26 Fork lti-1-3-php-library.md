# Fork lti-1-3-php-library

## Fork

- Go to: <https://github.com/IMSGlobal/lti-1-3-php-library>
- Click Fork button
- Select UBC account
- Go to: <https://github.com/ubc/lti-1-3-php-library>
- Delete stale branches

## Patch

```bash
cd ~/Code/ctlt
git clone git@github.com:ubc/lti-1-3-php-library.git
cd ~/Code/ctlt/lti-1-3-php-library

git checkout -b patch-EOL
cd ~/Code/ctlt/lti-1-3-php-library/src/lti
dos2unix Cache.php
dos2unix Cookie.php
dos2unix LTI_Deep_Link.php
dos2unix LTI_Message_Launch.php
dos2unix LTI_OIDC_Login.php
dos2unix LTI_Service_Connector.php
git add .
git commit -m"Corrected EOL on certain files."
git push --set-upstream origin patch-EOL

git checkout -b patch-Cookie.php
cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/Cookie.php.diff .
patch -p0 Cookie.php < Cookie.php.diff
rm Cookie.php.diff
git add -u
git commit -m"Added compatible setcookie() for PHP < 7.3"
git push --set-upstream origin patch-Cookie.php
cd ~/Code/ctlt/lti-1-3-php-library
```

## Change LTI 1.3 Composer package from IMSGlobal

```bash
cd ~/Code/ctlt/iPeer
wget -O composer.phar https://getcomposer.org/composer-stable.phar
php composer.phar config repositories.lti-1.3 '{"type": "vcs", "url": "https://github.com/ubc/lti-1-3-php-library"}'
php composer.phar require imsglobal/lti-1p3-tool dev-master
git diff composer.json

git status
git add -u
git commit -m"Changed composer package imsglobal/lti-1-3-php-library to forked ubc/lti-1-3-php-library."
git push
```

## Composer update

```bash
cd ~/Code/ctlt/iPeer
php composer.phar update
```
