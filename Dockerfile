# Sử dụng image PHP + Apache
FROM php:8.2-apache

# Cài đặt PostgreSQL extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy toàn bộ mã nguồn trong thư mục fast-food vào Apache
COPY fast-food/ /var/www/html/

# Đặt working directory
WORKDIR /var/www/html/

# Cấp quyền truy cập cho Apache
RUN chown -R www-data:www-data /var/www/html

# Expose cổng 80
EXPOSE 80

# Khởi động Apache
CMD ["apache2-foreground"]
