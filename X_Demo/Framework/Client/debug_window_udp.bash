#!/bin/bash
echo "Lanzando ventana"
#xterm -title "Debug CoreG2"  -bg black -hold -e netcat -u -l -p 7869
xterm -title "Debug CoreG2"  -bg black -e bash -c 'while [ 1 ]; do netcat -u -l -p 7869;clear;done' 