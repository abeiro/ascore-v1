#!/bin/bash
echo "Lanzando ventana"
xterm -bg black -e "(while [ 1 ]; do netcat  -l -p 7869;done)"
