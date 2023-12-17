# quizhaat-backend-api
All API endpoints for Quiz Management system developed in Laravel
After clone the project make a database and set the name into .env file DB_DATABASE
run `php artisan migrate`
Then register with `name`, `phone`, `email`, `password` and `c_password` "c_password" is the same as password which is to confirm the password
Please make sure the password is 8 character long
Note: please make sure in the php.ini file inside the php folder in your local the `extension=sodium` extension is on
After registration there will be a token in the response, you have to set the token as bearer token so test in postman to hit the details API, details api is just for a test porpuse to check if the token is working or not 
