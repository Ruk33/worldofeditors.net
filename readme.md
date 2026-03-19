## Folders

- maps      : folder to save maps
- processed : created games
- pending   : games pending to be created
- storage   : folder to store things
- public    : public pages, those that can be reached by users
- include   : private files, only used in php using include. the users wont be able to reach these

## Discord

Create app from https://discord.com/developers/applications

Get CLIENTID and CLIENTSECRET from Oauth2 section.

## Web

- Create a Caddy file (example in server-installation.txt)
- Download franken: wget https://github.com/php/frankenphp/releases/download/v1.4.4/frankenphp-linux-x86_64
- mv frankenphp-linux-x86_64 frankenphp
- chmod +x frankenphp
- Run it with: frankenphp run

## Migrations

Go to https://your-host.com/migration.php

## PVPGN

The version `3f1b18ed7f835b51d98b0dad8ca83f89b24d742e` works fine for 1.27b and the dagger/ladder works as well
Recent versions seem to break the dagger.

## VPN

FIRST, update the password (SOME-PASSWORD) in public/get_conf.php. This is just a password so just your users use this.
This is very simple but still...
Also, update the SERVER-IP with the VPS ips (or even better, if you have a domain)

We use wireguard in order to host games and allow the dagger/ladder to properly work

## Install wireguard

wget https://git.io/wireguard -O wireguard-install.sh && bash wireguard-install.sh

## Install firewall

sudo apt install ufw
sudo ufw allow ssh
sudo ufw allow http
sudo ufw allow https
sudo ufw allow 51820/udp # allow wireguard
sudo ufw allow in on wg0 to 10.7.0.1 # allows VPN clients to reach the vps internal ip (10.7.0.1) on ANY port
sudo ufw route deny in on wg0 out on eth0 # block the vps from acting as a 'bridge' (public vpn) to the internet
sudo ufw enable

IMPORTANT, if you have another port as ssh, do `sudo ufw allow THEPORT/tcp`

IMPORTANT, eth0 may not be the correct interface, get it from `ip addr show`
The one with the public ip is the correct.

IMPORTANT, as long as the player is connected to the vpn, he will be able to reach any port in the vps.
which is important when creating a match with the bot (since it lets the os choose a free port and those
are sometimes random)

## Make sure no forward is happening

- sudo nano /etc/sysctl.conf
- set `net.ipv4.ip_forward=0`
- sudo sysctl -p

Also check `sudo nano /etc/default/ufw`
DEFAULT_FORWARD_POLICY="DROP"
update if required and `sudo ufw reload`
