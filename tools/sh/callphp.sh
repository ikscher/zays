#!/bin/bash
#callphp.sh
echo "$1 $2 $3"
scriptsDir=$(cd "$(dirname "$0")"; pwd)
(/usr/bin/php $scriptsDir/../create_sql.php $1 $2 $3 > /dev/null 2>&1 &) && exit 0
