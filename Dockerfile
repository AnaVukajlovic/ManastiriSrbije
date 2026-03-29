FROM php:8.2-cli

# Sistemske zavisnosti + PHP zip ekstenzija
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

# Front build
RUN npm install
RUN npm run build

# Start skripta
RUN chmod +x /app/start-render.sh

EXPOSE 10000

CMD sh -c "php artisan migrate --force && php artisan db:seed --class=MonasteriesCsvSeeder --force && php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=10000"