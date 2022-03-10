FROM php:8.0

RUN apt-get update \
        && apt-get install -y \
            g++ \
            libicu-dev \
            libpq-dev \
            libzip-dev \
            zip \
            zlib1g-dev \
        && docker-php-ext-install \
            intl \
            opcache \
            pdo

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get upgrade -y

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#WORKDIR /app/public

CMD php -S localhost:8000 -t public