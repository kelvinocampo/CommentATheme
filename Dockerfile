# Imagen base: PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar tu código al contenedor
COPY . /var/www/html

# Dar permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Habilitar mod_rewrite si usas URLs amigables
RUN a2enmod rewrite

# Exponer puerto 80
EXPOSE 80
