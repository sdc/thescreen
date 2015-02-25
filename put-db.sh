#!/bin/bash
# http://misc.flogisoft.com/bash/tip_colors_and_formatting
DB="thescreen"

echo -e "\e[1mImporting SQL dump\e[0m"

if [ -r "$1" ]; then

  if grep -iq 'DROP DATABASE IF EXISTS' "$1"; then
    echo
    echo -e "\e[36;21m$1\e[39m seems legit. Importing into '$DB'..."

    echo
    echo -e "\e[49;31mThis will destroy your database!! Be \e[41;30msure\e[49;31m you want to do this.\e[0m"

    echo
    mysql -u root -p -h localhost --default_character_set utf8 $DB < $1

    echo
    echo Done.
    exit 0
  else
    # Doesn't seem to be an SQL dump.
    echo -e "\e[41mError:\e[0m \e[36;21m$1\e[39m doesn't seem to be a SQL dump. Exiting."
    exit 1
  fi

else
  # No file specified on the command line.
  echo -e "\e[41mError:\e[0m Please specify a file. Exiting."
  exit 1
fi
