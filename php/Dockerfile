FROM devilbox/php-fpm:8.2-work

# Install additional PHP extensions if needed
# RUN docker-php-ext-install pdo_mysql

# Set timezone
ENV TZ=Asia/Manila

# Copy custom php.ini
COPY conf.d/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html