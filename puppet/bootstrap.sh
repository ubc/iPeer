# install phing
pear config-set auto_discover 1
pear channel-discover pear.phing.info
pear upgrade Archive_Tar
pear install phing/phing

# restart server
sudo service php-fpm restart
