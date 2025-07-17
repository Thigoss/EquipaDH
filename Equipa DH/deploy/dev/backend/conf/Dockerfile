FROM ubuntu:18.04
#php7.2-dom php7.2-pdo-mysql libapache2-mod-fastcgi
ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update -y
RUN apt-get install apache2 -y
RUN apt-get install php7.2 php7.2-mbstring php7.2-curl php7.2-pgsql php7.2-xml php7.2-cli php7.2-common php7.2-fpm php7.2-json php7.2-opcache php7.2-readline php7.2-gd php7.2-soap nano vim -y
RUN apt-get install git zip unzip cron -y
RUN apt-get install libjpeg-dev -y
RUN apt-get install libpng-dev -y
RUN apt-get install libxml2-dev -y
RUN a2enmod proxy_fcgi rewrite
RUN echo "America/Sao_Paulo" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata
COPY ./artisan.sh /bin/artisan
COPY ./init.sh /init.sh
COPY ./composer.sh /bin/composer
COPY ./quickstart.sh /bin/quickstart
COPY ./www.conf /etc/php/7.2/fpm/pool.d/www.conf
COPY ./env.conf /etc/php/7.2/fpm/env.conf
COPY ./php-fpm.conf /etc/php/7.2/fpm/php-fpm.conf
COPY ./env.php /env.php
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./php.ini /etc/php/7.2/fpm/php.ini
COPY ./keystore_pgc.pem /keystore_pgc.pem
COPY ../../src/api/ /var/www/html

#Tunning apache
COPY mpm_worker.conf /etc/apache2/mods-available/mpm_worker.conf
COPY mpm_event.conf /etc/apache2/mods-available/mpm_event.conf
COPY mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf


RUN chmod 0777 /env.php /init.sh /bin/artisan /bin/composer /bin/quickstart
RUN export TERM=xterm
EXPOSE 80
WORKDIR /var/www/html
CMD ["/init.sh"]

