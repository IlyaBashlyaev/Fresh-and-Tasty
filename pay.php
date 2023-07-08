<?php
    require 'db.php';

    if (isset($_COOKIE['id'])) {
        $userId = $_COOKIE['id'];
        $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                -> fetch_assoc();
        $userId = $user['id'];

        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
            $connection -> query("UPDATE `users` SET `phone` = '$phone' WHERE `id` = '$userId'");
        }
    }

    else if (isset($_COOKIE['guest-id'])) {
        $userId = $_COOKIE['guest-id'];

        if (isset($_POST['phone'])) {
            $phone = $_POST['phone'];
            $connection -> query("UPDATE `user-carts` SET `phone` = '$phone' WHERE `user-id` = '$userId'");
        }
    }

    if (isset($_COOKIE['id']) || isset($_COOKIE['guest-id'])) {
        $userCart = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'")
            -> fetch_assoc();

        $productsId = json_decode($userCart['products-id']);
        $productPrices = array();
        $totalPrice = 0;

        foreach ($productsId as $productId) {
            if (isset($productPrices[$productId]))
                $totalPrice += $productPrices[$productId];
            
            else {
                $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$productId'")
                        -> fetch_assoc();

                $productPrices[$productId] = (int) $product['price'];
                $totalPrice += (int) $product['price'];
            }
        }


        $productsId = implode(' ', $productsId);
        setcookie('products-id', $productsId, time() + 315360000);

        $string = file_get_contents('https://www.google.com/search?q=1+euro+in+hryvnia');
        libxml_use_internal_errors(true);
        $html = new DOMDocument();
        $html -> loadHTML($string);
        $html = new DOMXPath($html);

        $UAH = $html -> query('//div[@class="BNeawe iBp4i AP7Wnd"]') -> item(0) -> nodeValue;
        $UAH = (double) explode(' ', $UAH)[0] * $totalPrice;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery.redirect@1.1.4/jquery.redirect.min.js"></script>
        
        <link rel="shortcut icon" href="images/icon.png">
        <title>Shop - Pay</title>
    </head>

    <body>
        <script>
            $.redirect('https://sci.interkassa.com', {
                ik_co_id: '621fa5dd8640c862c864953a',
                ik_pm_no: '<?= $userId ?>',
                ik_am: '<?= $UAH ?>',
                ik_cur: 'UAH',
                ik_desc: 'Buying of products'
            }, 'POST')
        </script>
    </body>
</html>