#!/bin/sh
# backup all databases
if [ ! -d ./data/backup ]
then
	mkdir -p ./data/backup
fi
docker-compose exec mysql sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD" 2>/dev/null' > data/backup/all-databases.sql

# restore with:
# cat data/backup/all-databases.sql | docker exec -i `docker-compose ps | grep mysql | cut -f1 -d" "` /usr/bin/mysql -uroot -p`cat .env | grep XWL_MYSQL_ROOT_PASSWORD | cut -d= -f2`
