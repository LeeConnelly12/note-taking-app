FROM dunglas/frankenphp

ENV SERVER_NAME=notes.leeconnelly.dev

WORKDIR /app

COPY docker/php.ini $PHP_INI_DIR/php.ini

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    supervisor \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy PHP dependencies first to leverage cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy Node dependencies first for caching
COPY package.json package-lock.json ./
RUN npm ci

# Copy remaining app files
COPY . .

# Build frontend
RUN npm run build:ssr

# Run Laravel optimizations
RUN php artisan optimize

# Copy Supervisor configuration
COPY docker/supervisord.conf /etc/supervisord.conf