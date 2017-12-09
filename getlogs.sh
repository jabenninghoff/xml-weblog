#!/bin/sh
# Grab yesterday/today's info from web logs, ignoring patterns in logs.ignore
# use:
#   ls data/assets/ | sed 's/\./\\&/' | sed 's!.*!"(GET|HEAD) /assets/& HTTP/1\\.[01]"!' >>./data/logs.ignore
# to ignore files in /assets

# Match nginx access log date format (19/May/2007)
if [ "$1" == "-y" ]
then
	# yesterday's logs
	WWWMATCH="`date -ur \`echo "\\\`date +%s\\\` - (24 * 60 * 60 )" | bc\` '+%d/%b/%Y'`"
else
	# today's logs
	WWWMATCH="`date -u '+%d/%b/%Y'`"
fi

docker-compose logs --no-color web | grep $WWWMATCH | egrep -vf ./data/logs.ignore
