#!/bin/bash
#
# User Statistics extension for the phpBB Forum Software package.
#
# @copyright (c) 2015 Jakub Senko <jakubsenko@gmail.com>
# @license GNU General Public License, version 2 (GPL-2.0)
#
set -e
set -x

DB=$1
TRAVIS_PHP_VERSION=$2
EXTNAME=$3

if [ "$TRAVIS_PHP_VERSION" == "5.3.3" -a "$DB" == "mysqli" ]
then
	phpBB/ext/$EXTNAME/vendor/bin/EPV.php run --dir="phpBB/ext/$EXTNAME/"
fi
