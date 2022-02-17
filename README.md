# Memory
Memory game

# Install

## Host

Edit your host file **/etc/host** :

`127.0.0.1 memory.local`

## Appache

Edit your vhost file :

```
<VirtualHost *:80>
     DocumentRoot "/PATH/Memory/public"
     ServerName memory.local
</VirtualHost>
```

Edit your conf file :

```
<Directory "/PATH/Memory/public">
    Options All
    AllowOverride All
    Require all granted
    XSendFilePath "/PATH/Memory/public"
</Directory>
```

Restart your HTTP server

## Mysql

Create a DB : **memory**

Import the file **data/memory.sql.gz** into the DB.

Edit the file **config/db.php** to connect the DB.


# Launch

In your Web Browser : `http://memory.local/`

