# Mentor Session Application

[![CircleCI](https://circleci.com/gh/the-kids-network/impact-capture-form.svg?style=svg)](https://circleci.com/gh/the-kids-network/impact-capture-form)

## Local Setup

### Setup Local MySQL Database 8

Install the MySQL Docker container (only need to do this once):
```bash
docker run --name tkn-mysql -e MYSQL_ROOT_PASSWORD=my-secret-pw -d mysql:8.0.17
```

Start MySQL Docker container:
```bash
docker start tkn-mysql
```

Make sure it started OK:
```bash
docker logs tkn-mysql
```

Create database and user, by logging in as root user and executing the following SQL:
```bash
sudo docker exec -it tkn-mysql mysql -uroot -p
```

```sql
CREATE DATABASE homestead;
CREATE USER 'homestead'@'localhost' IDENTIFIED BY 'secret';
CREATE USER 'homestead'@'%' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON homestead.* TO 'homestead'@'localhost';
GRANT ALL PRIVILEGES ON homestead.* TO 'homestead'@'%';
```

Get IP address of the MySQL container:
```bash
docker container inspect tkn-mysql
```

Connect to the 'remote' containerised database from the local MySQL client:
```bash
mysql -h <ip_address_of_docker_container> -uhomestead -p homestead
```

### Install PHP 7.4

It's likely your OS may not have PHP installed, or it is the wrong version - so use phpbrew to install the right version of PHP.

Install phpbrew as per instructions here: https://phpbrew.github.io/phpbrew/ - specifically make sure the dependencies are installed e.g. bz2, libxml:

```bash
sudo apt install php build-essential libssl-dev \
    libxml2-dev libxslt-dev libsqlite3-dev \
    libreadline-dev libbz2-dev zlib1g-dev \
    libzip-dev libonig-dev pkg-config autoconf \
    libpng-dev libjpeg-dev 
```

Install the correct PHP version using phpbrew (NOTE: the listed extensions needs to be compiled with the PHP build)

```bash
phpbrew install 7.4.6 +default +mysql +gd -- --with-curl
phpbrew switch php-7.4.6
phpbrew extension (check required extensions enabled)
php -v
```

### Install Composer (need to do this once only if you don't already have it)

Open a terminal in the source root directory of this project and run the following commands (taken from https://getcomposer.org/download/):

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
```

### Install Node 14.3.0 (need to do this once only if you don't already have it)

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
nvm install 14.3.0
nvm alias default 14.3.0
node -v
```

### Install 'PHP' dependencies via Composer

To install the backend dependencies from composer.lock:

```bash
composer install
```

To update the composer.lock from composer.json (e.g. after updating dependency versions):

```bash
composer update
```

### Install 'Javascript' dependencies via npm

To install the dependencies from the package-lock.json file:

```bash
npm install
```

To update from the package.json file (e.g. after updating dependency versions):

```bash
npm update
```

### Configure local environment configuration

```bash
cp .env.example.mysql .env
```

There are a few things in here that can/should be configured for local development:

1. Database host and credentials
2. AWS credentials for development AWS resources
3. Test email sending: MAIL_TEST
4. S3 or local filesystem: FILESYSTEM_DRIVER

Speak to another dev for help getting this setup right.

### Setup database schema

Clear the config cache first to ensure the latest config is used to build the database:

```bash
php artisan config:cache
```

To run new/latest database migrations (i.e. since last one in the migrations table):

```bash
php artisan migrate
```

To run all migrations from beginning. This will rollback all previous migrations and is likely to incur data loss:

```bash
php artisan migrate:refresh
```

To apply seed data to the database:

```bash
php artisan db:seed
```

A handy all-in-one command to do all the above in one go:

```bash
php artisan config:cache && php artisan migrate:refresh --seed
```

If all else fails (e.g. some corrupt database state), then try the following which drops all tables and runs migrations from the beginning:

```bash
php artisan config:cache && php artisan migrate:fresh --seed
```

### Run application

```bash
php artisan key:generate
composer dump-autoload
npm run dev
php artisan config:cache
php artisan serve
```

To rerun webpack e.g. to regenerate less into css, or regenerate the app.js bundle:
```
npm run dev
```

## Testing

### Unit tests

Requires the local database to be seeded with data (see above).

To run the unit test:

```
./vendor/bin/phpunit
```

### Browser acceptance tests

Install right version of chrome driver if needed. Check your version here: https://www.whatismybrowser.com/detect/what-version-of-chrome-do-i-have and then run:

```bash
php artisan dusk:chrome-driver <version>
```

Turn off the php debug bar in the .env file, as it interferes with the browser tests.

For debugging in the browser, disable headless in the DuskTestCase.

To run the Dusk browser tests (note that the app needs to be running before you launch these tests): 

```
composer install
php artisan config:cache
php artisan migrate:refresh --seed && php artisan dusk
```

To run a specific test:

```
php artisan dusk tests/Browser/SessionSubmissionPageTest.php 
```


## Debugging

### Logging
Start by adding the appropriate "use" line:
```
use Illuminate\Support\Facades\Log;
```
and then add the debug statement with something like
```
Log::info($message);
```
The logs can be found in the storage/logs directory.

### Debug Bar
In the .env file set:

```
APP_DEBUG=true
```

In the browser, the debug bar button should display on the bottom left. 
There are lots of useful things in there to explore e.g. DB queries per page

### Dump Server
```bash
php artisan dump-server
```

Then in the code, use:

```php
dump($someVariable)
```