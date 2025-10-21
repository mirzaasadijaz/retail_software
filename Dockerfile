# PHP 8.2 (ya aapka version) istemal karein
FROM richarvey/nginx-php-fpm:2.0.0

# Server par app ka folder banayein
WORKDIR /var/www/html

# Zaroori packages install karein (composer ke liye)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Composer install karein
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Pehle sirf composer files copy karein taake cache ho sakein
COPY composer.json composer.lock ./

# Dependencies install karein (production ke liye)
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Ab poora code copy karein
COPY . .

# Nginx config ko copy karein
COPY .docker/nginx.conf /etc/nginx/sites-available/nginx.conf

# Startup script ko copy karke executable banayein
COPY .docker/entrypoint.sh /usr/bin/entrypoint.sh
RUN chmod +x /usr/bin/entrypoint.sh

# File permissions set karein (Laravel ke liye zaroori)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Yeh port Render ko batata hai ke app kahan chal rahi hai
EXPOSE 80

# App ko start karne ka command
CMD ["/usr/bin/entrypoint.sh"]

