#!/usr/bin/env bash
status=1
while [ "$status" != "0" ]
do
   php /var/www/html/XMLReaderJob.php
   status=$?
   echo "Current result: $status"
   sleep 1
done