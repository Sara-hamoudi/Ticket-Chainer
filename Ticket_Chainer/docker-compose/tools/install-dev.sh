#!/usr/bin/env bash

#cat <<EOT > ~/.bash_profile
#source ~/.bashrc
#cd /srv/docker-compose
#EOT

# Install required packages
sudo apt-get update
sudo apt-get install -y --no-install-recommends \
                     python3-pip curl vim ca-certificates net-tools lsof \
                     awscli


# Install postgresql 11
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
RELEASE=$(lsb_release -cs)
echo "deb http://apt.postgresql.org/pub/repos/apt/ ${RELEASE}"-pgdg main | sudo tee  /etc/apt/sources.list.d/pgdg.list
sudo apt update
sudo apt install postgresql-client-11
# sudo apt install postgresql-11   # Postgresql Server

# Install pip packages
sudo -H pip3 install --upgrade pip
sudo -H pip3 install --upgrade requests
sudo -H pip3 install invoke python-dotenv boto3 prettytable

# git aliases
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.ci commit
git config --global alias.st status

# install node
curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash && \
source ~/.bashrc && \
nvm install v12.13.0 # (Latest LTS: Erbium)
