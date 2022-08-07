<?php
    if (isset($_POST['productId'])) {
        require '../db.php';
        $productId = $_POST['productId'];

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

            $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$productId'")
                       -> fetch_assoc();
 
            $price = (int) $product['price'];
            $extraPrice = 0;

            $productsId = json_decode($userCart['products-id']);
            $newProductsId = array();

            for ($i = 0; $i < count($productsId); $i++) {
                if ($productsId[$i] == $productId) {
                    unset($productsId[$i]);
                    $extraPrice += $price;
                }

                else
                    $newProductsId[] = $productsId[$i];
            }

            $quantity = count($productsId);
            $productsId = json_encode($newProductsId);
            $connection -> query("UPDATE `user-carts` SET `products-id` = '$productsId' WHERE `user-id` = '$userId'");
            
            echo $extraPrice . ' ' . $quantity;
        }
    }
?>