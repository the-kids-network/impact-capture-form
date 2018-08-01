# Impact Capture Form

[![CircleCI](https://circleci.com/gh/the-kids-network/impact-capture-form.svg?style=svg)](https://circleci.com/gh/the-kids-network/impact-capture-form)

## Local Setup

### Setup Local Database
(Expects postgres to already be installed)
```bash
/usr/local/bin/pg_ctl -D /usr/local/var/postgres start
/usr/local/bin/createuser -P homestead # Set password to 'homestead'
/usr/local/bin/createdb homestead
```

Stop it again with
```/usr/local/bin/pg_ctl -D /usr/local/var/postgres stop```

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
php artisan migrate:refresh --seed
php artisan serve
```

# Testing

composer global require phpunit/phpunit

https://codeception.com/for/laravel
