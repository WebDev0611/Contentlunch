#!/bin/bash

rm -Rf /var/www/html/
mkdir /var/www/public/adminer/
mv /var/www/adminer/index.php /var/www/public/adminer/index.php
rmdir /var/www/adminer/