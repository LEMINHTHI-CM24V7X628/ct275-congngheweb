FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_pgsql pgsql
COPY ./fast-food/ /var/www/html/
WORKDIR /var/www/html/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
