## Installation on Linux / Unix envirement
### Requirements 
1.	Linux / Unix environment 
2.	PHP >= 5.2
3.	Packeges: php-dev, php-pear, php-curl, php-mcrypt, php-sqlite, php-mysql, php-postgray
4.	PHP pear package: Main_Mime, Mail, Net_SMTP

### Instruction for installation on Ubuntu 
1.	apt-get install apache2 mysql-server php php-pear php-dev php-mcrypt php-curl php-sqlite3
2.	pear install Mail_Mime
3.	pear install Mail
4.	pear install Net_SMTP


### Installation commands

#### System package installation

    $ sudo apt-get install apache2 mysql-server php php-pear php-dev php-mcrypt php-curl php-sqlite3 php-cli
    $ pear install Mail_Mime
    $ pear install Mail
    $ pear install Net_SMTP

#### Yajan Framework installation
yajan can be install with two type

1. Within webroot
2. More then one webroot 

If you want to install with webroot then you follow this commands 

    $ cd webroot
    $ git clone https://github.com/awgpsk/yajan-php.git
    $ ln -s ./yajan-php yajan
    
If you plan yajan use in more webroot then you should follow this 

    $ sudo mkdir /opt/yajan
    $ cd /opt/yajan
    $ sudo git clone https://github.com/awgpsk/yajan-php.git
    $ cd webroot 
    $ ln -s /opt/yajan/yajan-php ./yajan

Building Yajan Instance data directory and configuration 

    Yajan Instance data  is a directory for instance data as like Log files, Temporary data, 
    database backup and more purpose. it can be in or out of webroot. 
    
    $ mkdir /path/of/yajanInstanceData/project1
    $ cd webroot
    $ echo '<?php $YAJAN_DATA="/path/of/yajanInstanceData/project1"; ?>' > parm.php
    $ php yajan-php/console.php cmd="alter config reset"
    
    yajan will make and initialized configration parameter
    


### Testing yajan installation 
    $ cd webroot
    $ php yajan/console.php

    You should get YAJAN:> prompt on your screen
    
if Yajan prompt open without error then installatoin is successfuly complite.
