#!/bin/sh
if test -f ./data/acme.json -o test -f ./data/traefik.toml
then
	echo "$PWD/data/acme.json or $PWD/data/traefik.toml already exists! skipping"
	exit 1
fi

# set up data directory & network
mkdir ./data
echo "enter password for admin account:"
PASSWD=`htpasswd -nBC 10 admin`
cat traefik.toml | sed "s!admin:my-encrypted-htpasswd!$PASSWD!" >./data/traefik.toml
touch ./data/acme.json
chmod 600 ./data/acme.json
docker network create proxy
