# Patch iPeer LTI 1.3 vendor library

> March 2020:
> After IMS Global LTI 1.3 PHP library update, it introduced samesite cookie, but for PHP 7.3 only.
> I had to make a PHP < 7.3 version of `setcookie()` in the `Cookie` class.

### Patch

```bash
cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/Cookie.php.diff ~/Code/ctlt/iPeer/vendor/imsglobal/lti-1p3-tool/src/lti/Cookie.php.diff
patch -p0 Cookie.php < Cookie.php.diff
```

### Revert patch if needed

```bash
patch -R -p0 Cookie.php < Cookie.php.diff
```
