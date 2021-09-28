#!/bin/sh
#uncomment this if required only if starting the container for local development and mounting a local src folder
#because starting the container for dev
#by mounting src folder will change to root permissions
echo "fixing permissions ..."
chown -R apache:apache /www /var/log/php/sessions
rm -f /run/apache2/httpd.pid
rm -f /run/mysqld/mysqld.pid
nohup mysqld --bind-address 0.0.0.0 --user mysql >/dev/null 2>&1 &
echo "MySQL started."
#starts apache httpd in the background
httpd
echo "Apache started."
#replaces crontab and starts cron daemon under root for cron every 5min
test -f /www/logs/cron/cron.log || touch /www/logs/cron/cron.log
crontab -r -u root | {
    cat
    echo "*/5 * * * * /www/cron/RunCron.sh >> /www/logs/cron/cron.log 2>&1"
} | crontab -
crond
echo "crond started."

# this tail command is key to keep the container alive
# added tail for both mysql and httpd
tail -f /var/log/apache2/error.log -f /var/log/mysql/error.log
