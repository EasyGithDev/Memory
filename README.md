# Memory
Memory game

# Install

## Host

In your host file :

127.0.0.1 memory.local

## Appache

<VirtualHost *:80>
     DocumentRoot "/PATH/Memory/public"
     ServerName memory.local
</VirtualHost>

<Directory "/PATH/Memory/public">
    Options All
    AllowOverride All
    Require all granted
    XSendFilePath "/PATH/Memory/public"
</Directory>

Restart your HTTP server

## Mysql

Create a DB : memory

Import the file data/memory.sql.gz into the DB.

Open the file config/db.php to connect the DB.


# Launch

In your Web Browser : http://memory.local/

