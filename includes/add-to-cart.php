<?php
    if (isset($_POST['userId']) && isset($_POST['productId'])) {
        $userId = $_POST['userId'];
        $productId = $_POST['productId'];

        $cleardb_url = parse_url(getenv('CLEARDB_DATABASE_URL'));
        $cleardb_server = $cleardb_url['host'];
        $cleardb_username = $cleardb_url['user'];
        $cleardb_password = $cleardb_url['pass'];
        $cleardb_db = substr($cleardb_url['path'], 1);

        $active_group = 'default';
        $query_builder = TRUE;

        $connection = new mysqli($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
        $userCart = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'")
                     -> fetch_assoc();

        if ($userCart) {
            $userCartId = $userCart['user-id'];
            
            if ($userId == $userCartId) {
                $productsId = json_decode($userCart['products-id']);
                $productsId[] = $productId;
                $productsId = json_encode($productsId);

                $connection -> query("UPDATE `user-carts` SET `products-id` = '$productsId' WHERE `user-id` = '$userId'");
            }
        }

        else {
            $connection -> query("INSERT INTO `user-carts` (`user-id`, `products-id`) VALUES (
                '$userId', JSON_ARRAY('$productId')
            )");
        }
    }
?>