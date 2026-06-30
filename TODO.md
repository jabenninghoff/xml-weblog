# TODO

## pre-migration

- [ ] Remove Traefik reverse proxy
- [ ] Refactor Dockerfile, Docker Compose files
- [ ] Implement `.github` automation from [nasmail](https://github.com/jabenninghoff/nasmail) (Dependabot, Publish Docker, Release Please)
- [ ] Security: read-only mounts except for mysql data
- [ ] Security: backup, restore script for xml-weblog database
- [ ] Security: detect changes to databases
- [ ] Security: daily scheduled task to write docker-compose logs to disk

## post-migration

- [ ] Update Docker following the [PHP language-specific guide](https://docs.docker.com/guides/php/)
- [ ] Implement PHP [Composer](https://getcomposer.org)
- [ ] Add [PHPUnit](https://phpunit.de/index.html) tests
- [ ] Upgrade to [PHP](https://www.php.net) 8.5
- [ ] Remove PEAR DB
- [ ] Replace mysql with [mariadb](https://mariadb.org)

<!-- original TODO

To Do:

* support conditional GET (news aggregators) (snap)
* add WAP + thin style, fix style/query string handling (snap)
* add user story submission - with XML-RPC support (?) (snap)
* add user signup/preferences screen (1.2)
* traditional weblog format/layout/style (benninghoff.org) (snap)
* multiple "site" support, like apple.slashdot.org (??)

Need:

* new logos
* (clean) php implementation of md5 digest auth

Maybe Do:

* multiple category support
* multiple language support
* multi-blog support (on same db)
* add client/gzip support (vs mod_gzip)

-->
