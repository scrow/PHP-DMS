#!/bin/bash
#**
#* initialize.sh:
#* 
#* Creates the initial day number and token data files, the creates the data
#* directory, and places an .htaccess file inside the data directory to secure
#* against remote access.
#** 

# Seed the data files with initial values
echo 0 > daynum.dat
echo 0 > token.dat

# Ensure the data files are writable
chmod +w daynum.dat
chmod +w token.dat

# Create and secure the data directory
[ -d data ] || mkdir data
echo order deny,allow > data/.htaccess
echo deny from all >> data/.htaccess
