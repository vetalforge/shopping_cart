# Online Shopping Cart Example

This is a working example of a shopping cart for an online store built with Laravel. It covers the basic functions: creating a cart, adding products, updating the quantity, deleting products, and merging the cart to the user after authorization. There is support for guest carts and authorized users.
User identification is done using a cookie. The code has a separation of responsibilities: the main business logic in CartService, routes in the controller. The cart code is covered by tests.

**Potential application stability issues**

The cookie is used as a source of a token for the cart. If the cookie is changed/deleted, the user will receive a new cart without any notification.
Guest user carts do not have an "owner", so Laravel cannot automatically clean them after the browser automatically deletes the cookie. As a result, the carts table becomes cluttered over time. An alternative
is to keep the guest cart in Redis and create carts in the database only after the order is placed or authorized. You can also add a last_activity_at field to store the date of the last use of the cart and do a scheduled cleanup of old records.
There are no transactions for complex operations. If something goes wrong (e.g., connecting to the database), the system will remain in an inconsistent state.
When merging carts at login, the number of items may be lost. 

**Additional functionality**

You can add a button to clear the entire cart at once in your application and ensure that it works for all users (guests and authorized). This will improve the UX, allowing you to quickly “reset” the cart.
To do this, you need to perform the following steps:
1. Add a clear button on the frontend, which will trigger the corresponding DELETE request.
2. Create a route on the backend, for example, /cart/clear, which will be responsible for clearing all items from the user’s cart.
3. Create a method in the controller. This method should get the cart_token from a cookie or via user_id if the user is authorized, find the corresponding cart record, delete all related cart_items. The clear method should take into account both cases:
- If the user is authorized, work via user_id.
- If the guest, work via cart_token from a cookie.
4. Update the interface after clearing with the message “Cart cleared”.
5. Handle cases where the cart is not found or is already empty to avoid unnecessary requests or errors on the frontend.

## System requirements

- PHP >= 8.1
- MySQL or MariaDB
- Apache 2
- Composer

## Installation
### Windows

Clone git repo
<pre>git clone https://github.com/vetalforge/shopping_cart.git</pre>

Change directory 
<pre>cd shopping_cart</pre>

Install dependencies via Composer.
<pre>composer install</pre>

Create .env file from .env.example.
<pre>cp .env.example .env</pre>

Create .env.testing file from .env.example.testing
<pre>cp .env.testing.example .env.testing</pre>

Generate keys.
<pre>php artisan key:generate
php artisan key:generate --env=testing</pre>
   
Create main and test databases, name it **"laravel"** and **"test_db_laravel"**. After that create tables using
<pre>php artisan migrate</pre>

Seed products and users to the database
<pre>php artisan db:seed</pre>

#### Create virtual host in Apache 
Add this code to **apache\conf\httpd.conf**
```
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/shopping_cart"
    ServerName test.cart
</VirtualHost>
```
Add such a record in **etc\drivers\hosts** 
<pre>127.0.0.1 test.cart</pre>


***
### Linux

Clone git repo to /var/www.<br>
<pre>git clone https://github.com/vetalforge/shopping_cart.git</pre>

Change directory to /var/www/shopping_cart.
<pre>cd shopping_cart</pre>

Set permissions by 
<pre>sudo chmod -R 777 *</pre>

and define an owner by 
<pre>sudo chown -R $USER:$USER .</pre>
  
Install dependencies via Composer.
<pre>composer install</pre>
   
Create .env file from .env.example.
<pre>cp .env.example .env</pre>
   
Generate keys.
<pre>php artisan key:generate 
php artisan key:generate --env=testing</pre>

Create main and test databases, name it **"laravel"** and **"test_db_laravel"**. After that create tables using
<pre>php artisan migrate</pre>

Seed products and users to the database
<pre>php artisan db:seed</pre>

#### Create virtual host in Apache 
Rename **/var/www/shopping_cart** to **/var/www/test.cart**

Add such a record in **/etc/hosts**
<pre>127.0.0.1 test.cart</pre>
          
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
<pre>sudo a2ensite test.cart.conf</pre>

<pre>sudo systemctl restart apache2</pre>


After that check http://test.cart
