#!/bin/sh
# backup all databases
if [ ! -d ./data/backup ]
then
	mkdir -p ./data/backup
fi
# shellcheck disable=SC2016
docker-compose exec mysql sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD" 2>/dev/null' > data/backup/all-databases.sql

# restore with:
# docker exec -i "$(docker-compose ps | grep mysql | cut -f1 -d' ')" /usr/bin/mysql -uroot -p"$(cat .env | grep XWL_MYSQL_ROOT_PASSWORD | cut -d= -f2)" < data/backup/all-databases.sql
