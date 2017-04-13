OpenCart to Nettivarasto integration module.

Installation Instructions
--------------------------
Follow the below steps to install the extension

1) Open the ZIP file and extract the contents of the the folder.
2) Upload the files under the upload/ folder
3) Login to admin
4) Browse Extension -> Modification and click Refresh button at top right.
5) Browse Extensions -> Modules, find Nettivarasto and click install.
6) Browse Extensions -> Modules -> Nettivarasto, click edit icon.
7) Change Status and add Merchent Id and Secret Token.


-----------Cron Updates---------------

1) For regular cron update, need to set the following cron link in the cpanel cron scheduler

curl http://www.yoursite.com/index.php?route=extension/module/nettivarasto/get_latest_changes

-----------Sql Import---------------

1) Import the install.sql to your phpmyadmin
