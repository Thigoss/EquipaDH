FROM ubuntu:20.04
#php8.0-dom php8.0-pdo-mysql libapache2-mod-fastcgi
ENV TZ=America/Sao_Paulo

RUN apt-get update -y
RUN apt-get install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update -y
RUN apt-get install apache2 -y
RUN apt-get install php8.0 php8.0-zip php8.0-mbstring php8.0-curl php8.0-mysql php8.0-pgsql php8.0-xml php8.0-cli php8.0-common php8.0-fpm php8.0-opcache php8.0-readline php8.0-gd php8.0-soap nano vim -y
RUN apt-get install git zip unzip cron -y
RUN apt-get install libjpeg-dev -y
RUN apt-get install libpng-dev -y
RUN apt-get install libxml2-dev -y
RUN a2enmod proxy_fcgi rewrite
RUN echo "America/Sao_Paulo" > /etc/timezone && dpkg-reconfigure -f noninteractive tzdata
COPY ./conf/artisan.sh /bin/artisan
COPY ./conf/init.sh /init.sh
COPY ./conf/composer.sh /bin/composer
COPY ./conf/quickstart.sh /bin/quickstart
COPY ./conf/www.conf /etc/php/8.0/fpm/pool.d/www.conf
COPY ./conf/env.conf /etc/php/8.0/fpm/env.conf
COPY ./conf/php-fpm.conf /etc/php/8.0/fpm/php-fpm.conf
COPY ./conf/env.php /env.php
COPY ./conf/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./conf/php.ini /etc/php/8.0/fpm/php.ini
COPY ./conf/keystore_pgc.pem /keystore_pgc.pem
COPY ./src/ /var/www/html

#Tunning apache
COPY ./conf/mpm_worker.conf /etc/apache2/mods-available/mpm_worker.conf
COPY ./conf/mpm_event.conf /etc/apache2/mods-available/mpm_event.conf
COPY ./conf/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf

RUN find /var/www/html -type f -exec chmod 664 {} \;    
RUN find /var/www/html -type d -exec chmod 775 {} \;
RUN chmod 0777 /env.php /init.sh /bin/artisan /bin/composer /bin/quickstart
RUN export TERM=xterm
EXPOSE 80
WORKDIR /var/www/html
CMD ["/init.sh"]

