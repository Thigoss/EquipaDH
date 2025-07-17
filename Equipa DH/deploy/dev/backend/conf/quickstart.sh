#!/bin/bash

php /env.php && service php8.0-fpm start 

/bin/chmod 777 -R /var/www/html/artisan
/bin/chmod guo+wr -R /var/www/html/storage

env | grep "^DB_" > /etc/environment

if [ -f "/var/www/html/crontab" ]; then
  /bin/chmod 0600 /var/www/html/crontab
  crontab /var/www/html/crontab
  /etc/init.d/cron start
fi

# Instalação
if [ ! -d "/var/www/html/vendor" ]; then

    #/bin/sed -i 's/index.php/index.html/g' /var/www/html/public/.htaccess
    /bin/echo "<pre>" > /var/www/html/public/install.html

    php /var/www/html/composer.phar self-update 2>> /var/www/html/public/install.html
    php /var/www/html/composer.phar install -d /var/www/html  2>> /var/www/html/public/install.html 

    #/bin/sed -i 's/index.html/index.php/g' /var/www/html/public/.htaccess
    /bin/rm -f /var/www/html/public/install.html

fi

if [ -f "/var/www/html/docker.sh" ]; then
  /bin/chmod +x /var/www/html/docker.sh
  /var/www/html/docker.sh &
fi

php -f /var/www/html/artisan optimize --force
php -f /var/www/html/artisan migrate


php -f /var/www/html/artisan route:cache
php -f /var/www/html/artisan cache:clear
php -f /var/www/html/artisan view:clear
php -f /var/www/html/artisan config:cache
php -f /var/www/html/artisan optimize --force
php -f /var/www/html/artisan storage:link

# find /var/www/html -type f -exec chmod 664 {} \;
# find /var/www/html -type d -exec chmod 775 {} \;
chmod 777 -R /var/www/html
