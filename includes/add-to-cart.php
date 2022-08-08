<?php
    if (isset($_GET['userId']) && isset($_GET['productId'])) {
        require '../db.php';
        
        $userId = $_GET['userId'];
        $productId = $_GET['productId'];

        $userCart = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'") -> fetch_assoc();

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
                '$userId', '$productId'
            )");
        }
    }
?>