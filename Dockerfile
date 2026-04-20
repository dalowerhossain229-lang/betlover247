# ১. অফিশিয়াল পিএইচপি ইমেজ ব্যবহার করা
FROM php:8.2-apache

# ২. ডাটাবেস কানেকশনের জন্য দরকারি এক্সটেনশন ইনস্টল করা
RUN docker-php-ext-install mysqli pdo pdo_mysql

# ৩. আপনার গিটহাবের সব ফাইল কন্টেইনারে কপি করা
COPY . /var/www/html/

# ৪. অ্যাপাচি সার্ভারকে পারমিশন দেওয়া
RUN chown -R www-data:www-data /var/www/html/

# ৫. পোর্ট ৮০ ওপেন করা
EXPOSE 80
