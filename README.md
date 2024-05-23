# Blue Harvest

Blue Harvest is a self-hostable image hosting repository similar to Unsplash it's created by the Web Team at the Matanuska-Susitna Borough. It's built using Laravel with Vue via InertiaJS.

## How to set up for local development.

1.  Install [NodeJS](https://nodejs.org/en)
2.  Install [PHP](https://www.php.net/), [Composer](https://getcomposer.org/), and [Laravel](https://laravel.com/).
3.  Install [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/) - This is required to run typesense.
4.  Make sure you have the the [required PHP modules](#required-php-modules) installed on your machine:
5.  Install composer dependencies:
    ```bash
    composer install
    ```
6.  Install npm dependencies:
    ```bash
    npm i
    ```
7.  Run migrations
    ```bash
    php artisan migrate
    ```
8.  Seed the database
    ```bash
    php artisan db:seed
    ```
9.  Start the laravel server
    ```bash
    php artisan serve
    ```
10. Compile the frontend code

    **With hot reload**

    ```bash
    npm run dev
    ```

    **Without hot reload** (Requires you to run build again to see UI changes)

    > I wouldn't recommend this for development. This is intended for production environments.

    ```
    npm run build
    ```

## Self-Hosting

1. The entire project is containerized, allowing you to self-host it on your own server using Docker Compose. Follow these steps:
   ```bash
   docker-compose up -d --build
   ```

## Required PHP Modules

- bcmath
- calendar
- Core
- ctype
- curl
- date
- dom
- exif
- FFI
- fileinfo
- filter
- ftp
- gd
- gettext
- hash
- iconv
- igbinary
- imagick
- json
- libxml
- mbstring
- mysqli
- mysqlnd
- openssl
- pcntl
- pcre
- PDO
- pdo_mysql
- pdo_pgsql
- pdo_sqlite
- pgsql
- Phar
- posix
- random
- readline
- redis
- Reflection
- session
- shmop
- SimpleXML
- sockets
- sodium
- SPL
- sqlite3
- standard
- sysvmsg
- sysvsem
- sysvshm
- tokenizer
- xml
- xmlreader
- xmlwriter
- xsl
- Zend OPcache
- zip
- zlib
