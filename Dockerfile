FROM php:8.1-fpm

# Установка расширений PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    curl \
    && docker-php-ext-install pdo_pgsql

# Копируем код
COPY . /var/www/html

# Устанавливаем права
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 9000