# Blue Harvest

Blue Harvest is an image hosting repository similar to Unsplash it's created by the Web Team at the [Matanuska-Susitna Borough](https://matsu.gov). It's built using Laravel with Vue via InertiaJS.

## Local Development Setup

### Install [NodeJS](https://nodejs.org/)

NodeJS is required to bundle the application frontend.

### Create your `.env` file

> You must configure your `.env` file or the application will not start Follow the rest of the steps then reach out to a team member for any missing values.

```bash
cp .env.example .env
```

### Install [PHP](https://www.php.net/), [Composer](https://getcomposer.org/), and [Laravel](https://laravel.com/).

PHP, Composer, and Laravel are all required to run the application.

### Install the [required PHP modules](#required-php-modules)

### Install Required PHP Modules

> The application requires specific PHP modules to function properly. Installation steps vary by operating system:

**Ubuntu/Debian:**

```bash
sudo apt update
sudo apt install php-bcmath php-curl php-gd php-imagick php-redis php-xml php-zip
# etc...
```

**MacOS (using Homebrew):**

```bash
brew install php
brew install imagemagick
pecl install imagick redis
# etc...
```

**Windows:**

- Enable required extensions in your `php.ini` file
- Use [XAMPP](https://www.apachefriends.org/) or [Laravel Homestead](https://laravel.com/docs/homestead) for a pre-configured environment

See the [complete list of required modules](#required-php-modules) below.

### Setup Typesense

Typesense powers the search functionality in Blue Harvest. Follow these steps to set it up:

1. Install Typesense by following their [Installation Guide](https://typesense.org/docs/guide/install-typesense.html#option-2-local-machine-self-hosting)
2. Configure your `.env` file with the appropriate Typesense connection values
3. Start the Typesense server

Without proper Typesense configuration, the search features will not work.

### Setup the Database

This application uses MySQL as the database. You must have a MySQL server running to use the application. To install MySQL, follow the instructions on the [MySQL website](https://dev.mysql.com/doc/mysql-installation-excerpt/8.0/en/). Double check the version, we will always try to using the latest LTS. As of this writing the latest LTS is 8.0.

Once MySQL is installed you will need to create a user and database for the application:

#### **Login to MySQL**

```bash
mysql -u root -p
```

#### **Create the database**

```mysql
CREATE DATABASE blue_harvest;
```

#### **Create the user**

```mysql
CREATE USER 'blueharvest_user'@'localhost' IDENTIFIED BY 'password';
```

#### **Grant the user access to the database**

```mysql
GRANT ALL PRIVILEGES ON blue_harvest.* TO 'blueharvest_user'@'localhost';
```

#### **Flush privileges**

```mysql
FLUSH PRIVILEGES;
```

#### **Exit MySQL**

```mysql
exit
```

#### **Update your `.env` file**

After following these steps be sure to update your `.env` file with the appropriate database connection values.

#### **Run the migrations**

```bash
php artisan migrate
```

### Setup your App key

```bash
php artisan key:generate
```

If for some reason your `.env` doesn't update automatically you can copy the output and paste it into `.env` manually.

### Setup the [Blue Harvest Clip API](https://github.com/akmatsu/clip_api)

AI image processing features (auto tagging and content flagging) require the Clip API to be set up. These features will be disabled if the API is not configured.

### Setup Reverb

> Not required for local development, but notifications will not work if you do not enable reverb.

```bash
php artisan install:broadcasting
```

### Setup Your IDE

This application uses [Prettier](https://prettier.io/) for automatic code formatting and (ESLint)[https://eslint.org/] for JavaScript/TypeScript linting. Ensure your IDE is setup to support these tools.

### Start the development server

> Please note each of the commands will need to be run in their own terminal window. For more details about each command see the [commands section](#commands).

```bash
npm run dev
php artisan serve
php artisan queue:work
php artisan queue:work --queue=scout
php artisan queue:work --queue=processImages
php artisan reverb:start
```

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
