#!/bin/sh
# monitor database for changes

[ ! -d ./data/backup ] && mkdir -p ./data/backup

if [ ! -f ./data/backup/snapshot.sql ]
then
	echo "Error: no snapshot found, creating snapshot.sql."
	# shellcheck disable=SC2016
	docker-compose exec mysql sh -c 'exec mysqldump --all-databases --skip-comments -uroot -p"$MYSQL_ROOT_PASSWORD" 2>/dev/null' > data/backup/snapshot.sql
	exit 1
fi

# shellcheck disable=SC2016
if ! docker-compose exec mysql sh -c 'exec mysqldump --all-databases --skip-comments -uroot -p"$MYSQL_ROOT_PASSWORD" 2>/dev/null' | diff -u data/backup/snapshot.sql -
then
	echo ""
	echo "Error: database has changed!"
	exit 1
fi
