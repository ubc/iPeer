iPeer 2.0.7

a quick readme as documentation is not yet compiled.

upgrade from 1.6:

1. copy the ipeer_export folder OUTSIDE of your iPeer 2 folder
2. run it from your browser http://yourserverpath/ipeer_export/ipeer_export.php
3. type the user/pass and whatnot to get the xml file of your database
4. save it for now and continue on to installation of iPeer 2.

iPeer 2 install:

requirements:
PEAR with XML_RPC module
mod_rewrite for apache
PHP 4.3.10+ with GD extension
MYSQL 4+

1.  run http://yourserverpath/youripeerpath/install   (trailing slash may be required)
1b. create a database in MySql
2.  follow the instructions for the install
2a. if you are upgrading, select UPGRADE and load the xml file there.
--     in install4, make sure you set the absolute path to your path!
3.  once the install is complete, delete controllers/install_controller.php and /ipeer_export directory.

please report any bugs you find on sourceforge. we can't fix it if we don't know about it.

questions? please post them on sourceforge.



troubleshooting:

-if you type http://yourserverpath/youripeerpath/ and you get http://yourserverpath/loginout/login, your mod_rewrite
is not set up properly. make sure the line in http.conf has 'AllowOverride All'.
