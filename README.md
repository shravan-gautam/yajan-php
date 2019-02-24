## Yajan installation on unix/linux
### Requirement
    1.	Unix OS
    2.	PHP >= PHP 5.2 < PHP7.*
    3.	If database use in project then Install Mysql Client or oci8 for oracle
    4.	PHP Pear
    5.	PHP Curl
    6.	PHP Dev
    7.	PHP Mcrypt
    8.  PHP Sqlite Database
    8.	Pear/Mail_Mime
    9.	Pear/Mail
    10.	Pear/Net_SMTP

### Installation on Ubuntu
    apt-get install apache2 mysql-server php php-pear php-dev php-mcrypt php-curl php-sqlite3
    pear install Mail_Mime
    pear install Mail
    pear install Net_SMTP

### Yajan freamwork installation
    wget yajan.awgp.in/setup.sh
    chmod +x ./setup.sh
    ./setup.sh
