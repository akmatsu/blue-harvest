# Blue Harvest

Blue Harvest is a powerful image hosting repository developed by the Web Team at the [Matanuska-Susitna Borough](https://matsu.gov). Built with Laravel and Vue.js through InertiaJS, it offers functionality similar to Unsplash, providing a robust platform for image storage and management.

## Requirements

- PHP 8.x
- Node.js (Latest LTS)
- Composer
- Laravel
- MySQL 8.0
- Typesense Server
- [PHP Modules](#required-php-modules)
- [Blue Harvest Clip API](https://github.com/akmatsu/clip_api) (for AI features)

- IDE with support for:
  - Prettier
  - ESLint

> All components must be properly configured with appropriate environment variables in the `.env` file. Follow the [local development setup](#local-development-setup) instructions to configure your environment.

## Local Development Setup

### Install [NodeJS](https://nodejs.org/)

NodeJS is required to bundle the application frontend.

### Configure Environment Variables

Create your environment configuration file:

```bash
cp .env.example .env
```

> **Important**: The application requires proper environment configuration to run. After creating your `.env` file, configure the required values following the documentation or consult with a team member for sensitive information.

### Install [PHP](https://www.php.net/), [Composer](https://getcomposer.org/), and [Laravel](https://laravel.com/).

PHP, Composer, and Laravel are all required to run the application.

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

Blue Harvest requires MySQL. Follow these steps to configure your database:

1. Install MySQL from the [official website](https://dev.mysql.com/doc/mysql-installation-excerpt/8.0/en/). Use the latest LTS version (currently 8.0).

2. Create the database and user:

```bash
# Login to MySQL
mysql -u root -p

# Create database and user
CREATE DATABASE blue_harvest;
CREATE USER 'blueharvest_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON blue_harvest.* TO 'blueharvest_user'@'localhost';
FLUSH PRIVILEGES;
exit
```

3. Update your `.env` file with the database credentials

4. Run migrations:

```bash
php artisan migrate
```

### Setup your App key

> The App key is a random string used by Laravel for encryption and security. It's required for secure session management and other encrypted data.

```bash
php artisan key:generate
```

If your `.env` doesn't update automatically, copy the output and paste it into `.env` manually.

### Setup Reverb

> Notifications require Reverb to work. Not required for local development.

```bash
php artisan install:broadcasting
```

### Setup Your IDE

This application uses [Prettier](https://prettier.io/) for code formatting and [ESLint](https://eslint.org/) for JavaScript/TypeScript linting. Configure your IDE to support these tools.

### Setup the [Blue Harvest Clip API](https://github.com/akmatsu/clip_api)

AI image processing features require the Clip API. Auto tagging and content flagging will be disabled if the API is not configured.

### Start the development server

> Run each command in a separate terminal window. See the [commands section](#commands) for details.

```bash
npm run dev
php artisan serve
php artisan queue:work
php artisan queue:work --queue=scout
php artisan queue:work --queue=processImages
php artisan reverb:start
```

## Commands

### Development Server

Run these commands in separate terminal windows:

```bash
# Frontend compilation
npm run dev

# Laravel development server (localhost:8000)
php artisan serve

# Queue workers
php artisan queue:work                         # Default queue (notifications)
php artisan queue:work --queue=scout          # Search indexing
php artisan queue:work --queue=processImages  # Image processing

# WebSocket server for real-time events
php artisan reverb:start
```

### Setup Commands

```bash
# Install dependencies
composer install
npm install

# Database setup
php artisan migrate
php artisan db:seed
```

> Note: Queue workers handle background tasks like notifications, search indexing, and image processing asynchronously. WebSocket server (Reverb) enables real-time notifications.

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
