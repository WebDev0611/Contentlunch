#!/bin/bash

rm -Rf /var/www/html/
mkdir /var/www/public/adminer/
mv /var/www/adminer/index.php /var/www/public/adminer/index.php
rmdir /var/www/adminer/
rm -Rf /var/www/default/
cp -R /var/www/beanstalk_console/ /var/www/public/beanstalk_console/
rm -Rf /var/www/beanstalk_console/