FROM php:7.4-apache

# Системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    curl \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        intl \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Включаем mod_rewrite (нужно для Yii2)
RUN a2enmod rewrite

# Разрешаем .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/html

# Копируем проект
COPY . .

# Скрипт запуска приложения
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Установка зависимостей
RUN composer install --no-dev --optimize-autoloader

# Права для Yii2
RUN mkdir -p runtime web/assets \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 runtime web/assets

# DocumentRoot → web (Yii2)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/web|g' /etc/apache2/sites-available/000-default.conf

# Порт
EXPOSE 80

# Запуск Apache после миграций
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
