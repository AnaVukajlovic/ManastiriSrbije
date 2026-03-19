FROM php:8.2-cli

# Sistemске зависности + PHP zip ekstenzija
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    curl \
    nodejs \
    npm \
 && docker-php-ext-install zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Kopiraj projekat
COPY . .

# Dozvole za Laravel foldere
RUN chmod -R 775 storage bootstrap/cache

# PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Vite build za CSS/JS
RUN npm install
RUN npm run build

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000