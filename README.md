# social-login

> Implementation of social login using github. Solution for this hackathon: https://nuwe.io/dev/competitions/job-barcelona-23/jobarcelona-23-backend


## API
Initial route:
```
http://127.0.0.1:8000/api/
```
Login route:
```
http://127.0.0.1:8000/api/login/github
```
Show all registered users:
```
http://127.0.0.1:8000/api/users
```
Star a repo
```
http://127.0.0.1:8000/api/star-repo/{owner-username}/{repo}
```

## Installation

1. Clone the repo to your computer
```
git clone https://github.com/jordijs/social-login.git
```
2. On your terminal, navigate to the folder location
```
cd social-login
```
3. Run composer install. (If you don't have composer on your computer, install it: https://getcomposer.org/download/)
```
composer install
```
4. Create a MySQL database on your computer
5. Configure the .env file of your project for your system to match the database. Fields that you must match:
```
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```
7. Migrate the database by typing on the terminal:
```
php artisan migrate
```
8. Run the server: 
```
php artisan serve
```

## License 

[MIT](https://opensource.org/licenses/MIT)
