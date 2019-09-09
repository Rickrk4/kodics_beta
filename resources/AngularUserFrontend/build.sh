#!/bin/bash

ng build --prod

rm ../../public/$1/*.js
rm ../../public/$1/*.css
rm ../views/index.html

mv dist/*/*.js ../../public/$1/
mv dist/*/*.css ../../public/$1/
mv dist/*/index.html ../views/index.html

if test $1; then
sed -i -e "s/src=\"/src=\"$1\//g" -e "s/href=\"styles/href=\"$1\/styles/g" ../views/index.html;
fi
