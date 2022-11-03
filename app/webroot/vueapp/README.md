

Once you have Docker running iPeer

For development environment, edit the core.php file by setting development to true

Note: To set development environment, scroll to the bottom of the page to make the change.
```textmate
app/config/core.php
  Configure::write('development', false); // default
```
Next in your terminal, CD to vueapp directory and run the following commands:
```textmate
1. to install all packages 
  yarn OR npm install
2. to start you development environment
  yarn dev OR npm start 
  
http://localhost:8080
```
Otherwise, the app will start in production mode on your machine, and you can access it at this url http://localhost:8080


### For deployment
```textmate
TBD
```


