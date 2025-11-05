FROM php:8.2-apache

# Cài các extension cần thiết
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copy toàn bộ mã nguồn hiện tại vào thư mục web
COPY . /var/www/html/

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Phân quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80

# Chạy Apache
CMD ["apache2-foreground"]
