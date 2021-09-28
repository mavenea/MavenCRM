#! /bin/sh

USE_PHP=php
CRONDIR=$(dirname "$0")
cd "$CRONDIR" || exit
while :
do
  sleep 5m
  $USE_PHP -f RunCron.php "$*"
done