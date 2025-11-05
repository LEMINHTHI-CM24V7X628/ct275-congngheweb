# Sử dụng PHP 8.2 có Apache
FROM php:8.2-apache

# Cài đặt extension PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copy toàn bộ source vào thư mục web server
COPY ./fast-food/ /var/www/html/

# Đặt thư mục làm webroot
WORKDIR /var/www/html/

# Bật rewrite module để PHP MVC hoạt động
RUN a2enmod rewrite

# Tạo file cấu hình Apache cho phép rewrite
RUN echo '<Directory /var/www/html/>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n\
ServerName localhost' > /etc/apache2/sites-available/000-default.conf

# Phân quyền cho Apache
RUN chown -R www-data:www-data /var/www/html

# Mở port
EXPOSE 80

# Chạy Apache
CMD ["apache2-foreground"]
