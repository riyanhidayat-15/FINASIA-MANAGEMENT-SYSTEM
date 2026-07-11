FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl zip pdo_mysql bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install

RUN npm run build

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=$PORT