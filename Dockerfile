FROM circleci/php:7.1-browsers
WORKDIR /home/circleci/tkn

COPY --chown=circleci . .

USER root

RUN chown circleci .

USER circleci

RUN cp .env.example.postgres .env

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN php composer.phar install
RUN php artisan key:generate
RUN php artisan config:cache
RUN composer dump-autoload
#php artisan migrate
#php artisan db:seed
RUN php artisan migrate:refresh --seed
RUN php artisan serve