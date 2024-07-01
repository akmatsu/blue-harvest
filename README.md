# Blue Harvest

Blue Harvest is an image hosting repository similar to Unsplash it's created by the Web Team at the [Matanuska-Susitna Borough](https://matsugov.us). It's built using Laravel with Vue via InertiaJS.

## Local development

### Install [NodeJS](https://nodejs.org/)

NodeJS is required to run the application frontend.

### Install [PHP](https://www.php.net/), [Composer](https://getcomposer.org/), and [Laravel](https://laravel.com/).

PHP, Composer, and laravel are all required to run the application.

### Install the [required PHP modules](#required-php-modules)

The application will not work if you do not have the required PHP modules installed on your machine. The process is slightly different depending on your OS.

### Setup the [Blue Harvest Clip API](https://github.com/akmatsu/clip_api)

The CLIP api is required in order to handle image processing like automatic tagging and automatic flagging.

### Setup Your IDE

This application uses [Prettier](https://prettier.io/) for automatic code formatting and (ESLint)[https://eslint.org/] for JavaScript/TypeScript linting. Ensure your IDE is setup to support these tools.

## Commands

### Dependency Management

#### **Install Composer dependencies**

```
composer install
```

#### **Install NPM dependencies**

> As mentioned previously node is required to run the frontend.

```bash
npm i
```

### Database management

#### **Migrate the database**

```bash
php artisan migrate
```

#### **Seed the database**

```bash
php artisan db:seed
```

### Running the dev server

#### **Start the Vite Server to compile your frontend code in dev mode**

```
npm run dev
```

#### **Run the development server. This will make your app available at localhost:8000**

```
php artisan serve
```

### Queues

> Queues handle background processes that might happen asynchronously or could take a very long time. They allow the application to perform background tasks without blocking users. We intentionally run a couple of different queue workers to prevent heavier tasks, like search indexing and image processing, from prevent smaller tasks like notifications for running.

#### **Start your default queue worker**

> This is necessary for basic queued tasks to run, like notifications.

```
php artisan queue:work
```

#### **Start your Scout queue worker**

> This is necessary for image search indexing to work.

```
php artisan queue:work --queue=scout
```

#### **Start the processImages queue worker**

> This handles image processes like auto tagging and flagging.

```
php artisan queue:work --queue=processImages
```

### Broadcast

> We use [Laravel Reverb](https://laravel.com/docs/11.x/reverb#main-content) to handle [broadcasting](https://laravel.com/docs/11.x/broadcasting#main-content). This is necessary to get real time events notifications working in the frontend.

#### **Start reverb:**

```
php artisan reverb:start
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
