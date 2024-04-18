#!/usr/bin/env bash

PHP_VERSION="${1:-8.0}"

LIST_EXT=(
"GIT"
"ZIP"
"GD"
"BCMATH"
"PHPREDIS"
"OPCACHE"
"IMAGEMAGICK"
"EXIF"
"PCNTL"
"INTL"
"SOAP"
"PGSQL"
"MYSQL"
"GETTEXT"
"SOCKETS"
"MEMCACHED"
"PECL_SYNC"
)

for name in ${LIST_EXT[@]}; do
  declare "INSTALL_$name"=false
done

## Default value
PHP_VERSION="8.0"
INSTALL_EXT=true
INSTALL_GIT=true
INSTALL_ZIP=true

## Parse arguments
while [ $# -gt 0 ]; do
  case "$1" in
    --php=*)
      PHP_VERSION="${1#*=}"
      ;;
    --git)
      INSTALL_GIT=true
      INSTALL_EXT=true
      ;;
    --zip)
      INSTALL_ZIP=true
      INSTALL_EXT=true
      ;;
    --gd)
      INSTALL_GD=true
      INSTALL_EXT=true
      ;;
    --bcmath)
      INSTALL_BCMATH=true
      INSTALL_EXT=true
      ;;
    --phpredis)
      INSTALL_PHPREDIS=true
      INSTALL_EXT=true
      ;;
    --opcache)
      INSTALL_OPCACHE=true
      INSTALL_EXT=true
      ;;
    --imagemagick)
      INSTALL_IMAGEMAGICK=true
      INSTALL_EXT=true
      ;;
    --exif)
      INSTALL_EXIF=true
      INSTALL_EXT=true
      ;;
    --pcntl)
      INSTALL_PCNTL=true
      INSTALL_EXT=true
      ;;
    --intl)
      INSTALL_INTL=true
      INSTALL_EXT=true
      ;;
    --soap)
      INSTALL_SOAP=true
      INSTALL_EXT=true
      ;;
    --pgsql)
      INSTALL_PGSQL=true
      INSTALL_EXT=true
      ;;
    --mysql)
      INSTALL_MYSQL=true
      INSTALL_EXT=true
      ;;
    --gettext)
      INSTALL_GETTEXT=true
      INSTALL_EXT=true
      ;;
    --sockets)
      INSTALL_SOCKETS=true
      INSTALL_EXT=true
      ;;
    --memcached)
      INSTALL_MEMCACHED=true
      INSTALL_EXT=true
      ;;
    --pecl_sync)
      INSTALL_PECL_SYNC=true
      INSTALL_EXT=true
      ;;

    -*)
      echo "Option $1 needs a valid argument"
    exit 1
    ;;
  esac
  shift
done

## Output information
printf "\n";
echo "+=========================+";
echo "     Build by PHP:$PHP_VERSION";
echo "+=========================+";
if [ "$INSTALL_EXT" = true ]; then
  printf "  Install: ";
  for name in ${LIST_EXT[@]}; do
    state="INSTALL_$name"
    if [ "${!state}" = true ]; then
      printf "$name;"
    fi
  done
  printf "\n";
  echo "+=========================+";
fi
printf "\n";

## Make build-arg for docker compose build
build_arg=""
for name in ${LIST_EXT[@]}; do
  state="INSTALL_$name"
  if [ "${!state}" = true ]; then
    build_arg="${build_arg}  --build-arg INSTALL_$name=${!state}"
  fi
done

# Run docker
docker compose down
docker compose build \
  --build-arg PHP_VERSION=$PHP_VERSION $build_arg
docker compose up -d
docker compose exec php-cli bash
docker compose down
