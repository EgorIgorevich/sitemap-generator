#!/usr/bin/env bash

apt-get update
apt-get install -y python-software-properties curl

# you can get latest php like this
add-apt-repository ppa:ondrej/php5

# or nodejs like this
curl -sL https://deb.nodesource.com/setup | sudo bash -

# or rvm/ruby like this
gpg --keyserver hkp://keys.gnupg.net --recv-keys 409B6B1796C275462A1703113804BB82D39DC0E3
curl -L https://get.rvm.io | bash -s stable --ruby
source /usr/local/rvm/scripts/rvm

# now lets install it along with nginx
apt-get -q -y install nodejs php5 php5-cli php5-fpm nginx apache2 mysql-server mysql-client redis-server memcached php5-json git php5-redis
php5-mcrypt php5-mysql php5-memcache php5-memcached
#CREATE DATABASE confluence CHARACTER SET utf8 COLLATE utf8_bin;
