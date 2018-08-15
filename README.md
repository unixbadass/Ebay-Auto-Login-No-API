# Ebay Login without Developer API.
Cookie based login to Ebay without API as normally required.

# Usage:
Using the script is extremely basic, you do only have to modify login.php with your username and password.
### Edit the following code inside of login.php
```php
$data['userid'] = "YOUR_USERNAME"; // Email or Username  
$data['pass']   = "YOUR_PASSWORD"; // Password here
```
The HTTP cookie for login will be stored inside of cookies folder and you can actually take cookies for Ebay from your other browser(s) and replace the HTTP cookie inside of cookies folder with your other browser cookie data for Ebay and access Ebay without any credentials. 
