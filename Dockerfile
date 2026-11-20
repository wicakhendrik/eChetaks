# E - Chetak's  (PHP + Apache)
# Build:  docker build -t echetaks .
FROM php:8.3-apache

# gd (+freetype/jpeg) dipakai captcha admin, pdo_mysql dipakai semua halaman
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpng-dev libjpeg-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" gd pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-chetaks.ini
COPY docker/apache.conf /etc/apache2/conf-available/chetaks.conf
RUN a2enconf chetaks

COPY --chown=www-data:www-data . /var/www/html

# Folder upload (harus writable oleh www-data)
RUN mkdir -p /var/www/html/img/product /var/www/html/desain /var/www/html/buktibayar \
 && chown -R www-data:www-data /var/www/html/img/product /var/www/html/desain /var/www/html/buktibayar

EXPOSE 80
