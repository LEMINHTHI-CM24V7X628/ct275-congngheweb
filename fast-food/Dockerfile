# Sử dụng image PHP + Apache
FROM php:8.2-apache

# Cài đặt extension cần thiết (nếu có dùng PostgreSQL)
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy toàn bộ mã nguồn vào thư mục web của Apache
COPY . /var/www/html/

# Cấp quyền truy cập
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80

# Chạy Apache
CMD ["apache2-foreground"]
