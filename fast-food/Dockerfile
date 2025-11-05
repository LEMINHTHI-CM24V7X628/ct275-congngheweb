# Sử dụng PHP + Apache
FROM php:8.2-apache

# Cài extension PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy mã nguồn từ thư mục fast-food vào thư mục web server của Apache
COPY fast-food/ /var/www/html/

# Đặt thư mục làm working directory
WORKDIR /var/www/html/

# Cấp quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80

# Khởi động Apache
CMD ["apache2-foreground"]
