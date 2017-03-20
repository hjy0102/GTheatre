# GTheatre
UBC CPSC 304 - movie ticket reservation web application

![Alt text](./screenshot.png?raw=true "Welcome to GTheatre")</br>

[Update - Mar 20, 2017 12:22AM] The front-end template will be marketing.css available on our latest commit 146b3b1</br>
[Update - Feb 17, 2017 10:14PM] We are not hosting our database remotely on ClearDB <br/>
[Update - Feb 17, 2017 12:38AM] We are now hosted on: https://gtheatre304.herokuapp.com

## Requirements
PHP >=7.0.0 [https://hjy0102.wordpress.com/2017/02/28/updating-to-php7-from-php5/] </br>
Composer >=1.3.2 [www.getcomposer.org/]</br>
(Optional to run locally) XAMPP [https://www.apachefriends.org/index.html]

## To Run/ Starting
In Terminal, make sure composer is installed.

Run 
```bash
composer update
```
This will update your dependencies.</br>
```bash
cd public
php -S localhost:3000
```
Change your directory to GTheatre/public and run the -S start command to run locally on port 3000. (Note you can change the port number if you want to if you have something already running on port 3000.)</br>

In the web browser of your choice go to:
```bash
localhost:3000
```
## Troubleshoot
1. If you get the error stating that you are missing "...autoload.php" in index.php line 5, make sure you pull the vendor packages in the branch "vendor" [https://github.com/hjy0102/GTheatre/commit/5742fe6af2734fc7a9b05153d94ee84a3e44895b]</br>
You can also just download it online.
2. we are using bootstrap as well as font-awesome css and jquery; these files should already be included in the root directories but if for some reason you get an error from these pathways, you can also download these online [http://fontawesome.io] [http://getbootstrap.com] [http://jquery.com]
3. To run <b> locally </b> make sure you <b>comment out </b>
```php
$this->dbProvider = new mysqli($server, $username, $password, $db);
```
and <b>uncomment out </b>the following line
```php 
$this->dbProvider = new mysqli("localhost", "root","","GTheatre");
```
in src/Database/dbConnect.class.php
