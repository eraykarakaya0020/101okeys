FROM php:8.2-cli

# Sistem paketlerini güncelle ve gerekli paketleri yükle
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer'ı yükle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini ayarla
WORKDIR /var/www/html

# Uygulama dosyalarını kopyala
COPY . .

# Composer bağımlılıklarını yükle
RUN composer install --no-dev --optimize-autoloader

# İzinleri ayarla
RUN chmod -R 755 /var/www/html

# PHP optimizasyonları
RUN echo 'memory_limit = 2G' >> /usr/local/etc/php/conf.d/memory.ini \
    && echo 'max_execution_time = 60' >> /usr/local/etc/php/conf.d/timeout.ini \
    && echo 'max_input_time = 60' >> /usr/local/etc/php/conf.d/timeout.ini \
    && echo 'post_max_size = 100M' >> /usr/local/etc/php/conf.d/upload.ini \
    && echo 'upload_max_filesize = 100M' >> /usr/local/etc/php/conf.d/upload.ini \
    && echo 'opcache.enable=1' >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo 'opcache.memory_consumption=512' >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo 'opcache.max_accelerated_files=10000' >> /usr/local/etc/php/conf.d/opcache.ini

# Startup script oluştur
RUN echo '#!/bin/bash' > /start.sh \
    && echo 'PORT=${PORT:-8000}' >> /start.sh \
    && echo 'echo "Starting PHP server on port $PORT with 32GB RAM optimization"' >> /start.sh \
    && echo 'php -S 0.0.0.0:$PORT -t /var/www/html /var/www/html/router.php' >> /start.sh \
    && chmod +x /start.sh

# Port'u expose et
EXPOSE 8000

# Uygulamayı başlat
CMD ["/start.sh"]
