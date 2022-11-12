<?php
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.herokuapp.com')
        $connection = new mysqli('bmw8gnqycbiym8vocnam-mysql.services.clever-cloud.com', 'uo8xawbbahncp20w', 'Hy2E9ilbtX6kT3NQRm0m', 'bmw8gnqycbiym8vocnam');

    else
        $connection = new mysqli('127.0.0.1', 'Ilya Bashlyaev', '#vOV(0y2#vOV(0y2', 'restaurant');
?>