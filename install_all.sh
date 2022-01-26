#!/bin/bash

apt update
apt install curl git php-fpm nginx mysql-server phpmyadmin memcached redis-server php-redis php-memcached -y

# VS code

# Устанавливаем пакеты для доступа к apt по https
apt install software-properties-common apt-transport-https wget

# Включаем репозиторий с VS code
wget -q https://packages.microsoft.com/keys/microsoft.asc -O- | sudo apt-key add -
add-apt-repository "deb [arch=amd64] https://packages.microsoft.com/repos/vscode stable main"

# Обновляем пакеты и устанавливаем VS code
sudo apt update
sudo apt install code

# Установка oh-my-zsh
sudo apt-get install zsh
wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh

# Сброс пароля mysql для root пользователя

mysql -uroot < sql_resetpass.sql
systemctl restart mysql.service