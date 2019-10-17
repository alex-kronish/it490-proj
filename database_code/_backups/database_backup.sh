#!/bin/sh

ts=$(date "+%Y%m%d_%H%M%S")
fname="IT490_DB_BACKUP_"$ts".sql"
echo $fname
mysqldump -u IT490_DBUSER --databases IT490_MYSTERY_STEAM_THEATER -pIT490 --single-transaction > $fname

