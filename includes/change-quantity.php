<?php
    if (isset($_POST['productId'])) {
        require '../db.php';
        
        $postProductId = $_POST['productId'];
        $quantity = $_POST['quantity'];

        if (isset($_COOKIE['id'])) {
            $userId = $_COOKIE['id'];
            $userId = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                    -> fetch_assoc()['id'];
        }

        else if (isset($_COOKIE['guest-id']))
            $userId = $_COOKIE['guest-id'];

        if (isset($_COOKIE['id']) || isset($_COOKIE['guest-id'])) {
            $userCart = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'");

            if ($userCart) {
                $userCart = $userCart -> fetch_assoc();
                $productsId = json_decode($userCart['products-id']);

                if (count($productsId) < $quantity) {
                    for ($i = count($productsId); $i < $quantity; $i++) {
                        $productsId[] = $postProductId;
                    }

                    $newProductsId = $productsId;
                }

                else if (count($productsId) > $quantity) {
                    for ($i = $quantity; $i < count($productsId); $i++) {
                        $index = array_search($postProductId, $productsId);
                        unset($productsId[$index]);
                    }

                    $newProductsId = array();

                    for ($i = 0; $i < count($productsId); $i++) {
                        if ($productsId[$i])
                            $newProductsId[] = $productsId[$i];
                    }
                }
            }
            
            else {
                $connection -> query("INSERT INTO `user-carts` (`user-id`, `products-id`) VALUES (
                    '$userId', '[\'$postProductId\']'
                )");

                $newProductsId = array($postProductId);
            }

            $newProductsId = json_encode($newProductsId);
            $connection -> query("UPDATE `user-carts` SET `products-id` = '$newProductsId' WHERE `user-id` = '$userId'");

            // $i = 0;
            // for ($i = 0; $i < count($productsId); $i++) {
            //     if ($productsId[$i] == $productId)
            //         $productsId[$i] = '';
            // }

            // $length = count($productsId);
            // for ($i = $length; $i < $length + $quantity; $i++) {
            //     $productsId[$i] = $productId;
            // }

            // foreach ($productsId as $productId) {
            //     if ($productId)
            //         $newProductsId[] = $productId;
            // }

            $productPrice = (int) $product['price'] * $quantity;
            echo $productPrice;

            $totalPrice = 0; $totalQuantity = 0;
            $newProductsId = json_decode($userCart['products-id']);

            foreach ($newProductsId as $productId) {
                $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$productId'")
                -> fetch_assoc();

                $totalPrice += (int) $product['price'];
                $totalQuantity++;
            }

            echo ' ' . $totalPrice . ' ' . $totalQuantity;

            if (isset($product['prev-price'])) {
                $prevPrice = (int) $product['prev-price'] * $quantity;
                echo ' ' . $prevPrice;
            }

            if (!count($newProductsId))
                $connection -> query("DELETE FROM `user-carts` WHERE `user-id` = '$userId'");
        }
    }
?>