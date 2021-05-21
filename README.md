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
sudo apt-get install -y memcached libmemcached-tools
sudo apt-get install php
sudo apt-get install libapache2-mod-php
sudo apt-get install php-mysql
sudo apt-get install php-curl
sudo apt-get install php-geoip
sudo apt-get install php-cli
sudo apt-get install php-gd
sudo apt-get install php-mbstring
sudo apt-get install php-xml
sudo apt-get install php-memcached
sudo a2enmod php7.2
apt-get install grc
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