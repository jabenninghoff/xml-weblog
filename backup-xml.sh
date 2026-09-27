#!/bin/sh
# backup xml_weblog databases
if [ ! -d ./data/backup ]
then
	mkdir -p ./data/backup
fi
# shellcheck disable=SC2016
docker-compose exec mysql sh -c 'exec mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" xml_weblog 2>/dev/null' > data/backup/xml_weblog.sql

# restore with:
# docker exec -i "$(docker-compose ps | grep mysql | cut -f1 -d' ')" /usr/bin/mysql -uroot -p"$(cat .env | grep XWL_MYSQL_ROOT_PASSWORD | cut -d= -f2)" xml_weblog < data/backup/xml_weblog.sql
