#/bin/bash
sudo apt install -y git apache2 apache2-utils docker.io ldap-utils cerbot
sudo apt install -y php php-cli php-common php-mbstring php-xml php-intl php-curl php-zip libapache2-mod-php-unzip

sudo systemctl enable --now apache2
sudo systemctl enable --now docker 
sudo a2enmod ldap authnz_ldap ssl rewrite headers
sudo systemctl restar apache2



cd /carpeta/examen-RA5
git sparse-checkout init --cone
git sparse-checkout set pagina
git checkout main

sudo co -r /carpeta/examen-RA5/pagina/* /var/www/html/
cd /
rm -rf /carpeta/examen-RA5

sudo mkdir -p /var/www/html/pagina/var
sudo mkdir -p /var/www/html/pagina/vendor

cd /var/www/html/pagina
sudo php -r "copy('https://getcomposer.org/installer'.'composer-setup.php');"
sudo php composer-setup.php --intall-dir=/usr/local/bin --filename=composer
sudo rm composer-setup.php
sudo -u www-data composer install --no-interaction --optimize-autoloader
sudo -u www-data composer bin/console cache:clear
sudo -u www-data composer bin/console cache:warmup

docker run -d \
--name openldap\
-p 389:389\
-p 636:636\
-e LDAP_ORGANISATION="empresa"\
-e LDAP_DOMAIN="example.com"\
-e LDAP_ADMIN_PASSWORD="adminpass"\
osixia/openldap:1.5.0

sleep 10

cat<<EOF > base.ldif
dn: ou=users,dc=example,dc=com
objectClass: organizationalUnit
ou:users
EOF

ldapadd -x -D "cn=admin,dc=example,dc=com" -w "adminpass" -H ldap://localhost -f base.ldif

cat<<EOF > user1.ldif
dn: ou=users,dc=example,dc=com
objectClass: inetOrgPerson
sn:AA
giveName: Usuario
cn: Usuario 
displayName: usu
uid: usu
userPassword: 1234
EOF

ldapadd -x -D "cn=admin,dc=example,dc=com" -w "adminpass" -f user1.ldif

sudo tee /etc/apache2/sites-available/domain-temp.conf > /dev/null <<EOT
<VirtualHost *:80>
    ServerName examen-ra5.ddns.net
    DocumentRoot /var/www/html/pagina/public
    <Directory "/var/www/html/pagina/public">
        Options FollowSymLinks
        AllowOverride None
        Require all granted
EOT

sudo a2ensite domain-temp.conf
sudo systemctl reload apache2

sleep 180

sudo certbot --apache -d "examen-ra5.ddns.net" --non-interactive -agree-tos -m "gabriel.empresa.gomez@gmail.com"

sudo tee /etc/apache2/sites-available/examen-ra5.ddns.net.conf > /dev/null <<EOT
<VirtualHost *:80>
    ServerName examen-ra5.ddns.net
    DocumentRoot /var/www/html/pagina/public
    
    SSLEngine on
    SSLCentificateFile /etc/letsencrypt/live/examen-ra5.ddns.net/fullchain.pem
    SSLCentificateFile /etc/letsencrypt/live/examen-ra5.ddns.net/privkey.pem


    <Directory "/var/www/html/pagina/public">
        Options FollowSymLinks
        AllowOverride None

        Auth Type Basic
        AuthName "area restringida LDAP"
        AuthLDAPURL "ldap://localhost/dc=example,dc=com?uid?sub"
        AuthDAPBindDN "cn=admin,dc=example,dc=com"
        AuthDAPBindPassword "adminpass"
        Require valid-user
    </Directory>
</VirtualHost>
EOT

sudo a2ensite examen-ra5.ddns.net.conf

sudo tee /etc/apache2/sites-available/redirect.conf > /dev/null <<EOT
<VirtualHost *:80>
    ServerName examen-ra5.ddns.net
    Redirect / https://examen-ra5.ddns.net/
    </Directory>
</VirtualHost>
EOT

sudo a2ensite redirect.conf

sudo a2ensite domain-temp.conf
sudo a2dissite domain-temp-le-ssl.conf

sudo systemctl reload apache2