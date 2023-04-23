## Salesman API (Laravel APP)
### 1.) Clone repository
### 2.) Install Sail
#### a) composer require laravel/sail --dev
#### b) php artisan sail:install
#### c) ./vendor/bin/sail up or sail up -d
#### d) php artisan sail:add (If you would like to add an additional service to your existing Sail installation, you may run the sail:add)
#### e) run: ./vendor/bin/sail artisan migrate
#### f) run: ./vendor/bin/sail artisan db:seed
#### g) run: ./vendor/bin/sail artisan db:seed

## Route List
#### GET|HEAD  http://localhost/api/codelists
#### POST      http://localhost/api/login
#### GET|HEAD  http://localhost/api/salesman
#### POST      http://localhost/api/salesman
#### PUT       http://localhost/api/salesman
#### DELETE    http://localhost/api/salesman

### Use API
The first step is to log in the user. There are two users in the system with different abilities. John Doe has access to all API operations, so far what John Wick just readonly. first you need to call the login path with credentials.
John Doe login: johndoe@example.com password: password
John Wick login: johnwick@example.com password: password
After call login you can get a new token for another API operations. Token is well set as bearer. At this moment you can try all api calls.

