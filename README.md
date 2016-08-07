# insta
Mini Project that behaves like Instagram

Synopsis
==============
Complete web project that allows users to share pictures. 
Very simple UI which allows to upload files up to 2MB and upto 1920x1080 image size. 
Supports the JPEG, PNG and GIF image format only.
You can download CSV file with information of all uploaded pictures using 'Export as CSV' button.

Motivation
==============
Project was created as test issue for hiring process

Installation
==============
Requirements:
--------------
- PHP7
- Apache/2.4.18 with web host configured
- Installed composer (see https://getcomposer.org/)
- Installed bower (see https://bower.io/)
- MongoDB server/3.2.8 (see https://www.mongodb.com/download-center)
- MongoDB driver for PHP (see http://php.net/manual/ru/mongodb.installation.php)

How to run application
--------------
- download it in the root of the web host

	```
    - git clone git@github.com:JuliaLatanskaya/insta.git
    ```
    
- Run MongoDB server

    ```
	- mongod
    ```
    
- Install dependencies

    ```
	- composer update
	- bower install
    ```
