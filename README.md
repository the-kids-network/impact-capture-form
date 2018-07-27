# Impact Capture Form

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
mv .env.example .env
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install
php artisan config:cache
php artisan key:generate
composer dump-autoload
php artisan migrate
php artisan db:seed
php artisan serve
```



