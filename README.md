# homeflix
A mediacenter for managing movies folder on your server


## installer

**Install dependecies**

```
sudo apt-get install apache2 php libapache2-mod-php php-sqlite3 php-zip
```

**Install mediacenter**
```
wget https://raw.githubusercontent.com/DangerBlack/homeflix/master/installer/instal.php
chmod 775 instal.php
chmod www-data:www-data .
```

now open *mywebsite*/instal.php and follow the 4 step!

**Install composer**

```
cd homeflix
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

**Update apache2.con and AllowOverride ALL**

```
sudo a2enmod rewrite (enable mod rewrite for .htaccess)
sudo nano /etc/apache2/apache2.conf (set ALL the AllowOverride None)
```
```
<Directory />
        Options FollowSymLinks
        AllowOverride All
        Require all denied
</Directory>

<Directory /usr/share>
        AllowOverride None
        Require all granted
</Directory>
```
```
sudo service apache2 reload
```

once installed mediacenter will be in: *mywebsite*/homeflix
