FROM ubuntu:latest

LABEL maintainer="nileio@nileio.com"

ARG DEBIAN_FRONTEND=noninteractive
ARG DUMB_INIT_VERSION=1.2.5

#Users and Passwords
ARG MYSQL_ROOT_PASSWORD=root
ARG DATABASE_NAME=vtenext
ARG DBUSER_NAME=vtenextuser
ARG DBUSER_PASSWORD=stRongPass1rd

# pass --build-arg REMOTESOURCE=false if you you do not want to download the package from sourceforge. Do this for local development
# for example : docker build --build-arg REMOTESOURCE=true -t vtnext .
# then you can run the container and mount a volume to the src directory like
# docker run -d -p 80:80 -v /path/to/source/travelnext/src:/www --name=travelnext vtenext
ARG REMOTESOURCE=true

ENV TIMEZONE=Australia/Melbourne
# Set the lang, you can also specify it as as environment variable through docker-compose.yml
#ENV LANG=en_US.UTF-8 LANGUAGE=en_US.UTF-8
ENV PORT=80
ENV MYSQLROOT_PASSWORD=${MYSQL_ROOT_PASSWORD} DATABASE_NAME=${DATABASE_NAME} DBUSER_NAME=${DBUSER_NAME} DBUSER_PASSWORD=${DBUSER_PASSWORD}
ENV PHP_MEMORY_LIMIT=256M
ENV MAX_UPLOAD=50M
ENV PHP_MAX_FILE_UPLOAD=200
ENV PHP_MAX_POST=100M


WORKDIR /tmp

# **** INSTALL ****
#REQUIRED PACKAGES INSTALLATION

#install apache and php including required php modules
RUN apt-get update -y &&\
    apt-get upgrade -y &&\
    apt-get install -y\
    wget cron net-tools\
    apache2\
    mysql-server\
    php php-cli php-gd php-apcu php-memcached php-intl php-bcmath php-xdebug\
    php-imap php-ldap php-mysql php-curl php-imagick php-mbstring php-xml libapache2-mod-php
# ****

# **** MYSQL ****

#initialise mysql db
RUN usermod -d /var/lib/mysql/ mysql && \
    mkdir -p /var/run/mysqld && \
    chown mysql:mysql /var/run/mysqld && \
    ln -s /usr/lib/libxml2.so.2 /usr/lib/libxml2.so

#configure mysql
COPY ./docker/my.conf /etc/mysql/my.cnf
#create database
# Note: root user access from external host in the same network is enabled using GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '${MYSQLROOT_PASSWORD}' WITH GRANT OPTION; \
# without this statement phpmyadmin or any other client on a different host cannot connect
# combine this with skip-networking (tcp level) option to close out root user access from any other host
RUN (mysqld_safe &) && \
    mysqladmin --silent --wait=5 ping || exit 1 && \
    mysqladmin -uroot password "${MYSQLROOT_PASSWORD}" && \
    mysql -uroot -p"${MYSQLROOT_PASSWORD}" -e "CREATE DATABASE ${DATABASE_NAME} DEFAULT CHARACTER SET utf8 default collate utf8_general_ci;\
    CREATE USER ${DBUSER_NAME}@localhost IDENTIFIED BY '${DBUSER_PASSWORD}';\
    GRANT ALL PRIVILEGES ON ${DATABASE_NAME}.* TO ${DBUSER_NAME}@localhost;\
    DELETE FROM mysql.user WHERE User=''; \
    DROP DATABASE IF EXISTS test; \
    DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%';FLUSH PRIVILEGES;"
# ****
# **** APACHE & PHP ****
#APACHE: configure apache2
#sed -i 's@^#LoadModule rewrite_module modules/mod_rewrite.so@LoadModule rewrite_module modules/mod_rewrite.so@g' /etc/apache2/httpd.conf && \
RUN mkdir -p /var/log/php/sessions /var/log/xdebug/ && \
    chown -R www-data:www-data /var/log/php/sessions /var/log/xdebug/
#PHP-7: configure php7

COPY ./docker/php-ini-overrides.ini /etc/php/7.4/apache2/conf.d/
COPY ./docker/site.conf /etc/apache2/sites-available/site.conf
RUN mkdir -p /tmp/xdebug && chown -R www-data:www-data /tmp/xdebug
# ****


# **** PACKAGE COPY ****
# download the package
RUN mkdir /var/www/vtenext && (if [ ${REMOTESOURCE} = true ]; then wget https://sourceforge.net/projects/vtecrm/files/vtenext20ce.tgz &&\
    tar xzf vtenext20ce.tgz --strip-components=1 -C /var/www/vtenext  &&\
    rm vtenext20ce.tgz ; fi) &&\
    chown -R www-data:www-data /var/www/vtenext
#create phpinfo.php [remove this when you dont need it]
#RUN echo "<?php phpinfo(); ?>" > /var/www/vtenext/phpinfo.php && \
#    chown -R www-data:www-data /var/www/vtenext /tmp/sessions

#overwrite with any source file updates
COPY --chown=www-data:www-data ./dest /var/www/vtenext
# ****


#Disable default Apache site & enable site;
#Enable Apache Rewrite Module;
RUN phpenmod imap && \
    a2dissite 000-default.conf && \
    a2ensite site.conf && \
    a2enmod rewrite && dpkg-reconfigure libapache2-mod-php


# **** START SCRIPT ****
# copy a start script for services
COPY ./docker/start-ubuntu.sh /usr/bin/start-ubuntu.sh
RUN chmod +x /usr/bin/start-ubuntu.sh

# ****

WORKDIR /var/www/vtenext
EXPOSE ${PORT}

CMD ["start-ubuntu.sh"]
