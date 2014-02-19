# import sample data into database named ipeer
mysql -u ipeer -pipeer -h `hostname` ipeer < /var/www/app/config/sql/ipeer_samples_data.sql

# install oauth php extension required for tests
yum -y install php-devel
yum -y install pcre-devel
pecl install oauth
echo 'extension=oauth.so' > /etc/php.d/oauth.ini

# install phing
pear channel-discover pear.phing.info
pear install phing/phing

# restart server
sudo service php-fpm restart
