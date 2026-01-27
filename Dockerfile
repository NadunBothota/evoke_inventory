# Use a PHP 8.3 FPM image as the base. This matches your project's dependency requirements.
FROM php:8.3-fpm

# Install essential system dependencies required for a Laravel application.
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev

# Clear the apt cache to keep the image size down.
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install the PHP extensions that Laravel and common packages rely on.
RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd zip

# Install Composer globally, which is the dependency manager for PHP.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory for the application inside the container.
WORKDIR /var/www/html

# Copy your local project files into the container's working directory.
COPY . .

# Add the project directory as a safe directory for git. This prevents "dubious ownership" errors.
RUN git config --global --add safe.directory /var/www/html

# Set an unlimited memory limit for Composer and install your project's PHP dependencies.
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --no-scripts --no-dev


# Install Node.js and npm to compile your frontend assets.
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Install your project's JavaScript dependencies and build the production assets.
RUN npm install
RUN npm run build

# Set the correct file permissions for Laravel's storage and cache directories.
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 9000, which is the default port for PHP-FPM.
EXPOSE 9000

# The default command to run when the container starts. This starts the PHP-FPM process manager.
CMD ["php-fpm"]
