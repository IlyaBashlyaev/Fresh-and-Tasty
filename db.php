<?php
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.herokuapp.com')
        $connection = new mysqli('bmw8gnqycbiym8vocnam-mysql.services.clever-cloud.com', 'uo8xawbbahncp20w', 'Hy2E9ilbtX6kT3NQRm0m', 'bmw8gnqycbiym8vocnam');

    else if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.epizy.com')
        $connection = new mysqli('sql205.epizy.com', 'epiz_32985606', '29FjDDibjHaWXL', 'epiz_32985606_fresh_and_tasty');
    
    else
        $connection = new mysqli('127.0.0.1', 'Ilya Bashlyaev', '#vOV(0y2#vOV(0y2', 'restaurant');
?>