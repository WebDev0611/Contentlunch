#!/bin/sh

# Functions
heading () {
echo -e "
================================================================
 $1
================================================================"
}

echo "     __/ _//|\                   __             __     __                       __  "
echo "   _/_  /_/     _________  ____  / /____  ____  / /_   / /___ ___  ______  _____/ /_ "
echo "  / /_/_/      / ___/ __ \/ __ \/ __/ _ \/ __ \/ __/  / / __ \`/ / / / __ \/ ___/ __ \ "
echo " / //_/       / /__/ /_/ / / / / /_/  __/ / / / /_   / / /_/ / /_/ / / / / /__/ / / /"
echo "/ /_/         \___/\____/_/ /_/\__/\___/_/ /_/\__/  /_/\__,_/\__,_/_/ /_/\___/_/ /_/ "
echo "|_|                                                                                  "

if [ -f /etc/provisioned ]; then
    echo "Already Provisioned"
    exit 0
fi


heading "Updating CentOS7 box"
echo " ╰─➤ Installing epel-release"
sudo yum install -y epel-release  >&- 2>&-
echo " ╰─➤ Downloading MySQL 5.6 Repo"
sudo wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm  >&- 2>&-
echo " ╰─➤ Registering MySQL 5.6 Repo"
sudo rpm -ivh mysql-community-release-el7-5.noarch.rpm >&- 2>&-
echo -e "Done!\n"

heading "Installing yum packages";
echo " ╰─➤ Installing cURL"
sudo yum install -y -q curl >&- 2>&-
echo " ╰─➤ Installing Apache"
sudo yum install -y -q httpd >&- 2>&-
echo " ╰─➤ Installing vim"
sudo yum install -y -q vim >&- 2>&-
echo " ╰─➤ Installing sshpass"
sudo yum install -y -q sshpass >&- 2>&-
echo " ╰─➤ Installing git"
sudo yum install -y -q git >&- 2>&-
echo " ╰─➤ Installing NodeJS"
sudo yum install -y -q nodejs >&- 2>&-
echo " ╰─➤ Installing npm"
sudo yum install -y -q npm >&- 2>&-
echo " ╰─➤ Installing MySQL"
sudo yum install -y -q mysql-server >&- 2>&-
echo " ╰─➤ Installing php"
sudo yum install -y -q php >&- 2>&-
echo " ╰─➤ Installing php-gd"
sudo yum install -y -q php-gd >&- 2>&-
echo " ╰─➤ Installing php-mysql"
sudo yum install -y -q php-mysql >&- 2>&-
echo " ╰─➤ Installing php-mcrypt"
sudo yum install -y -q php-mcrypt >&- 2>&-
echo " ╰─➤ Installing php-mbstring"
sudo yum install -y -q php-mbstring >&- 2>&-
echo " ╰─➤ Installing phpunit"
sudo yum install -y -q phpunit >&- 2>&-
echo " Install XDebug"
sudo yum install -y php-pecl-xdebug.x86_64

echo "[xdebug]" >> /etc/php.ini
echo "zend_extension=\"/usr/lib64/php/modules/xdebug.so\"" >> /etc/php.ini
echo "xdebug.remote_enble=1" >> /etc/php.ini
echo "xdebug.idekey=marc" >> /etc/php.ini
echo "xdebug.remote_autostart=1" >> /etc/php.ini
echo "xdebug.remote_host=192.168.1.125" >> /etc/php.ini

echo -e "Done!\n"


heading "Starting services";
echo " ╰─➤ Starting MySQL"
sudo systemctl start mysqld >&- 2>&-
sudo systemctl enable mysqld >&- 2>&-
echo " ╰─➤ Starting Apache"
sudo systemctl start httpd >&- 2>&-
echo -e "Done!\n"


heading "Installing node packages"
echo " ╰─➤ Installing gulp"
sudo npm install -g gulp >&- 2>&-
echo " ╰─➤ Installing bower"
sudo npm install -g bower >&- 2>&-
echo -e "Done!\n"


