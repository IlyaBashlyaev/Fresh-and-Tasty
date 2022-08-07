<?php
    if (isset($_POST['productId'])) {
        require '../db.php';
        
        $productId = $_POST['productId'];
        $quantity = (int) $_POST['quantity'];

        if (isset($_COOKIE['id'])) {
            $userId = $_COOKIE['id'];
            $userId = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                    -> fetch_assoc()['id'];
        }

        else if (isset($_COOKIE['guest-id']))
            $userId = $_COOKIE['guest-id'];

        if (isset($_COOKIE['id']) || isset($_COOKIE['guest-id'])) {
            $userCart = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'")
                        -> fetch_assoc();

            $productsId = json_decode($userCart['products-id']);
            $newProductsId = array();

            $i = 0;
            for ($i = 0; $i < count($productsId); $i++) {
                if ($productsId[$i] == $productId)
                    $productsId[$i] = '';
            }

            $length = count($productsId);
            for ($i = $length; $i < $length + $quantity; $i++) {
                $productsId[$i] = $productId;
            }

            foreach ($productsId as $productId) {
                if ($productId)
                    $newProductsId[] = $productId;
            }

            $productsId = json_encode($newProductsId);
            $connection -> query("UPDATE `user-carts` SET `products-id` = '$productsId' WHERE `user-id` = '$userId'");
            
            $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$productId'")
                       -> fetch_assoc();

            $price = (int) $product['price'] * $quantity;
            echo $price;
            echo ' ' . $quantity;

            if (isset($product['prev-price'])) {
                $prevPrice = (int) $product['prev-price'] * $quantity;
                echo ' ' . $prevPrice;
            }
        }
    }
?>