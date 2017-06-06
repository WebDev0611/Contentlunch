FROM cvsouth/apache-php7-mysql-redis

RUN curl -sL https://deb.nodesource.com/setup_7.x | sudo -E bash - && \
    apt-get update && \
    apt-get install -y nodejs && \
    npm install -g bower && \
    npm install -g gulp && \
    curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer



