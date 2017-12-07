#!/bin/sh
# set up data directory & network
if [ ! -f .env ]
then
	cp template.env .env
fi
. .env

if [ ! -d ./data ]
then
	mkdir ./data
fi

if [ ! -f ./data/traefik.toml ]
then
	echo "enter password for admin account:"
	PASSWD=`htpasswd -nBC 10 admin`
	cat traefik.toml | sed "s!admin:my-encrypted-htpasswd!$PASSWD!" | sed "s/admin@example.com/$EMAIL/"  >./data/traefik.toml
fi

if [ ! -f ./data/acme.json ]
then
	touch ./data/acme.json
	chmod 600 ./data/acme.json
fi

docker network create -o "com.docker.network.bridge.host_binding_ipv4"="$HOST_BIND_IP" public
