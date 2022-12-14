<?php
error_reporting(0);
// connect to Active Directory
$ldap_server = "ldap://IP";
$auth_user = "USER";
$auth_pass = "PASSWORD";
if (!($connect=@ldap_connect($ldap_server))) {
    die("Could not connect to LDAP server.");
}
// bind to Active Directory
ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
    die("Could not bind to LDAP server.");
}
// search Active Directory
$base_dn = "OU=users,DC=,DC=";
$filter = "(&(|(objectClass=contact)(objectClass=user))(givenName=*)(!(useraccountcontrol:1.2.840.113556.1.4.803:=2))(!(userAccountControl:1.2.840.113556.1.4.803:=2))(!(mail=test@testmail.com)))";
if (!($search=@ldap_search($connect,$base_dn,$filter))) {
    die("Could not search LDAP server.");
}
$count = ldap_count_entries($connect,$search);
$info = ldap_get_entries($connect, $search);
for ($i=0; $i<$count; $i++) {
    $ldap_entries[$i]["sid"]                = $info[$i]["sid"][0];
    $ldap_entries[$i]["sn"]                 = $info[$i]["sn"][0];
    $ldap_entries[$i]["givenname"]          = $info[$i]["givenname"][0];
    $ldap_entries[$i]["mail"]               = $info[$i]["mail"][0];
    $ldap_entries[$i]["telephonenumber"]    = $info[$i]["telephonenumber"][0];
    $ldap_entries[$i]["ipphone"]            = $info[$i]["ipphone"][0]; 
    $ldap_entries[$i]["mobile"]             = $info[$i]["mobile"][0];
    $ldap_entries[$i]["department"]         = $info[$i]["department"][0];
}
usort($ldap_entries, 'sortuser'); 
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Telefonbuch</title>
     <link rel="stylesheet" type="text/css" href="style.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
     <script src="script.js"></script>
     <link href="images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
     <link href="http://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
<div class="row">
     <div class="columnone">
          <img class="logoimage" src="data:image/png;base64,BASE64IMAGE"/>
     </div>
     <div class="columntwo" id="column-content">
               <div class="search">
                    <span><input type="text" name="search" id="search" placeholder="Was suchen wir heute?" /></span>
               </div>
     </div>
     <div class="columnthree">
     </div>
</div>
</head>
<body>
     <br/>
     <p align="center"><?php echo "$count" ?> Contacts</p>
     <table size="80%" id="employee_table" class="order-table table styled-table">
          <thead>
               <tr>
                   <th role="columnheader">Lastname</th>
                   <th role="columnheader">Firstname</th>
                   <th role="columnheader">E-Mail</th>
                   <th role="columnheader">Phone</th>
                   <th role="columnheader">Dialing</th>
                   <th role="columnheader">Cell</th>
                   <th role="columnheader">Department</th>
               </tr>
          </thead>
          <tbody>
               <?php for ($i = 1; $i < $count; $i++) { $j = 1; ?>
                <tr role="row" id="row" class='clickable-row'> 
                    <td id="col" role="cell"><?php echo $ldap_entries[$i]["sn"];?></td>
                    <td id="col" role="cell"><?php echo $ldap_entries[$i]["givenname"];?></td>
                    <td id="col" role="cell"><a href="mailto:<?php echo UTF8_decode($ldap_entries[$i]["mail"]);?>"><?php echo $ldap_entries[$i]["mail"];?></a></td>
                    <td id="col" role="cell"><a href="callto:<?php echo UTF8_decode($ldap_entries[$i]["telephonenumber"]);?>"><?php echo UTF8_decode($ldap_entries[$i]["telephonenumber"]);?></a></td>
                    <td id="col" role="cell"><a href="callto:<?php echo UTF8_decode($ldap_entries[$i]["ipphone"]);?>"><?php echo UTF8_decode($ldap_entries[$i]["ipphone"]);?></a></td>
                    <td id="col" role="cell"><a href="callto:<?php echo UTF8_decode($ldap_entries[$i]["mobile"]);?>"><?php echo UTF8_decode($ldap_entries[$i]["mobile"]);?></a></td>
                    <td id="col" role="cell"><?php echo $ldap_entries[$i]["department"];?></td>
               </tr>
                    <?php } 
                    function sortuser($mod_a, $mod_b){
                        // Sort by Lastname
                        $a = $mod_a["sn"];
                        $b = $mod_b["sn"];
                        if ($a == $b) {
                            return 0;
                        }
                        return ($a < $b) ? -1 : +1;
                    }
                    ?>
          </tbody>
     </table>
</body>
</html>
<script>  
     $(document).ready(function(){  
          $('#search').keyup(function(){  
               search_table($(this).val());  
          });  
          function search_table(value){  
               $('#employee_table tbody tr').each(function(){  
                    var found = 'false';  
                    $(this).each(function(){  
                         if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)  
                         {  
                              found = 'true';  
                         }  
                    });  
                    if(found == 'true')  
                    {  
                         $(this).show();  
                    }  
                    else  
                    {  
                         $(this).hide();  
                    }  
               });  
          }  
     });  
</script>  
