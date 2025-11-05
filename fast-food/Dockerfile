# Sử dụng PHP 8.2 với Apache
FROM php:8.2-apache

# Cài các extension cần cho PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copy toàn bộ mã nguồn vào thư mục web
COPY . /var/www/html/

# Phân quyền để Apache có thể truy cập
RUN chown -R www-data:www-data /var/www/html

# Mở port 80 để Render truy cập
EXPOSE 80

# Chạy Apache khi container khởi động
CMD ["apache2-foreground"]
