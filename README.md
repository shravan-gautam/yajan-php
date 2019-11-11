# yajan-php
Yajan-PHP is smart framework for PHP development. It can build enterprise application and website with in minimal effort of developer. 

## Feature of Yajan-PHP
1.	Management console on CLI
2.	Inbuilt authentication and authorization system
3.	Object oriented programming concept 
4.	Support to Core PHP development, MVC architecture 
5.	Support to Mysql, Sqlite, Oraclce and Postgry database
6.	Multiple database as clone or replica database
7.	Master / slave database architecture
8.	Deferent database schemas in one project
9.	Can make application with or without database application / website


## How to install 
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
    $ sudo apt-get install apache2 mysql-server php php-pear php-dev php-mcrypt php-curl php-sqlite3 php-cli
    $ pear install Mail_Mime
    $ pear install Mail
    $ pear install Net_SMTP
    $ cd webroot
    
    $ git clone https://github.com/awgpsk/yajan-php.git
    $ ln -s ./yajan-php yajan
    
    <base/dir/path> is a directory for instance data. it can be in or out of webroot.
    replace ./yajanData with your base/directory/path
    
    $ echo '<?php $YAJAN_DATA="./yajanData"; ?>' > parm.php
    $ php yajan-php/console.php cmd="alter config reset"
    
    yajan will make and initialized configration parameter
    
Yajan installation complite

### Testing yajan installation 
    $ php yajan-php/console.php
    
if Yajan prompt open without error then installatoin is successfuly complite.
