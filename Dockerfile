# --- Etapa 1: instalar dependencias de PHP con Composer ---
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# --- Etapa 2: imagen de ejecución ---
FROM php:8.4-apache

# Extensión PDO para MySQL y módulo de reescritura de URLs (front controller).
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

WORKDIR /var/www/html

# Dependencias ya resueltas (sin Composer en la imagen final).
COPY --from=vendor /app/vendor /var/www/html/vendor

# Código de la aplicación.
COPY . /var/www/html

# Apache apunta directamente a public/ (documentación de Laravel).
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Permisos de escritura para storage y caché de Laravel.
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache

# Preparación en tiempo de arranque (clave de aplicación y cachés).
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
