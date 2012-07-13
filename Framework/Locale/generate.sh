#!/bin/bash

for i in $(find -name "*.po")
do
  cd $(dirname $i)
  msgfmt $(basename $i) -o $(basename $i|sed s/"\.po"/"\.mo"/)
  cd -
done
