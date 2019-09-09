#!/bin/bash

ng build --prod

rm ../../public/$1/*.js
rm ../../public/$1/*.css
rm ../views/$1-index.html

mv dist/*/*.js ../../public/$1/
mv dist/*/*.css ../../public/$1/
mv dist/*/index.html ../views/$1-index.html

if test $1; then
sed -i -e "s/src=\"/src=\"$1\//g" -e "s/href=\"styles/href=\"$1\/styles/g" ../views/$1-index.html;
fi
