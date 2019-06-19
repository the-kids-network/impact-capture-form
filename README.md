# Mentor Session Application

[![CircleCI](https://circleci.com/gh/the-kids-network/impact-capture-form.svg?style=svg)](https://circleci.com/gh/the-kids-network/impact-capture-form)

## Local Setup

### Setup Local MySQL Database

(Expects mySQL 5.7 to be installed with the database created.
On OS X - simplest way is through Homebrew as follows)

Install mySQL 5.7:
```bash
brew install mysql@5.7
brew link mysql@5.7 --force
brew tap homebrew/services
```

Start/stop mySQL service:
```bash
brew services start mysql@5.7
brew services stop mysql@5.7
```

Ideally, you might want to create a mySQL docker container instead of installing via homebrew.

Create database and user for app:
```bash
mysql -uroot
mysql> CREATE DATABASE homestead;
mysql> CREATE USER 'homestead'@'localhost' IDENTIFIED BY 'secret';
mysql> GRANT ALL PRIVILEGES ON homestead.* TO 'homestead'@'localhost';
```

Connect to database as user so that you can explore:
```bash
mysql -uhomestead -psecret -Dhomestead
```

### Install PHP 7.2

It's likely you OS may not have PHP installed, or it is the wrong version. So use homebrew (or equivalent) to install the right version of PHP and make sure it is activated e.g. on the path.

```bash
brew install php72
brew link php72 --force
```

### Install Composer (need to do this once only if you don't already have it)

Open a terminal in the source root directory of this project and run the following commands (taken from https://getcomposer.org/download/):

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
```

### Install Node  (need to do this once only if you don't already have it)

```bash
brew install node
```

### Install 'PHP' dependencies via composer

To install the backend dependencies (this will create a composer.lock file to fix versions - this should be checked into source control):

```bash
composer install
```

To update the composer.lock (e.g. after updating dependencies in composer.json):

```bash
composer update
```

### Install 'Javascript' dependencies via npm

The following will install the dependencies from the package.json file, creating a package-lock.json file to fix the versions - this should be checked into source control.

```bash
npm install
```

Run npm update to update dependencies e.g. if a version is changed, or a new dependency added.

```bash
npm update
```

### Configure local environment configuration

```bash
cp .env.example.mysql .env
```

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
php artisan dusk:chrome-driver 74
```

Turn off the php debug bar in the .env file, as it interferes with the browser tests.

For debugging in the browser, disable headless in the DuskTestCase.

To run the Dusk browser tests (note that the app needs to be running before you launch these tests): 

```
composer install
php artisan config:cache
php artisan migrate:refresh --seed && php artisan dusk
```

## Debugging

I kept forgetting how to add debug lines. Start by adding the appropriate "use" line:
```
use Illuminate\Support\Facades\Log;
```
and then add the debug statement with something like
```
Log::info($message);
```