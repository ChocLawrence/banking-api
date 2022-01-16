# Setting up project

1. Clone the repository into your computer
2. cd into the laravel lumen project
3. Edit .env file using contents of .env.example
4. composer install
5. composer update
6. php artisan serve
7. Dev enviroment will be located on http://localhost:8000
8. Create 3 roles with names "customer","admin","employee"  | Must be named exactly this
9. Create users with those roles.
10. Create 2 or more account types with names "savings","deposits", etc
11. Test



# Unit Tests

1. Migrate tables with php artisan migrate; 
2. Run /vendor/bin/phpunit

    Sample unit tests screenshot:
    ![alt text](./screenshots/unittest.png "Unit tests")

# Documentation
   Documentation was generated with scribe(https://scribe.knuckles.wtf/laravel/)
   Run php artisan scribe:generate after any change to routes/Controllers

   Documentation can be accessed at http://localhost:8000/docs


# Screenshots
    
1. Transfer:
    ![alt text](./screenshots/transfer.png "Transfer")

2. Account history:
    ![alt text](./screenshots/history.png "Account History")

2. Account balance:
    ![alt text](./screenshots/balance.png "Account Balance")

2. MySQL Table:
    ![alt text](./screenshots/mysql-table.png "MySQL Table")    



# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


