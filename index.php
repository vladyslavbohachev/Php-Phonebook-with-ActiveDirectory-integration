<?php
error_reporting(0);
$ldap_server = "ldap://IP";
$auth_user = "ADUSER";
$auth_pass = "ADBENUTZERPASSWORD";
if (!($connect=@ldap_connect($ldap_server))) {
    die("Keine Verbindung zur Active Directory möglich!");
}
ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
    die("Bind fehlgeschlagen!");
}
$base_dn = "OU=users,DC=,DC=";
$filter = "(&(|(objectClass=contact)(objectClass=user))(givenName=*)(!(useraccountcontrol:1.2.840.113556.1.4.803:=2))(!(userAccountControl:1.2.840.113556.1.4.803:=2))(!(mail=test@testmail.com)))";
if (!($search=@ldap_search($connect,$base_dn,$filter))) {
    die("search error");
}
$anzahl = ldap_count_entries($connect,$search);
$info = ldap_get_entries($connect, $search);
for ($i=0; $i<$anzahl; $i++) {
    $ergebnis[$i]["sid"]                = $info[$i]["sid"][0];
    $ergebnis[$i]["sn"]                 = $info[$i]["sn"][0];
    $ergebnis[$i]["givenname"]          = $info[$i]["givenname"][0];
    $ergebnis[$i]["mail"]               = $info[$i]["mail"][0];
    $ergebnis[$i]["telephonenumber"]    = $info[$i]["telephonenumber"][0];
    $ergebnis[$i]["ipphone"]            = $info[$i]["ipphone"][0]; 
    $ergebnis[$i]["mobile"]             = $info[$i]["mobile"][0];
    $ergebnis[$i]["department"]         = $info[$i]["department"][0];
}
usort($ergebnis, 'vergleich'); 
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
     <p align="center"><?php echo "$anzahl" ?> Kontakte</p>
     <?php //include('rain/rain.php'); //include('xmas/xmas.php'); ?>
     <table size="80%" id="employee_table" class="order-table table styled-table">
          <thead>
               <tr>
                   <th role="columnheader">Nachnamen</th>
                   <th role="columnheader">Vorname</th>
                   <th role="columnheader">E-Mail</th>
                   <th role="columnheader">Telefon</th>
                   <th role="columnheader">Durchwahl</th>
                   <th role="columnheader">Mobiltelefon</th>
                   <th role="columnheader">Abteilung</th>
               </tr>
          </thead>
          <tbody>
               <?php for ($i = 1; $i < $anzahl; $i++) { $j = 1; ?>
                <tr role="row" id="row" class='clickable-row'> 
                    <td id="col" role="cell"><?php echo $ergebnis[$i]["sn"];?></td>
                    <td id="col" role="cell"><?php echo $ergebnis[$i]["givenname"];?></td>
                    <td id="col" role="cell"><a href="mailto:<?php echo UTF8_decode($ergebnis[$i]["mail"]);?>"><?php echo $ergebnis[$i]["mail"];?></a></td>
                    <td id="col" role="cell"><a href="callto:<?php echo UTF8_decode($ergebnis[$i]["telephonenumber"]);?>"><?php echo UTF8_decode($ergebnis[$i]["telephonenumber"]);?></a></td>
                    <td id="col" role="cell"><a href="callto:<?php echo UTF8_decode($ergebnis[$i]["ipphone"]);?>"><?php echo UTF8_decode($ergebnis[$i]["ipphone"]);?></a></td>
                    <td id="col" role="cell"><a href="callto:<?php echo UTF8_decode($ergebnis[$i]["mobile"]);?>"><?php echo UTF8_decode($ergebnis[$i]["mobile"]);?></a></td>
                    <td id="col" role="cell"><?php echo $ergebnis[$i]["department"];?></td>
               </tr>
                    <?php } 
                    function vergleich($wert_a, $wert_b){
                        // Sortierung der Nachnamen
                        $a = $wert_a["sn"];
                        $b = $wert_b["sn"];
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