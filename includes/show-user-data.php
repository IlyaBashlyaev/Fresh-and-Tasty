<?php
    if (isset($_COOKIE['id'])) {
        $cleardb_url = parse_url(getenv('CLEARDB_DATABASE_URL'));
        $cleardb_server = $cleardb_url['host'];
        $cleardb_username = $cleardb_url['user'];
        $cleardb_password = $cleardb_url['pass'];
        $cleardb_db = substr($cleardb_url['path'], 1);

        $connection = new mysqli($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
        $userId = $_COOKIE['id'];

        $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                -> fetch_assoc();

        echo '["' . $user['username'] . '","' . $user['email'] . '"]';
    }
?>