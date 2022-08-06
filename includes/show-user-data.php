<?php
    if (isset($_COOKIE['id'])) {
        $userId = $_COOKIE['id'];

        $connection = new mysqli('127.0.0.1', 'admin', 'admin', 'restaurant');
        $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                -> fetch_assoc();

        echo '["' . $user['username'] . '","' . $user['email'] . '"]';
    }
?>