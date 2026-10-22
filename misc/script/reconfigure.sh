#!/bin/bash
set -eo pipefail

# Se positionner sur la racine du projet
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cd ${DIR}
cd ../..
DIR=$(pwd)

bin/console d:s:u --force
bin/console app:init

# Publier les assets des bundles (bninefiles, bninemdeditor) dans public/bundles/
bin/console assets:install public

exec $@