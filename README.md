<img src="./public/logo.png" alt="Logo of the project" align="right">

# Interview Platform &middot; [![php](https://img.shields.io/badge/php-v8.*-blue)](https://www.php.net/) [![laravel](https://img.shields.io/badge/laravel-v8.*-blue)](https://laravel.com/docs/8.x) [![mysql](https://img.shields.io/badge/mysql-v8.*-blue)](https://www.mysql.com/) [![npm](https://img.shields.io/badge/npm-v8.*-green)](https://www.npmjs.com/package/npm) [![composer](https://img.shields.io/badge/composer-v2.*-green)](https://getcomposer.org/) [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/your/your-project/blob/master/LICENSE)

A website that supports both candidates and businesses in the recruitment process. The website provides recruitment articles as well as articles to share recruitment experiences, and will also help manage interviews or practice for candidates. In addition, it also helps to manage projects or processes, job postings in business projects as well as registered candidates for recruitment.

## Requirements

- First, install Composer for your operating system from this link https://getcomposer.org/download/
- Second install NPM and Node JS 8.* from https://www.npmjs.com/get-npm
- Third you have to create an account Daily.co https://dashboard.daily.co/ to get an API key and get App Password for sending email notifications
- (Optional) install MySQL 8.*, PHP 8.*, Laravel Framework 8.*  in your system to run the project

## Installing 

### Getting started

1. Clone this project from Github
    ```sh
    git clone https://github.com/michiokazuo/interview-platform.git
    ```

2. Install Laravel dependencies
    ```sh
    composer install
    ```

3. Install NPM packages
   ```sh
   npm install
   ```

4. Config .env variables like .env.example with your environment
    - MySQL database 
    - Mail information 
    - Daily.co API key

### Built Project

When you finish the previous step, you should create tables in your database and create an admin account for the system
```sh
php artisan migrate
php artisan db:seed
```

After that, you can build a project with commands
   ```sh
   npm run watch
   php artisan serve
   ```
And create a queue for jobs to crawl data from Stack Overflow https://stackoverflow.com/
```sh
php artisan queue:work
```

Finally, you can connect with the system from http://127.0.0.1:8000 and use the app's features 

### Command
Admin can run cmd on the server to crawl data do not use UI with commands
```sh
php artisan crawl:data {--count=} {--rollback=} {--tag=} {--tag-rollback=}
```
with arguments as:
  - count: numbers of questions will be got
  - rollback: crawl all again to update information
  - tag: you can crawl with a specific tag what you want
  - tag-rollback: crawl only questions of tag again

## Getting help

Phong DX - [@MichioKazuo](https://twitter.com/MichioKazuo) - phong.dx183966@sis.hust.edu.vn

Project Link: [Interview Platform](https://github.com/michiokazuo/interview-platform.git)

