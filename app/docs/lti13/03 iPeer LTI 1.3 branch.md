# iPeer LTI 1.3 branch

## Create repository

Make <https://repo.code.ubc.ca/smarsh05/ipeer-lti13>

## Set remote URL

```bash
cd ~/Code/ctlt/iPeer
git remote set-url origin git@repo.code.ubc.ca:smarsh05/ipeer-lti13.git
git push --set-upstream origin master
```

## Create branch

Add `lti-1p3-tool` Composer package in this new git branch.

```bash
git checkout -b lti-1.3

php composer.phar config repositories.lti-1.3 '{"type": "vcs", "url": "https://github.com/IMSGlobal/lti-1-3-php-library"}'
php composer.phar require imsglobal/lti-1p3-tool dev-master
git diff composer.json

git status
git add .
git commit -m"Added lti-1p3-tool composer package"
git push --set-upstream origin lti-1.3
```
