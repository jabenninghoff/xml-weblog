# xml-weblog
An XSLT based weblog engine written in PHP

## Overview
_From the introductory article, ca. 2002:_

Welcome to xml-weblog, the first fully buzzword-compliant weblog/portal engine.

All content and most configuration information is stored on the database (currently MySQL, but in the future, additional databases will be supported) back-end, with all code residing on the web server. The intermediate content layer is a PHP-generated XML page, using the built-in xml-weblog format (dtd not yet written). This intermediate format is converted into XHTML or other formats using the XSLT/PHP front-end.

This separation of content, logical presentation, and actual presentation (style) makes it easy to change the look & feel of the site or present the site in multiple styles and formatted for different display devices (i.e. mobile/AvantGo).

## Installing

Quickstart instructions to get an instance up & running. Assumes DNS or `dnsmasq` is properly configured.

Start the [traefik](https://traefik.io) reverse proxy using the `init.sh` script:
```sh
cd docker/traefik
cp template.env .env && vim .env # optional
sh init.sh
docker-compose up -d
```

Start the applicaiton and load the default database:
```sh
cd ../..
cp template.env .env && vim .env
docker-compose up -d
lynx http://xml-weblog.test/install/load_dbase.php
```
