# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
    netcat-traditional \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd zip intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets (if needed)
RUN npm install && npm run build 2>/dev/null || true

# Create writable directories and set permissions
RUN mkdir -p writable/cache writable/debugbar writable/logs writable/session writable/uploads \
    && chown -R www-data:www-data writable \
    && chmod -R 755 writable

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "=== Starting CI-Books Application ===\n\
Container started at: $(date)"\n\
\n\
# Wait for MySQL to be ready\n\
echo "Waiting for MySQL..."\n\
timeout=30\n\
counter=0\n\
while ! nc -z mysql 3306; do\n\
    counter=$((counter + 1))\n\
    if [ $counter -gt $timeout ]; then\n\
        echo "ERROR: MySQL did not respond within ${timeout} seconds"\n\
        exit 1\n\
    fi\n\
    echo "Attempt $counter/$timeout: MySQL port not open yet..."\n\
    sleep 1\n\
done\n\
echo "✅ MySQL port is open!"\n\
\n\
# Additional wait for MySQL to be fully ready\n\
sleep 3\n\
\n\
# Test database connection\n\
echo "Testing database connection..."\n\
for i in {1..10}; do\n\
    if php -r "\n\
        try {\n\
            \$pdo = new PDO('\''mysql:host=mysql;dbname=books_management_ci4'\'', '\''root'\'', '\''root'\'');\n\
            echo \"✅ Database connection successful\\n\";\n\
            exit(0);\n\
        } catch (Exception \$e) {\n\
            echo \"Attempt $i: Database connection failed: \" . \$e->getMessage() . \"\\n\";\n\
            exit(1);\n\
        }\n\
    "; then\n\
        break\n\
    fi\n\
    if [ $i -eq 10 ]; then\n\
        echo "❌ Database connection failed after 10 attempts"\n\
        exit 1\n\
    fi\n\
    sleep 2\n\
done\n\
\n\
# Run database migrations\n\
echo "Running migrations..."\n\
if php spark migrate; then\n\
    echo "✅ Migrations completed"\n\
else\n\
    echo "❌ Migrations failed"\n\
    exit 1\n\
fi\n\
\n\
# Run database seeds\n\
echo "Running seeds..."\n\
if php spark db:seed CreateSampleData; then\n\
    echo "✅ Seeds completed"\n\
else\n\
    echo "❌ Seeds failed"\n\
    exit 1\n\
fi\n\
\n\
echo "Starting Apache..."\n\
# Start Apache\n\
exec apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Expose port 80
EXPOSE 80

# Health check
HEALTHCHECK --interval=10s --timeout=5s --start-period=30s --retries=5 \
    CMD curl -f http://localhost/ || exit 1

# Start the application
CMD ["/usr/local/bin/start.sh"]