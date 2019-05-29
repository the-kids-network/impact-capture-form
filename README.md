# Impact Capture Form

[![CircleCI](https://circleci.com/gh/the-kids-network/impact-capture-form.svg?style=svg)](https://circleci.com/gh/the-kids-network/impact-capture-form)

## Local Setup

### Setup Local Database
(Expects MySql 5.7 to be installed with the database created.
On OS X - simplest way is through Homebrew as follows)

Install mysql 5.7:
```bash
brew install mysql@5.7
brew tap homebrew/services
```

Start/stop mysql service:
```bash
brew services start mysql@5.7
brew services stop mysql@5.7
```

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

brew install php72

### Install Composer

Open a terminal in the source root directory of this project and run the following commands (taken from https://getcomposer.org/download/):

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install
```

To update composer.lock (e.g. after updating dependencies in composer.json):

```bash
php composer.phar update
```

### Configure local environment configuration

```bash
cp .env.example.mysql .env
```

### Setup database

Clear the config cache first if any problems:

```bash
php artisan config:cache
```

To run new database migrations:

```bash
php artisan migrate
```

To run all migrations from beginning. This will will WIPE the database:

```bash
php artisan migrate:refresh
```

To apply seed data to the database:

```bash
php artisan db:seed
```

A handy all-in-one command:

```bash
php artisan config:cache && php artisan migrate:refresh --seed
```

### Run application
```bash
php artisan key:generate
php composer.phar dump-autoload
php artisan config:cache
php artisan serve
```

To rebuild less into css:
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
php composer.phar install
php artisan config:cache
php artisan migrate:refresh --seed
php artisan dusk
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