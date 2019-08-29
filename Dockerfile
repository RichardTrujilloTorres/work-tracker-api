################################
# ---- Work Tracker Utility ----
################################

FROM ubuntu:19.04

MAINTAINER Richard Trujillo Torres <richard [at] desemax.com>

# Allow to run as superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

###################################
# Non interactive installation(s)
###################################
ARG DEBIAN_FRONTEND=noninteractive
# ARG DEBCONF_NONINTERACTIVE_SEEN=true


####################
# Utilities
####################
RUN apt update && apt install -y ca-certificates vim git curl wget

####################
# Apache
####################
RUN apt install -y apache2


###############################
# Install php and its modules
###############################
RUN apt install -y \
    php \
    php-mysql \
    php-dev \
    libmcrypt-dev \
    php-pear \
    php-json \
    php-curl \
    php-gd \
    php-xml php-intl \
    php-zip \
    libapache2-mod-php

# RUN /usr/sbin/a2dismod php7.2
# RUN /usr/sbin/a2dismod 'mpm_*' && /usr/sbin/a2enmod mpm_prefork

# enable url rewriting
RUN /usr/sbin/a2enmod rewrite
# RUN /usr/sbin/a2enmod php7.2


ADD 000-work-tracker-api.conf /etc/apache2/sites-available/
ADD 001-work-tracker-api-ssl.conf /etc/apache2/sites-available/
RUN /usr/sbin/a2dissite '*' && /usr/sbin/a2ensite 000-work-tracker-api 001-work-tracker-api-ssl



####################
# Composer
####################
RUN /usr/bin/curl -sS https://getcomposer.org/installer |/usr/bin/php
RUN /bin/mv composer.phar /usr/local/bin/composer




####################
# Project setup
####################
WORKDIR /var/www/
RUN git clone https://github.com/RichardTrujilloTorres/work-tracker-api

# install
WORKDIR /var/www/work-tracker-api
RUN composer install --no-scripts

# permissions
RUN mkdir -p /var/www/work-tracker-api/var/log /var/www/work-tracker-api/var/cache
RUN /bin/chown -R www-data:www-data /var/www/work-tracker-api/var/cache /var/www/work-tracker-api/var/log
RUN /bin/chown -R www-data:www-data /var/www/work-tracker-api
RUN find /var/www/work-tracker-api -type f -exec chmod 644 {} \;
RUN find /var/www/work-tracker-api -type d -exec chmod 755 {} \;




# COPY entrypoint.sh /usr/local/bin/
# RUN ln -s /usr/local/bin/entrypoint.sh /entrypoint.sh


EXPOSE 80
EXPOSE 443
EXPOSE 3306

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
