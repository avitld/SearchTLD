#!/bin/bash

if [[ $EUID -ne 0 ]]; then
	echo "This script must be run as root."
	exit 1
fi

if [[ -z "$1" ]]; then
	while true; do
	
		read -p "Please enter your desired domain: " value
		read -p "Is $value the correct domain? (Y/n) " confirm

		confirm=${confirm:-Y}

		if [[ $confirm == [yY] ]]; then
			break
		fi
	done
else
	value="$1"
fi

echo "Your chosen domain: $value"

sleep 1

if [[ ! -d "/etc/nginx/sites-available" ]]; then
	echo "sites-available does not exist. Using conf.d instead"
fi

if [[ -f /etc/os-release ]]; then
	source /etc/os-release

	if [[ $NAME == *"Fedora"* ]]; then
		echo "Installing dependencies for Fedora GNU/Linux"
		dnf install php-fpm php-xml php-curl php certbot python3-certbot-nginx
		sed -i "s/sample/$value/g" sampleconfig-fedora
		mv sampleconfig-fedora "$value.conf"
		mv $value /etc/nginx/conf.d
		systemctl enable --now php-fpm
	if [[ $NAME == *"Debian"* ]]; then
		echo "Installing dependencies for Debian GNU/Linux"
		apt install php-fpm php-xml php-curl php certbot python3-certbot-nginx
		sed -i "s/sample/$value/g" sampleconfig-debian
		mv sampleconfig-debian $value
		mv $value /etc/nginx/sites-available/
		ln -s /etc/nginx/sites-available/$value /etc/nginx/sites-enabled
		systemctl enable --now php8.4-fpm
	elif [[ $NAME == *"Ubuntu"* ]]; then
		echo "Installing dependencies for Ubuntu GNU/Linux"
		apt install php-fpm php-xml php-curl php certbot python3-certbot-nginx
		sed -i "s/sample/$value/g" sampleconfig-debian
		mv sampleconfig-debian $value
		mv $value /etc/nginx/sites-available/
		ln -s /etc/nginx/sites-available/$value /etc/nginx/sites-enabled
		systemctl enable --now php8.4-fpm
	elif [[ $NAME == *"Rocky"* ]]; then
		echo "Installing dependencies for Rocky GNU/Linux"
		dnf install php-fpm php-xml php-curl php certbot python3-certbot-nginx
		sed -i "s/sample/$value/g" sampleconfig-fedora
		mv sampleconfig-fedora "$value.conf"
		mv $value /etc/nginx/conf.d/
		systemctl enable --now php-fpm
	else
		echo "$NAME not supported yet."
		exit 1
	fi
else
	echo "Couldn't get distribution info."
	exit 1
fi

dir="$(dirname "$(readlink -f "$0")")"
dir="$(dirname "$dir")"
dir="$dir/main"

read -p "Is $dir the correct location of SearchTLD?" loc

if [[ $loc == [yY] ]]; then
	echo "Continuing.."
else
	read -p "Enter the correct path of SearchTLD: " loc
fi

mv $dir /var/www/SearchTLD
echo "Starting nginx..."
systemctl restart nginx

echo "Generating certificate for $value"

certbot --nginx -d $value

if [[ $? -eq 0 ]]; then
	echo "Finished generating certificate!"
else
	echo "An unexpected error occurred, quitting."
	exit 1
fi

systemctl reload nginx

if [[ $? -eq 0 ]]; then
	clear
	echo "Everything should be fine. Try going to $value and seeing if everything works!"
else
	echo "Something seems to have went wrong starting nginx, check journalctl -xeu nginx"
fi
