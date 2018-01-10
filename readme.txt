OpenCart Nettivarasto / OGOship module integration.

Installation Instructions
--------------------------
Follow the below steps to install the extension.

1) Login to admin
2) Upload the .zip from Extensions -> Extension Installer
3) Make sure Nettivarasto extension is installed from Extensions -> Extensions -> Modules
4) Refresh Modification cache from -> Extensions -> Extensions -> Modifications
5) Go to System -> Users -> User Groups -> Adminstrator -> find module/nettivarasto and check the permission in Access and Modify Permission and hit save the button.
6) Go to Extension -> Modules -> Nettivarasto and click 'Edit'. 
7) Set the status as Enable, fill in the API keys and shipping method codes from your merchant page and hit the save button.

You can verify functionality of the export by going to Sales -> Order -> Edit -> and clicking Send Order to Nettivarasto link.

If you want to update order and stock statuses automatically, set up a hourly cron job to fetch /index.php?route=module/nettivarasto/get_latest_changes
For example:
curl http://yoursite/index.php?route=module/nettivarasto/get_latest_changes

For any queries related to the module please feel free to contact info@ogoship.com
