# Use PHP 8.1 with Apache
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set proper permissions for Cloud VPS 10
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 755 /var/www/html/public

# Create necessary directories
RUN mkdir -p /var/www/html/storage/uploads \
    && mkdir -p /var/www/html/storage/backups \
    && mkdir -p /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/storage

# Configure Apache for RMS
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/rms.conf \
    && echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/rms.conf \
    && echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/rms.conf \
    && echo '        AllowOverride All' >> /etc/apache2/sites-available/rms.conf \
    && echo '        Require all granted' >> /etc/apache2/sites-available/rms.conf \
    && echo '    </Directory>' >> /etc/apache2/sites-available/rms.conf \
    && echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/rms.conf \
    && echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/rms.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/rms.conf

# Enable the site
RUN a2dissite 000-default && a2ensite rms

# Expose port 80
EXPOSE 80

# Health check for Cloud VPS monitoring
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Start Apache
CMD ["apache2-foreground"]