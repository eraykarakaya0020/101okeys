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

# Railway için port ayarı
ENV PORT=8000
EXPOSE $PORT

# Uygulamayı başlat
CMD ["php", "-S", "0.0.0.0:$PORT"]
