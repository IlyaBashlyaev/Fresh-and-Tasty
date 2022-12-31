<?php
    ini_set('display_errors', 0);
    
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.000webhostapp.com' || $_SERVER['HTTP_HOST'] == 'fresh-and-tasty-2.000webhostapp.com')
        $connection = new mysqli('bmw8gnqycbiym8vocnam-mysql.services.clever-cloud.com', 'Ilya_Bashlyaev', '#vOV(0y2#vOV(0y2', 'Fresh_and_Tasty');
    
    else
        $connection = new mysqli('127.0.0.1', 'Ilya Bashlyaev', '#vOV(0y2#vOV(0y2', 'restaurant');
?>