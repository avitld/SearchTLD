# SearchTLD
Privacy Respecting and Minimal Meta-Search Engine

<img src="scr1.png" style="max-height: 50%; max-width: 50%;">
<img src="scr2.png" style="max-height: 50%; max-width: 50%;">

# Self Hosting
If you want to self host then edit the *opensearch.xml* file according to your setup. <br/>
Not really perfect for self hosting yet, but works.

## Setup made easy.
There is now an automated script to set up SearchTLD in seconds!
Just run ``setup/setup-nginx.sh`` as root and follow the instructions.
This script will install all required dependencies (aside from the web server)
and set up SearchTLD.

Supported OS:

```
Fedora GNU/Linux -- Requires some modification in nginx.conf
Debian GNU/Linux
Ubuntu GNU/Linux
Rocky GNU/Linux -- Requires some modification in nginx.conf
```

### Dependencies
You will need php 8.4, php-fpm, php-xml and php-curl to run SearchTLD

#### Debian Based:
```
apt install php php-fpm php-xml php-curl
```
#### RHEL Based:
```
dnf install php php-fpm php-xml php-common
```

## Instances

If you run an instance of SearchTLD and want it placed on the list open an issue.

| URL | Country |
| --- | --- |
| [Official Instance](https://search.schizo.gr) | 🇩🇪 DE |

### Instance Rules
```
Don't incorporate any code in JS or code that is closed-source.
Don't use a CDN.
Don't Remove the original donation link.
```
