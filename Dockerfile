FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Set default environment variables for production
ENV APP_KEY="base64:33k/fp6r26oCVsXsyLyIsuBUAAIH/3GjOeid5WBIkO4="
ENV APP_ENV="production"
ENV APP_DEBUG="true"
ENV APP_URL="https://hound-production-1928.up.railway.app"
ENV DB_CONNECTION="sqlite"
ENV DB_DATABASE="/app/database/database.sqlite"

# Copy composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the entire application (vendor is already committed)
COPY . .

# Install dependencies (vendor is committed but ensure autoloader is optimized)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Cache config and views — but NOT routes (app has duplicate route names across role groups by design)
RUN php artisan config:cache || true
RUN php artisan view:cache || true

# Make sure storage and bootstrap cache directories exist with correct permissions
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Make sure SQLite database is available and has write permissions
RUN mkdir -p database && touch database/database.sqlite && chmod -R 777 database

# Run database migrations to apply any pending schema changes
RUN php artisan migrate --force

# Expose port (Railway sets $PORT dynamically)
EXPOSE 8080

# Start Laravel's built-in server on the Railway-assigned port
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
