

Once you have Docker running iPeer

For development environment, edit the core.php file by setting debug to true

Note: To set debug, scroll to the bottom of the page to make the change.
```textmate
app/config/core.php
  Configure::write('debug', 0); // false
  Configure::write('debug', 1); // true
  Configure::write('debug', 2); // debug mode
```
Next in your terminal, CD to webroot/vue-ts-ipeer directory and run the following commands:
```textmate
1. to install all packages 
  yarn OR npm install
2. to start you development environment
  yarn dev OR npm start 
  
http://localhost:8080
```
Otherwise, the app will start in production mode, and you can access it at this url http://localhost:8080




