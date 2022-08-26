FROM php:7.2-apache

RUN a2enmod rewrite

# Install PHP extensions
# ref: https://stackoverflow.com/a/63579640/2496217
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions redis zip

#RUN apt-get update
#RUN apt install git -y

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
