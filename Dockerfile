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

# Install Yii2 basic application template
RUN composer create-project --prefer-dist --no-interaction yiisoft/yii2-app-basic /app

# Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

COPY cron/reminder /etc/cron.d/reminder
RUN chmod 0644 /etc/cron.d/reminder

# Expose port for PHP built-in server
EXPOSE 8080

# Use entrypoint to allow running PHP server or other commands
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app/web", "/app/web/index.php"]