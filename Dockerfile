FROM php:8.0-apache
RUN export DOCKER_DEFAULT_PLATFORM=linux/amd64
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
RUN apt-get install -y cron
RUN a2enmod rewrite