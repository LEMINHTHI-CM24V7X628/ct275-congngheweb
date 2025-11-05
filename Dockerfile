# Sử dụng PHP + Apache
FROM php:8.2-apache

# Cài các extension cần thiết cho PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Sao chép mã nguồn vào thư mục web của Apache
COPY fast-food/ /var/www/html/

# Thiết lập thư mục làm việc
WORKDIR /var/www/html/

# Cấp quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80

# Chạy Apache khi container khởi động
CMD ["apache2-foreground"]
