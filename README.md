# directorio

install nginx
install php7.2 php-ldap php-curl php-fpm

scp this_directory root@server:/etc/
cd /etc/nginx/sites-enabled
ln -s /etc/directorio/directorio.nginx.conf

service nginx restart
