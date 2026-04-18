# PHP এবং Apache ইমেজ ব্যবহার করা হচ্ছে
FROM php:8.1-apache

# MySQL কানেকশনের জন্য এক্সটেনশন ইনস্টল করা
RUN docker-php-ext-install mysqli pdo pdo_mysql

# আপনার কোডগুলো সার্ভারে কপি করা
COPY . /var/www/html/

# Apache সার্ভার চালু রাখা
EXPOSE 80
