<?php
    if ($_SERVER['HTTP_HOST'] == 'fresh-and-tasty.herokuapp.com') {
        $cleardb_url = parse_url(getenv('CLEARDB_DATABASE_URL'));
        $server = $cleardb_url['host'];
        $username = $cleardb_url['user'];
        $password = $cleardb_url['pass'];
        $db = substr($cleardb_url['path'], 1);

        $active_group = 'default';
        $query_builder = TRUE;
    }

    else {
        $server = 'localhost';
        $username = 'Ilya Bashlyaev';
        $password = '#vOV(0y2#vOV(0y2';
        $db = 'restaurant';
    }

    $connection = new mysqli($server, $username, $password, $db);
?>