#!/bin/sh

# Functions
heading () {
echo -e "
================================================================
 $1
================================================================"
}

heading "Updating CentOS7 box"
sudo yum install -y epel-release
sudo wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm && sudo rpm -ivh mysql-community-release-el7-5.noarch.rpm

heading "Installing yum packages";
sudo yum install -y wget curl httpd vim sshpass git nodejs npm mysql-server php php-mysql php-mcrypt php-mbstring php-gd

heading "Starting services";
sudo service mysqld start
sudo service httpd start

heading "Installing node packages"
sudo npm install -g gulp
sudo npm install -g bower

heading "Configuring Apache";
sudo sed -i 's,User apache,User vagrant,g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,Group apache,Group vagrant,g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,DocumentRoot "/var/www/html",DocumentRoot "/vagrant/public",g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,<Directory "/var/www/html">,<Directory "/vagrant/public">,g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,    Options Indexes FollowSymLinks,    Options All,g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,    AllowOverride None,    AllowOverride All,g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,#ServerName www.example.com:80,ServerName contentlaunch.app,g' /etc/httpd/conf/httpd.conf
sudo service httpd restart

heading "Configuring mysql";
echo "Setting up default permissions"
mysql -e "UPDATE mysql.user SET Password = PASSWORD('secret') WHERE User = 'root'"
mysql -e "DROP USER ''@'localhost'"
mysql -e "DROP USER ''@'$(hostname)'"
mysql -e "DROP DATABASE test"
mysql -e "FLUSH PRIVILEGES"
echo "Add the contnetlaunch database"
sshpass -p secret mysql -u root -p -e "CREATE DATABASE contentlaunch;"
echo "Add the contnetlaunch user to the database"
sshpass -p secret mysql -u root -p -e "CREATE USER 'contentlaunch'@'localhost' IDENTIFIED BY 'launch123';"
echo "Set the permission for the contentlaunch user"
sshpass -p secret mysql -u root -p -e "GRANT ALL PRIVILEGES ON contentlaunch.* TO 'contentlaunch'@'localhost';"


heading "Installing Laravel and composer";
cd /usr/local/bin
sudo wget http://laravel.com/laravel.phar
sudo mv laravel.phar laravel
sudo chmod -R 755 laravel
sudo curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar composer

git config --global url."https://".insteadOf git://
cd /vagrant

heading "Cleaning workspace";
rm -rfv bower_components node_modules public vendor

heading "Installing composer dependencies";
composer update
composer install

heading "Setting up the database tables";
php artisan migrate
php artisan db:seed

heading "Installing Bower dependencies";
sudo su vagrant -c "bower install";

heading "Installing npm dependencies";
npm install

heading "Running gulp tasks";
gulp

heading "Complete!"
echo -e "\nAccess the app with these URLs:\n"
echo "	http://192.168.33.10/        -    Without editing your hosts file"
echo "	http://contentlaunch.app/    -    If you added \"192.168.33.10  contentlaunch.app\" to your hosts file"

heading "Login Information"
echo "  Email Address       |    Password  "
echo "  admin@test.com      |    launch123 "
echo "  manager@test.com    |    launch123 "
echo "  client@test.com     |    launch123 "
echo "  editor@test.com     |    launch123 "
echo "  creator@test.com    |    launch123 "

echo -e "\n\nEnjoy!"
