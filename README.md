## About
<h2>Step 1: download in your system.</h2>  
    
        git clone  
    
<h2>Step 2: Configure your database from .env file</h2> 
    
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=
        DB_USERNAME=root
        DB_PASSWORD=
    
<h2>Step 3: Migrate</h2> 

        php artisan migrate
    
<h2>Step 4: Install composer</h2> 

        composer install
    
<h2>Step 5: key generate</h2> 

        php artisan key:generate
    
<h2>Step 6: Run server</h2>  

        php artisan serve
    
        http://127.0.0.1:8000/dashboard
        http://127.0.0.1:8000/login
        http://127.0.0.1:8000/register 
 
