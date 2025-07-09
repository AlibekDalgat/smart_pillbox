FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    default-mysql-client \
    cron \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required by Yii2
RUN docker-php-ext-install pdo_mysql intl zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

RUN composer create-project --prefer-dist --no-interaction yiisoft/yii2-app-basic /app

COPY php.ini /usr/local/etc/php/php.ini

COPY entrypoint.sh /entrypoint.sh
COPY cron/reminder /etc/cron.d/reminder
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app/web", "/app/web/index.php"]