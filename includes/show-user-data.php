<?php
    if (isset($_COOKIE['id'])) {
        require '../db.php';
        $userId = $_COOKIE['id'];
        
        $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                -> fetch_assoc();

        echo '["' . $user['username'] . '","' . $user['email'] . '","' . $user['phone'] . '"]';
    }
?>