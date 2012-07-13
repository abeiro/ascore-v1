#!/bin/bash
echo "Lanzando ventana"
konsole -title "Debug CoreG2"  -e bash -c 'while [ 1 ]; do netcat -w 1 -u -l $(hostname) 7869;done' 
