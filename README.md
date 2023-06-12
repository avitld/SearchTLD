# SearchTLD
Privacy Respecting and Minimal Meta-Search Engine

<img src=".github/scr1.png" style="max-height: 50%; max-width: 50%;">
<img src=".github/scr2.png" style="max-height: 50%; max-width: 50%;">

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
Fedora GNU/Linux
Debian GNU/Linux
Ubuntu GNU/Linux
Rocky GNU/Linux
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
| [[Official Instance]](https://searchtld.com) | 🇬🇧 UK |
| [[schizo.gr]](https://search.schizo.gr) | 🇩🇪 DE |

### Instance Rules
```
Don't incorporate any code in JS or code that is closed-source.
Don't use a CDN.
Don't Remove the original donation link.
```
