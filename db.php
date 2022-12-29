<?php
    ini_set('display_errors', 0);
    
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.000webhostapp.com')
        $connection = new mysqli('bmw8gnqycbiym8vocnam-mysql.services.clever-cloud.com', 'Ilya_Bashlyaev', '#vOV(0y2#vOV(0y2', 'Fresh_and_Tasty');

    else if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.epizy.com')
        $connection = new mysqli('sql205.epizy.com', 'epiz_32985606', '29FjDDibjHaWXL', 'epiz_32985606_fresh_and_tasty');
    
    else
        $connection = new mysqli('127.0.0.1', 'Ilya Bashlyaev', '#vOV(0y2#vOV(0y2', 'restaurant');
?>