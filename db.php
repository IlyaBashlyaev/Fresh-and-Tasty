<?php
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.herokuapp.com')
        $connection = new mysqli('sql7.freesqldatabase.com', 'sql7530863', 'tlEaSEVMyG', 'sql7530863');

    else
        $connection = new mysqli('127.0.0.1', 'Ilya Bashlyaev', '#vOV(0y2#vOV(0y2', 'restaurant');
?>