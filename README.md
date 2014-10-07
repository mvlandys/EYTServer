
## EYT Server – Heroku Setup

### Installation on Mac OS X

## 1. Prerequisites

### GIT

Download & install GIT from:

[http://git-scm.com/download/mac](http://git-scm.com/download/mac)

### Heroku Toolbelt

Download & install the Heroku toolbelt from:

[https://toolbelt.heroku.com](https://toolbelt.heroku.com)

## 1. Heroku Setup

1. Sign up to Heroku (http://www.heroku.com)
2. Create a new app
  1. Within the dashboard click on the plus + button in the top right
  2. Enter in an App Name (take note)
  3. Select a region
  4. Click on the Create App button

3. Create a new database
  1. From the dashboard select Databases from the top menu
  2. Click on the Create Database button
  3. Select a plan
    - \*\*Dev Plan is free
  4. Click on the Add Database button

4. Click on the newly created database entry
5. Take note of the following details:
  1. Host
  2. Database
  3. User
  4. Password



## 1. Download & Configure the EYT Server

1. Open the Terminal app
2. Download the EYT Server source code using GIT
```
git clone https://github.com/mvlandys/EYTServer.git
```
3. CD into the source code folder 
```
cd EYTServer
```
4. Open the database config file
```
open app/config/database.php
```
5. Find the line 'default' => 'sqlite',
  - Change 'sqlite' to 'pgsql'
6. Find the line 'pgsql' => array(
7. Fill in the following entries with the values noted in the previous step:
  - Host, Database, Username, Password
8. Save and close the database.php file
9. Enter the following command:
```
git remote add heroku git@heroku.com:AppName.git
```
  - Replace AppName with your Heroku app name as configured in Step 2.2.b


10. Enter the following command:
```
git push heroku master
```


## 1. Test your EYT Server

1. Open your internet browser
2. Go to http://AppName.herokuapp.com/
  - Replace AppName with your Heroku app name as configured in Step 2.2.b

3. Login with the default credentials:
  - Username: admin
  - Password: admin



## 1. Configure the EYT apps

1. Open the settings screen of the EYT app
  - Click on the (i) button in the bottom right corner
2. Enter the following for the Database URL
  - AppName.herokuapp.com
  - Replace AppName with your Heroku app name as configured in Step 2.2.b
