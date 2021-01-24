FROM ambientum/php:7.3-nginx
RUN mkdir /home/ambientum/certs
COPY nginx.conf /etc/nginx/sites/laravel.conf