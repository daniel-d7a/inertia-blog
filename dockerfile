FROM php:8.4-apache

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

EXPOSE 8000

RUN groupadd -g 1000 appuser && useradd -u 1000 -g appuser -m appuser

USER appuser

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]