heading "Configuring Apache";
echo " ╰─➤ Setting user to 'vagrant'"
sudo sed -i 's,User apache,User vagrant,g' /etc/httpd/conf/httpd.conf
echo " ╰─➤ Setting group to 'vagrant'"
sudo sed -i 's,Group apache,Group vagrant,g' /etc/httpd/conf/httpd.conf
echo " ╰─➤ Setting DocumentRoot to '/vagrant/public'"
sudo sed -i 's,DocumentRoot "/var/www/html",DocumentRoot "/vagrant/public",g' /etc/httpd/conf/httpd.conf
sudo sed -i 's,<Directory "/var/www/html">,<Directory "/vagrant/public">,g' /etc/httpd/conf/httpd.conf
echo " ╰─➤ Setting Options to 'All'"
sudo sed -i 's,    Options Indexes FollowSymLinks,    Options All,g' /etc/httpd/conf/httpd.conf
echo " ╰─➤ Setting AllowOverride to 'All'"
sudo sed -i 's,    AllowOverride None,    AllowOverride All,g' /etc/httpd/conf/httpd.conf
echo " ╰─➤ Setting ServerName to 'contentlaunch.app'"
sudo sed -i 's,#ServerName www.example.com:80,ServerName contentlaunch.app,g' /etc/httpd/conf/httpd.conf
echo " ╰─➤ Restarting Apache"
sudo systemctl restart httpd >&- 2>&-
sudo systemctl enable httpd>&- 2>&-
echo -e "Done!\n"


heading "Configuring mysql";
echo " ╰─➤ Setting root password to 'secret'"
mysql -e "UPDATE mysql.user SET Password = PASSWORD('secret') WHERE User = 'root'"
echo " ╰─➤ Dropping guest users"
mysql -e "DROP USER ''@'localhost'"
mysql -e "DROP USER ''@'$(hostname)'"
echo " ╰─➤ Flushing privileges"
mysql -e "FLUSH PRIVILEGES"
echo " ╰─➤ Creating the contnetlaunch database"
sshpass -p secret mysql -u root -p -e "CREATE DATABASE contentlaunch;"
echo " ╰─➤ Creating the contnetlaunch user to the database"
sshpass -p secret mysql -u root -p -e "CREATE USER 'contentlaunch'@'localhost' IDENTIFIED BY 'launch123';"
echo " ╰─➤ Setting the permission for the contentlaunch user"
sshpass -p secret mysql -u root -p -e "GRANT ALL PRIVILEGES ON contentlaunch.* TO 'contentlaunch'@'localhost';"
echo -e "Done!\n"


heading "Installing Composer";
cd /usr/local/bin
echo " ╰─➤ Downloading"
sudo curl -sS https://getcomposer.org/installer | sudo php
echo " ╰─➤ Installing"
sudo mv composer.phar composer
echo " ╰─➤ Setting permissions"
sudo chmod 755 composer
echo -e "Done!\n"


heading "Cleaning workspace";
cd /vagrant
echo " ╰─➤ Removing bower_components"
rm -rf bower_components
echo " ╰─➤ Removing node_modules"
rm -rf node_modules
echo " ╰─➤ Removing vendor"
rm -rf vendor
echo -e "Done!\n"


heading "Installing composer dependencies";
echo " ╰─➤ Running composer install"
/usr/bin/composer install
echo -e "Done!\n"


heading "Setting up the database";
echo " ╰─➤ Creating DB Tables"
php artisan -q migrate
echo " ╰─➤ Loading sample data"
php artisan -q db:seed
echo -e "Done!\n"


heading "Installing Bower dependencies";
echo " ╰─➤ Changing git URLs from git:// to https://"
git config --global url."https://".insteadOf git://
echo " ╰─➤ Running bower install"
sudo su vagrant -c "bower install"
echo -e "Done!\n"


heading "Installing npm dependencies";
echo " ╰─➤ Running npm install"
npm install
echo -e "Done!\n"


heading "Triggering gulp tasks";
echo " ╰─➤ Running gulp default"
gulp
echo -e "Done!\n"

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

sudo echo "ok" > /etc/provisioned
