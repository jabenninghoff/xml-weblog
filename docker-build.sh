#!/bin/sh
# local build wrapper
set -e

docker build --build-arg XML_WEBLOG_VERSION="$(cat version.txt | tr -d '[:space:]')" -t xml-weblog:dev "$@" .
