FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions for MySQL and CRUD operations
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    # Database extensions
    pdo_mysql \
    mysqli \
    # Other essential extensions
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    curl \
    zip \
    intl \
    opcache \
    # Redis extension (optional)
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Configure PHP settings
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "date.timezone = UTC" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "session.save_handler = redis" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "session.save_path = tcp://redis:6379" >> /usr/local/etc/php/conf.d/custom.ini

CMD ["php-fpm"]