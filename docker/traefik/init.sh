#!/bin/sh
# set up data directory & network
if [ ! -f .env ]
then
	echo "Installing default .env"
	cp template.env .env
fi
. .env

if [ ! -d ./data ]
then
	echo "Adding data directory..."
	mkdir ./data
fi

if [ ! -f ./data/traefik.toml ]
then
	echo "Installing traefik.toml..."
	echo "enter password for admin account:"
	PASSWD=`htpasswd -nBC 10 admin`
	cat traefik.toml | sed "s!admin:my-encrypted-htpasswd!$PASSWD!" | sed "s/my-email/$EMAIL/"  >./data/traefik.toml
fi

if [ ! -f ./data/acme.json ]
then
	echo "Adding acme.json..."
	touch ./data/acme.json
	chmod 600 ./data/acme.json
fi

if [ "`docker network ls | awk '{print $2}' | grep traefik`" != "traefik" ]
then
	echo "Adding docker network..."
	docker network create -o "com.docker.network.bridge.host_binding_ipv4"="$HOST_BIND_IP" traefik
fi
