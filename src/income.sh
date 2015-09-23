#!/bin/bash
DAY1=$1
DAY2=$2

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
  DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
DIR=$(dirname $SOURCE)

echo $DAY1
/usr/bin/php $DIR/Maxine/displaycase/fleetDayImporter.php $DAY1
echo $DAY2
/usr/bin/php $DIR/Maxine/displaycase/fleetDayImporter.php $DAY2