#!/bin/bash

find -type f -name "*php" > list_myform_files.txt
find -type f -name "*html" >> list_myform_files.txt
xgettext -E --from-code=iso-8859-1 -f list_myform_files.txt -k_ -j -o po/myform.pot
