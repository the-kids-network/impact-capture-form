# Impact Capture Form

[![CircleCI](https://circleci.com/gh/the-kids-network/impact-capture-form.svg?style=svg)](https://circleci.com/gh/the-kids-network/impact-capture-form)

## Local Setup

### Setup Local Database
(Expects MySql 5.7 to be installed with the database created, OS X - simplest way is through Homebrew as follows)

Install mysql 5.7
```bash
brew install mysql@5.7
brew tap homebrew/services
```

Start/stop mysql service
```bash
brew services start mysql@5.7
brew services stop mysql@5.7
```

Create database and user for app
```bash
mysql -uroot
mysql> CREATE DATABASE homestead;
mysql> CREATE USER 'homestead'@'localhost' IDENTIFIED BY 'secret';
mysql> GRANT ALL PRIVILEGES ON homestead.* TO 'homestead'@'localhost';
```

Connect to database as user so that you can explore
```bash
mysql -uhomestead -psecret -Dhomestead
```

### Composer Setup

Open a terminal in the source root directory (this one) and run the following commands (taken from https://getcomposer.org/download/):

```bash
cp .env.example.postgres .env

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install
php artisan key:generate
php artisan config:cache
composer dump-autoload
#php artisan migrate
#php artisan db:seed
php artisan migrate --seed
php artisan migrate:refresh --seed // Will WIPE the database
php artisan serve
```

To rebuild less into css:
```
npm run dev
```

# Testing

Note that the app needs to be running before you launch tests.

To run tests: 

```
php composer.phar install
php artisan config:cache
php artisan migrate:refresh --seed && php artisan dusk
```

# Debug

I kept forgetting how to add debug lines. Start by adding the appropriate "use" line:
```
use Illuminate\Support\Facades\Log;
```
and then add the debug statement with something like
```
Log::info($message);
```