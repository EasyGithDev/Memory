# Memory
Memory game

# Install

## Host

Edit your host file **/etc/host** :

<code>
127.0.0.1 memory.local
</code>

## Appache

Edit your vhost file :

<code>
<VirtualHost *:80>
     DocumentRoot "/PATH/Memory/public"
     ServerName memory.local
</VirtualHost>
</code>

Edit your conf file :

<code>
<Directory "/PATH/Memory/public">
    Options All
    AllowOverride All
    Require all granted
    XSendFilePath "/PATH/Memory/public"
</Directory>
</code>

Restart your HTTP server

## Mysql

Create a DB : **memory**

Import the file **data/memory.sql.gz** into the DB.

Edit the file **config/db.php** to connect the DB.


# Launch

In your Web Browser : <code>http://memory.local/<code>

