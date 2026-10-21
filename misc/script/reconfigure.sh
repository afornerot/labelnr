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

# La CSS FontAwesome du bundle (bninefiles) reference 8 webfonts, mais le bundle
# n'en livre que 3 (les .woff2 brands/regular/solid). On complete avec les
# webfonts de l'application (public/lib/fontawesome/webfonts) pour que toutes
# les url() de la CSS resolvent.
if [ -d public/lib/fontawesome/webfonts ] && [ -d public/bundles/bninefiles/lib/webfonts ]; then
    cp -f public/lib/fontawesome/webfonts/* public/bundles/bninefiles/lib/webfonts/
fi

exec $@