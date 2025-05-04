# Etapa 1: Instalar dependencias con Composer
FROM composer:2.6 AS composerstep
WORKDIR /app
COPY ./ /app
RUN composer install --no-dev --ignore-platform-reqs

# Etapa 3: Crear imagen para producción
FROM php:8.2-apache

#Install php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && install-php-extensions pdo pdo_pgsql imagick gd bcmath redis zip

# Copiar el código fuente de la aplicación
COPY ./ /var/www/html

# Copiar las dependencias resueltas por composer
COPY --from=composerstep /app/vendor /var/www/html/vendor

# Copiar php.ini personalizado
ADD docker/php.ini /usr/local/etc/php/php.ini

# Copiar un virtual host personalizado si es necesario
ADD docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Activar modulo que permite leer htaccess y hacer redireccionamientos
RUN a2enmod rewrite

RUN cp .env.example .env

# Establecer permisos adecuados para el servidor web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

USER www-data