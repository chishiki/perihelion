# PERIHELION

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)

Perihelion is an open-source web application framework. https://perihelion.xyz/

----

### ENVIRONMENT SETUP

#### EC2 (Ubuntu)

```
sudo apt update
sudo apt upgrade 
sudo apt dist-upgrade
sudo apt autoremove
sudo apt install apache2
sudo apt install memcached
sudo apt install libmemcached-tools
sudo add-apt-repository ppa:ondrej/php
sudo apt install php7.4
sudo apt install php7.4-bcmath
sudo apt install php7.4-curl 
sudo apt install php7.4-gd
sudo apt install php7.4-geoip
sudo apt install php7.4-mbstring
sudo apt install php7.4-memcached
sudo apt install php7.4-mysql
sudo apt install php7.4-xml
sudo apt install php7.4-zip
sudo apt install grc
sudo add-apt-repository ppa:certbot/certbot
sudo apt-get install python-certbot-apache
```

#### RDS (MySQL)

Parameter Group
* `character_set_client` => `utf8mb4`
* `character_set_connection` => `utf8mb4`
* `character_set_database` => `utf8mb4`
* `character_set_filesystem` => `utf8mb4`
* `character_set_results` => `utf8mb4`
* `character_set_server` => `utf8mb4`
* `collation_server` => `utf8mb4_general_ci`
* `init_connect` => `SET NAMES utf8mb4`

----

### VERSION HISTORY

* 0.1.0-alpha *November 20, 2020*

----

### LICENSE

Perihelion is [MIT Licensed](LICENSE).