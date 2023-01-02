<?php
    if (isset($_POST['productId'])) {
        require '../db.php';
        ini_set('display_errors', 1);
        
        $postProductId = $_POST['productId'];
        $quantity = (int) $_POST ['quantity'];
        $lastQuantity = (int) $_POST ['lastQuantity'];

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

            if ($userCart) {
                $productsId = json_decode($userCart['products-id']);

                if ($lastQuantity < $quantity) {
                    for ($i = $lastQuantity; $i < $quantity; $i++) {
                        $productsId[] = $postProductId;
                    }

                    $newProductsId = $productsId;
                }

                else if ($lastQuantity > $quantity) {
                    $newProductsId = array();
                    $quantityDiff = $lastQuantity - $quantity;
                    $quantityIndex = 0;

                    for ($i = 0; $i < count($productsId); $i++) {
                        if ($quantityIndex < $quantityDiff) {
                            if ($productsId[$i] == $postProductId)
                                $quantityIndex++;
                            else
                                $newProductsId[] = $productsId[$i];
                        }

                        else
                            $newProductsId[] = $productsId[$i];
                    }
                }

                else
                    $newProductsId = $productsId;
            }
            
            else {
                $connection -> query("INSERT INTO `user-carts` (`user-id`, `products-id`) VALUES (
                    '$userId', '[\'$postProductId\']'
                )");

                $newProductsId = array($postProductId);
            }

            if (count($newProductsId) == 0)
                $connection -> query("DELETE FROM `user-carts` WHERE `user-id` = '$userId'");

            $newProductsId = json_encode($newProductsId);
            $connection -> query("UPDATE `user-carts` SET `products-id` = '$newProductsId' WHERE `user-id` = '$userId'");
            
            $postProduct = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$postProductId'")
            -> fetch_assoc();
            $productPrice = (int) ($postProduct['price'] * $quantity);
            echo $quantity . ' ' . $productPrice;

            $totalPrice = 0; $totalQuantity = 0;
            $newProductsId = json_decode($newProductsId);

            for ($i = 0; $i < count($newProductsId); $i++) {
                $productId = $newProductsId[$i];
                $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$productId'")
                -> fetch_assoc();

                $totalPrice += (int) $product['price'];
                $totalQuantity++;
            }

            echo ' ' . $totalPrice . ' ' . $totalQuantity;
            if (isset($product['prev-price'])) {
                $prevPrice = (int) ($postProduct['prev-price'] * $quantity);
                echo ' ' . $prevPrice;
            }
        }
    }
?>