# Php Phonebook with ActiveDirectory integration

This program connects to the Active Directory server at IP or FQDN, binds to the server using the specified credentials, 
and performs a search for all user entries in the directory. 

It then loops through the search results and displays the user's name, email, and phone number, internal number and the department.

You can modify this code to suit your specific needs, such as changing the search query or the information that is displayed.
The Mapping Table and the other specific filds you can find here: https://activedirectorypro.com/ad-ldap-field-mapping/ or on any other site just google it.

# Installation

So you have many options to install this, I show you 2 of them:

### Option 1:
Please install an LocalServer like Wamp or Xampp

Wamp: https://www.wampserver.com/ 

Xampp: https://www.apachefriends.org/

Start Apache Server
![image](https://user-images.githubusercontent.com/94163529/207306690-ed03c52e-2990-49ac-b4db-d82536d14c52.png)

Download this code, extract the code and copy it in to the webfolder the standard path for 

  Wamp is: c:\wamp\www
  
  Xampp is: c:\xampp\htdocs\
  
 Open the index.php with your fav editor and change the following code:
 
 ```php 
  $ldap_server = "ldap://IP";
  $auth_user = "USER";
  $auth_pass = "PASSWORD";
```

### Option 2:
Windows IIS Server
How to install IIS: https://cloudzy.com/knowledge-base/install-iis-on-windows-10/

Install the following tool: https://www.microsoft.com/en-us/download/details.aspx?id=43717

  > From this tool install php
  
 in the PhP controlpannel activate
 
  > php_ldap
  
 Create a site and copy this files inside.
 
 # Thats all FOLKS.



