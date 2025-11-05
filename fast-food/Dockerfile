FROM php:8.2-apache

# Cài extension cần thiết
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copy toàn bộ source code
COPY fast-food/ /var/www/html/

# Đặt thư mục làm webroot (nếu index.php nằm trong fast-food)
WORKDIR /var/www/html/

# Cấu hình Apache cho phép PHP MVC (rewrite URLs)
RUN a2enmod rewrite
COPY ./fast-food/.htaccess /var/www/html/.htaccess

# Sửa quyền
RUN chown -R www-data:www-data /var/www/html

# Mở port
EXPOSE 80

CMD ["apache2-foreground"]
