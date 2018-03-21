#start with our base image (the foundation) - version 7.1.5
FROM php:7.0.19-apache

#install all the system dependencies and enable PHP modules 
RUN apt-get update && apt-get install -y \  
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      git \
      zip \
      unzip \
      wget \
      vim \
      telnet \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
      intl \
      mbstring \
      mcrypt \
      pcntl \
      pdo_mysql \
      pdo_pgsql \
      pgsql \
      zip \
      opcache

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#set our application folder as an environment variable
ENV APP_HOME /var/www/html

#change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# enable apache module rewrite
RUN a2enmod rewrite
RUN a2ensite 000-default

#change the web_root to laravel /var/www/html/public folder
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf
RUN sed -i -e "s/#ServerName www.example.com/ServerName localhost/g" /etc/apache2/sites-enabled/000-default.conf
RUN sed -i -e "s/\${APACHE_LOG_DIR}/\/var\/log\/apache2/g" /etc/apache2/sites-enabled/000-default.conf

# add some php configurations
COPY config/php-configs/php.ini /usr/local/etc/php

# https://xdebug.org/docs/install
RUN cd /tmp \
    && wget https://xdebug.org/files/xdebug-2.5.0.tgz \
    && tar -zxvf xdebug-2.5.0.tgz \
    && cd xdebug-2.5.0 \
    && /usr/local/bin/phpize \
    && ./configure --enable-xdebug --with-php-config=/usr/local/bin/php-config \
    && make \
    && cp modules/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20151012/


# add xdebug configurations
RUN { \
        echo '[xdebug]'; \
        echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so'; \
        echo 'xdebug.remote_enable=on'; \
        echo 'xdebug.remote_autostart=on'; \
        echo 'xdebug.remote_connect_back=off'; \
        echo 'xdebug.remote_handler=dbgp'; \
        echo 'xdebug.profiler_enable=off'; \
        echo 'xdebug.profiler_output_dir="/var/www/html"'; \
        echo 'xdebug.remote_port=9000'; \
        #echo 'xdebug.remote_port=$XDEBUG_HOST_IP'; \ # Now setting this with ENV VAR instead.
    } > /usr/local/etc/php/conf.d/xdebug.ini

#copy source files and run composer
#COPY . $APP_HOME

# install all PHP dependencies
#ENV COMPOSER_ALLOW_SUPERUSER 1
#RUN composer install --no-interaction

#change ownership of our applications
#RUN chown -R www-data:www-data $APP_HOME
