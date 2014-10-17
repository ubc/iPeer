# install phing
pear config-set auto_discover 1
pear channel-discover pear.phing.info
pear install phing/phing

# restart server
sudo service php-fpm restart
