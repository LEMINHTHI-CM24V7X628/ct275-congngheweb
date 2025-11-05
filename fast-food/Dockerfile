FROM php:8.2-apache

# Cài đặt extension PostgreSQL nếu dùng
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy mã nguồn vào thư mục web server
COPY fast-food/ /var/www/html/

# Cấp quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80

# Khởi động Apache
CMD ["apache2-foreground"]
