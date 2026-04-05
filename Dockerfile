FROM php:8.3-cli

# Install system packages and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libsqlite3-dev \
        curl \
        ca-certificates \
        build-essential \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip bcmath pcntl

# Install Composer (copy from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js (LTS) so we can run npm builds
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y --no-install-recommends nodejs

# Set working directory
WORKDIR /app

# Copy the Laravel application into the image
COPY Apartment_BoookingSystem/laravel_app /app

# Install PHP dependencies and build frontend
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts || true
RUN npm ci --silent && npm run build --silent || true

# Ensure start script is executable
RUN chmod +x /app/scripts/railway-start.sh || true

EXPOSE 8000

# Use the existing railway start script which runs migrations and starts the server
CMD ["/bin/bash", "/app/scripts/railway-start.sh"]
