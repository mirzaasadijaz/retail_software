# PHP 8.2 (ya aapka version) istemal karein
FROM richarvey/nginx-php-fpm:2.0.0

# Server par ek folder banayein
WORKDIR /var/www/html

# Apne local code ko server ke folder mein copy karein
COPY . /var/www/html

# File permissions set karein (Laravel ke liye zaroori)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Nginx config ko copy karein
COPY .docker/nginx.conf /etc/nginx/sites-available/default.conf

# Yeh port Render ko batata hai ke app kahan chal rahi hai
EXPOSE 80

# App ko start karne ka command
CMD ["/usr/bin/entrypoint.sh"]
