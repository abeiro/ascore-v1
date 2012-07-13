#!/bin/bash

for i in $(find -name "*.po")
do
  cd $(dirname $i)
  msgfmt --check --strict $(basename $i) -o $(basename $i|sed s/"\.po"/"\.mo"/)
  cd -
done
