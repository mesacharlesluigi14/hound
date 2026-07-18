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

# Copy composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the entire application (vendor is already committed)
COPY . .

# Install dependencies (vendor is committed but ensure autoloader is optimized)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Cache config and views — but NOT routes (app has duplicate route names across role groups by design)
RUN php artisan config:cache || true
RUN php artisan view:cache || true

# Make sure SQLite database is available
RUN mkdir -p database && touch database/database.sqlite

# Expose port (Railway sets $PORT dynamically)
EXPOSE 8080

# Start Laravel's built-in server on the Railway-assigned port
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
