#!/bin/bash

echo "This script will update SearchTLD to the latest version. You need to have a webserver already set up for this."
sleep 0.5
echo "If you do not have a web server set up, then I recommend you run setup-nginx.sh for a quick install."

read -p "Continue? (Y/n)" continue 
continue=${continue:-Y}

if [[ $continue != [Yy] ]]; then  
    exit 1
fi

if [ "$(id -u)" -ne 0 ]; then
    echo -e "\033[31mThis script must be run with root privileges.\033[0m"
    exit 1
fi

git clone https://github.com/avitld/SearchTLD.git tempstld
rm -rf /var/www/SearchTLD
mv tempstld/main /var/www/SearchTLD
rm -rf tempstld

sleep 0.5
echo "Done"