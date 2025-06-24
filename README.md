# Online Shopping Cart Example

This is a working example of a shopping cart for an online store built with Laravel. 

**System requirements**
- PHP >= 8.1
- MySQL or MariaDB
- Apache 2
- Composer

## Installation
### Windows

Clone git repo
>$ git clone https://github.com/vetalforge/shopping_cart.git

Change directory 
>$ cd shopping_cart

Install dependencies via Composer.
>$ composer install

Create .env file from .env.example.
>$ cp .env.example .env

Create .env.testing file from .env.example.testing
>$ cp .env.testing.example .env.testing

Generate a key.
>$ php artisan key:generate
   
Create two database, name it **"laravel"** and **"test_db_laravel"**. After that create tables using
>$ php artisan migrate

Seed products and users to the database
>$ php artisan db:seed

#### Create virtual host in Apache 
Add this code to **apache\conf\httpd.conf**
```
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/shopping_cart"
    ServerName test.cart
</VirtualHost>
```
Add such a record in **etc\drivers\hosts** 
>127.0.0.1 test.cart




***
### Linux

Clone git repo to /var/www.<br>
>$ git clone https://github.com/vetalforge/shopping_cart.git
>
Change directory to /var/www/shopping_cart.
>$ cd shopping_cart
>
Set permissions by 
>$ sudo chmod -R 777 * 
>
and define an owner by 
>$ sudo chown -R $USER:$USER .
  
Install dependencies via Composer.
>$ composer install
   
Create .env file from .env.example.
>$ cp .env.example .env
   
Generate a key.
>$ php artisan key:generate
   
#### Create virtual host in Apache 
Rename **/var/www/shopping_cart** to **/var/www/test.cart**

Add such a record in **/etc/hosts**
>127.0.0.1 test.cart
          
Create a configuration file in **/etc/apache2/sites-available/** and name it as **test.cart.conf**
           
Insert into it this code
```
<VirtualHost *:80>
    ServerAdmin admin@test.com
    ServerName test.cart
    DocumentRoot /var/www/test.cart/public
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    <Directory /var/www/test.cart/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
   </Directory>
</VirtualHost>
```
   
Run these commands
>$ sudo a2ensite test.cart.conf

>$ sudo systemctl restart apache2


After that check http://test.cart

Create two database, name it **"laravel"** and **"test_db_laravel"**. After that create tables using
>$ php artisan migrate

Seed products and users to the database
>$ php artisan db:seed
