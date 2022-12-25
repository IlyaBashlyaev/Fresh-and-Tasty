<?php
    require 'db.php';
    ini_set('display_errors', 0);

    if (isset($_COOKIE['products-id'])) {
        $productsId = explode(' ', $_COOKIE['products-id']);
        setcookie('products-id', '', time() - 3600);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
        <link rel="shortcut icon" href="images/icon.png">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css" integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" crossorigin="anonymous">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">

        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/global.css">

        <script class="paypal" src="includes/paypal.js" defer></script>
        <title>Fresh and Tasty</title>
    </head>

    <body id='body' onmouseup="isMouseDown = false">
        <?php
            require 'includes/header.php';
            echo '<script>var webView = ';

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                echo 'true';
            else
                echo 'false';

            echo '; var isMouseDown, element;</script>'
        ?>

        <main class="content">
            <div class="container">
                <div class="content-w">
                    <aside class="settings">
                        <ul class="settings-menu">
                            <h3 class="title">Online: <span>0</span></h3>
                        </ul>

                        <ul class="settings-menu">
                            <h3 class="title">Categories</h3>

                            <li class="settings-menu__item">
                                <input class="main-category" id="category-all" type="checkbox" onclick="categoryCheck(this, 'all')">
                                <label for="category-all"><b>All Categories</b></label>
                            </li>

                            <li class="settings-menu__item">
                                <input class="main-category" id="category-0" type="checkbox" onclick="categoryCheck(this, 1); categoryUncheck(this, 0)">
                                <label for="category-0">Fast Food</label>
                            </li>

                            <li class="settings-menu__item">
                                <input id="sub-category-0" type="checkbox" category-id="0" sub-category-id="0" onclick="categoryUncheck(this, 1)">
                                <label for="sub-category-0">Pizzas</label>
                            </li>
                            <li class="settings-menu__item">
                                <input id="sub-category-1" type="checkbox" category-id="0" sub-category-id="1" onclick="categoryUncheck(this, 1)">
                                <label for="sub-category-1">Burgers</label>
                            </li>
                            <li class="settings-menu__item">
                                <input id="sub-category-2" type="checkbox" category-id="0" sub-category-id="2" onclick="categoryUncheck(this, 1)">
                                <label for="sub-category-2">French Fries</label>
                            </li>

                            <li class="settings-menu__item">
                                <input class="main-category" id="category-1" type="checkbox" onclick="categoryCheck(this, 5); categoryUncheck(this, 0)">
                                <label for="category-1">Snacks</label>
                            </li>

                            <li class="settings-menu__item">
                                <input id="sub-category-3" type="checkbox" category-id="1" sub-category-id="0" onclick="categoryUncheck(this, 5)">
                                <label for="sub-category-3">Popcorn</label>
                            </li>
                            <li class="settings-menu__item">
                                <input id="sub-category-4" type="checkbox" category-id="1" sub-category-id="1" onclick="categoryUncheck(this, 5)">
                                <label for="sub-category-4">Crisps</label>
                            </li>
                            <li class="settings-menu__item">
                                <input id="sub-category-5" type="checkbox" category-id="1" sub-category-id="2" onclick="categoryUncheck(this, 5)">
                                <label for="sub-category-5">Crackers</label>
                            </li>

                            <li class="settings-menu__item">
                                <input class="main-category" id="category-2" type="checkbox" onclick="categoryCheck(this, 9); categoryUncheck(this, 0)">
                                <label for="category-2">Drinks</label>
                            </li>

                            <li class="settings-menu__item">
                                <input id="sub-category-6" type="checkbox" category-id="2" sub-category-id="0" onclick="categoryUncheck(this, 9)">
                                <label for="sub-category-6">Water</label>
                            </li>
                            <li class="settings-menu__item">
                                <input id="sub-category-7" type="checkbox" category-id="2" sub-category-id="1" onclick="categoryUncheck(this, 9)">
                                <label for="sub-category-7">Cola</label>
                            </li>
                            <li class="settings-menu__item">
                                <input id="sub-category-8" type="checkbox" category-id="2" sub-category-id="2" onclick="categoryUncheck(this, 9)">
                                <label for="sub-category-8">Juice</label>
                            </li>
                        </ul>

                        <?php
                            require 'PHPMailer/src/PHPMailer.php';
                            require 'PHPMailer/src/SMTP.php';
                            require 'PHPMailer/src/Exception.php';
                        
                            use PHPMailer\PHPMailer\PHPMailer;
                            use PHPMailer\PHPMailer\SMTP;
                            use PHPMailer\PHPMailer\Exception;
                            $key = '';

                            if (
                                (isset($_POST['ik_co_id']) && isset($_POST['ik_inv_st'])) ||
                                isset($_POST['payment_details'])
                            ) {
                                isset($_POST['ik_co_id']) ? $ik_co_id = $_POST['ik_co_id'] : $ik_co_id = '';
                                isset($_POST['ik_inv_st']) ? $ik_inv_st = $_POST['ik_inv_st'] : $ik_inv_st = '';

                                $paymentDetails = (array) json_decode($_POST['payment_details']);

                                if (!$paymentDetails) {
                                    $paymentDetails = array(
                                        'intent' => '',
                                        'status' => ''
                                    );
                                }

                                if (isset($paymentDetails['purchase_units'])) {
                                    $purchase_units = (array) $paymentDetails['purchase_units'][0];

                                    if ($purchase_units['amount'])
                                        $amount = (array) $purchase_units['amount'];
                                    
                                    else {
                                        $amount = array(
                                            'currency_code' => ''
                                        );
                                    }
                                }

                                else {
                                    $amount = array(
                                        'currency_code' => ''
                                    );
                                }

                                if (
                                    ($ik_co_id == '621fa5dd8640c862c864953a' && $ik_inv_st == 'success') ||
                                    (
                                        isset($paymentDetails['id']) && $paymentDetails['intent'] == 'CAPTURE' && $paymentDetails['status'] == 'COMPLETED' &&
                                        $amount['currency_code'] == 'EUR'
                                    )
                                ) {
                                    $repeatedIds = array();
                                    foreach ($productsId as $productId) {
                                        if (isset($repeatedIds[$productId]))
                                            $repeatedIds[$productId]++;
                                        else
                                            $repeatedIds[$productId] = 1;
                                    }

                                    if ($repeatedIds) {
                                        if (isset($_POST['ik_pm_no'])) {
                                            $userId = $_POST['ik_pm_no'];
                                            $user = $connection -> query("SELECT * FROM `users` WHERE `id` = '$userId'")
                                                    -> fetch_assoc();
                                        }

                                        else if (isset($_COOKIE['user-id'])) {
                                            $secretId = $_COOKIE['user-id'];
                                            $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$secretId'")
                                                    -> fetch_assoc();
                                        }

                                        if ($user) {
                                            $username = $user['username'];
                                            $email = $user['email'];
                                            $phone = $user['phone'];
                                        }

                                        else {
                                            $userId = '';
                                            $username = 'Unknown';
                                            $email = 'Unknown';
                                            $phone = 'Unknown';
                                        }

                                        $senderUsername = 'ibashlyaev2000@gmail.com';
                                        $senderPassword = 'pazbrinzydyhztqv';

                                        $mail = new PHPMailer();
                                        $mail -> isSMTP();
                                        $mail -> Host = 'smtp.gmail.com';
                                        $mail -> SMTPAuth = true;

                                        $mail -> Username = $senderUsername;
                                        $mail -> Password = $senderPassword;

                                        $mail -> SMTPSecure = 'tls';
                                        $mail -> Port = 587;

                                        $mail -> setFrom($senderUsername, 'Ilya Bashlyaev');
                                        $mail -> addAddress($senderUsername);
                                        if ($email) $mail -> addAddress($email);
                                        $mail -> isHTML();

                                        $body = "<i>Hi! Products have just been purchased from our website by a customer.</i><br><br>

Customer contact details:<br>
- Username: <b>$username</b>.<br>
- Email: <b>$email</b>.<br>
- Phone: <b>$phone</b>.<br><br>

Products that have been purchased by a customer:<br>";

                                        foreach ($repeatedIds as $id => $repeatedId) {
                                            $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$id'")
                                                        -> fetch_assoc();
                                            $title = $product['title'];

                                            $body .= "    - \"$title\" ($repeatedId product";
                                            if ($repeatedId > 1)
                                                $body .= 's';
                                            $body .= ').<br>';
                                        }

                                        if (isset($purchase_units['shipping'])) {
                                            $shipping = (array) $purchase_units['shipping'];

                                            if (isset($shipping['address']))
                                                $address = (array) $shipping['address'];
                                            else
                                                $address = '';
                                        }

                                        else
                                            $address = '';

                                        if ($address) {
                                            $body .= "<br>The data of user for the shipping:<br>
- Addres: <b>" . $address['address_line_1'] . "</b><br>
- City: <b>" . $address['admin_area_2'] . "</b><br>
- Postal Code: <b>" . $address['postal_code'] . "</b><br>
- Country Code: <b>" . $address['country_code'] . "</b>";
                                        }

                                        $mail -> Subject = 'New order';
                                        $mail -> Body = $body;
                                        $mail -> send();

                                        $connection -> query("DELETE FROM `user-carts` WHERE `user-id` = '$userId'");
                                        ?>

                                        <script>
                                            if (!webView)
                                                alert('Products have been successfully purchased. Wait for feedback from our worker.')
                                        </script>

                                        <?php
                                    }
                                }

                                else
                                    echo "<script>alert('Your payment was uncorrect. Please, try it again.')</script>";
                            }

                            if (isset($_GET['key'])) {
                                if ($_GET['key'] == 'discount') {
                                    $key = 'discount';
                                    $categoryId = '';
                                    $subCategoryId = '';

                                    $products = $connection -> query(
                                        "SELECT * FROM `products` WHERE `prev-price` LIMIT 0, 6"
                                    );

                                    $totalCount = $connection -> query(
                                        'SELECT COUNT(*) AS `total-count` FROM `products` WHERE `prev-price`'
                                    ) -> fetch_assoc()['total-count'];

                                    $maxPrice = $connection -> query(
                                        'SELECT max(`price`) FROM `products`' . $totalCountStr
                                    ) -> fetch_assoc()['max(`price`)'];

                                    ?>

                                    <script>
                                        const settingsMenuItems = document.querySelectorAll('.settings-menu__item')
                                        settingsMenuItems.forEach(settingsMenuItem => {
                                            const checkbox = settingsMenuItem.querySelector('input[type="checkbox"]')
                                            checkbox.checked = true
                                        })
                                    </script>

                                    <?php
                                }
                            }

                            else {
                                if (!isset($_GET['page'])) {
                                    if (isset($_GET['category-id']) && isset($_GET['sub-category-id'])) {
                                        $categoryId = (int) $_GET['category-id'];
                                        $subCategoryId = (int) $_GET['sub-category-id'];
                                        $totalCountStr = "WHERE `category-id` = $categoryId AND `sub-category-id` = $subCategoryId";

                                        $products = $connection -> query(
                                            "SELECT * FROM `products` WHERE `category-id` = $categoryId AND `sub-category-id` = $subCategoryId ORDER BY `id`"
                                        );
                                    }

                                    else if (isset($_GET['category-id'])) {
                                        $categoryId = (int) $_GET['category-id'];
                                        $subCategoryId = '';
                                        $totalCountStr = "WHERE `category-id` = $categoryId";

                                        $products = $connection -> query(
                                            "SELECT * FROM `products` WHERE `category-id` = $categoryId ORDER BY `id`"
                                        );
                                    }
                                    
                                    else {
                                        $categoryId = '';
                                        $subCategoryId = '';
                                        $totalCountStr = '';

                                        $products = $connection -> query('SELECT * FROM `products` ORDER BY `id` LIMIT 0, 6');
                                    }

                                    $totalCount = $connection -> query(
                                        'SELECT COUNT(*) AS `total-count` FROM `products`' . $totalCountStr
                                    ) -> fetch_assoc()['total-count'];

                                    $maxPrice = $connection -> query(
                                        'SELECT max(`price`) FROM `products`' . $totalCountStr
                                    ) -> fetch_assoc()['max(`price`)'];
                                }
                                
                                else {
                                    $perPage = 6;
                                    $page = (int) $_GET['page'];
                                    $startId = ($page - 1) * $perPage;

                                    if (isset($_GET['category-id']) && isset($_GET['sub-category-id'])) {
                                        $categoryId = (int) $_GET['category-id'];
                                        $subCategoryId = (int) $_GET['sub-category-id'];
                                        $totalCountStr = "WHERE `category-id` = $categoryId AND `sub-category-id` = $subCategoryId";

                                        $products = $connection -> query(
                                            "SELECT * FROM `products` WHERE `category-id` = $categoryId AND `sub-category-id` = $subCategoryId ORDER BY `id` LIMIT $startId, $perPage"
                                        );
                                    }

                                    else if (isset($_GET['category-id'])) {
                                        $categoryId = (int) $_GET['category-id'];
                                        $subCategoryId = '';
                                        $totalCountStr = "WHERE `category-id` = $categoryId";

                                        $products = $connection -> query(
                                            "SELECT * FROM `products` WHERE `category-id` = $categoryId ORDER BY `id` LIMIT $startId, $perPage"
                                        );
                                    }

                                    else {
                                        $categoryId = '';
                                        $subCategoryId = '';
                                        $totalCountStr = '';

                                        $products = $connection -> query("SELECT * FROM `products` ORDER BY `id` LIMIT $startId, $perPage");
                                    }

                                    $totalCount = $connection -> query(
                                        'SELECT COUNT(*) AS `total-count` FROM `products`' . $totalCountStr
                                    ) -> fetch_assoc()['total-count'];

                                    $maxPrice = $connection -> query(
                                        'SELECT max(`price`) FROM `products`' . $totalCountStr
                                    ) -> fetch_assoc()['max(`price`)'];

                                    $allPages = ceil($totalCount / $perPage);
                                    ?>
                                    
                                    <div class="settings-menu">
                                        <div class="title">Paginator</div>

                                        <div class="paginator-block">
                                            <?php
                                                if ($page != 1) {
                                                    ?>

                                                    <button onclick="setNewPage('<?= $page - 1 ?>')" class="button paginator-button">
                                                        <span>&laquo; Last Page</span>    
                                                    </button>

                                                    <?php
                                                }

                                                if ($page != $allPages) {
                                                    ?>

                                                    <button onclick="setNewPage('<?= $page + 1 ?>')" class="button paginator-button">
                                                        <span>Next Page &raquo;</span>
                                                    </button>
                                                    
                                                    <?php
                                                }
                                            ?>
                                        </div>

                                        <button onclick="window.location.href = '/'" class="button paginator-button" style="<?php
                                            if ($page != 1 || $page != $allPages)
                                                echo " margin-top: 25px;"
                                        ?>">
                                            <span>All Products</span>    
                                        </button>
                                    </div>

                                    <?php
                                }

                                if (isset($_GET['category-id']) && isset($_GET['sub-category-id'])) {
                                    ?>

                                    <script>
                                        const checkbox = document.querySelector('#sub-category-<?= $categoryId * 3 + $subCategoryId ?>')
                                        checkbox.checked = true
                                    </script>

                                    <?php
                                }

                                else if (isset($_GET['category-id'])) {
                                    ?>

                                    <script>
                                        const checkbox = document.querySelector('#category-<?= $categoryId ?>')
                                        checkbox.checked = true
                                    </script>

                                    <?php
                                }

                                else {
                                    ?>

                                    <script>
                                        const settingsMenuItems = document.querySelectorAll('.settings-menu__item')
                                        settingsMenuItems.forEach(settingsMenuItem => {
                                            const checkbox = settingsMenuItem.querySelector('input[type="checkbox"]')
                                            checkbox.checked = true
                                        })
                                    </script>

                                    <?php
                                }
                            }
                        ?>

                        <div class="price">
                            <h3 class="title">Price</h3>

                            <div class="price__limit">
                                <input type="number" name="min" class="min-price" oninput="priceCheck()" value="0">
                                <p>-</p>
                                <input type="number" name="max" class="max-price" oninput="priceCheck()" value="<?= $maxPrice ?>">
                            </div>

                            <div class="price__range" range-id="0" style="width: 10px;">
                                <div class="price__range-circle" onmousedown="isMouseDown = true; element = this;"></div>
                            </div>

                            <div class="price__range" range-id="1" style="width: 100%;">
                                <div class="price__range-circle" onmousedown="isMouseDown = true; element = this;"></div>
                            </div>
                        </div>
                    </aside>

                    <section class="main-content">
                        <?php
                            if (!isset($_GET['page']) && $totalCount > 6) {
                                ?>
                                
                                <div class="show-products">
                                    <span onclick="showAllProducts()">Show All Products</span>
                                </div>

                                <?php
                            }
                        ?>

                        <div class="products">
                            <?php
                                if ($products) {
                                    while ($product = $products -> fetch_assoc()) {
                                        ?>

                                        <div class="products__item">
                                            <div class="products__item-photo" style="background-image: url(<?= $product['image'] ?>);"></div>

                                            <div class="products__item-title">
                                                <a href="/product/?id=<?= $product['id'] ?>"><?= $product['title'] ?></a>
                                            </div>
                                            
                                            <div class="products__item-text"><?php
                                                $shortDescription = mb_substr($product['description'], 0, 50, 'utf-8');
                                                echo $shortDescription;

                                                if ($shortDescription != $product['description'])
                                                    echo ' ...';
                                            ?></div>

                                            <div class="products__item-buy">
                                                <div class="cost">
                                                    <?= $product['price'] ?> €
                                                    <div class="prev-cost"><?php
                                                        $hasDiscount = false;

                                                        if ($product['prev-price']) {
                                                            $hasDiscount = true;
                                                            echo $product['prev-price'] . ' €';
                                                        }
                                                    ?></div>
                                                </div>

                                                <div class="products__btn button" onclick="addToCart('<?= $product['product-id'] ?>')">Buy now</div>
                                            </div>

                                            <?php
                                                if ($hasDiscount) {
                                                    ?>
                                                    
                                                    <div class="products__item-discount"><?php
                                                        $discount = 100 - round($product['price'] / $product['prev-price'] * 100);
                                                        echo "-$discount%";
                                                    ?></div>

                                                    <?php
                                                }
                                            ?>
                                        </div>

                                        <?php
                                    }
                                }
                            ?>
                        </div>
                    </section>
                </div>

                <div class="related">
                    <div class="related__top">
                        <div class="related__title title">Products with discount</div>
                        <div class="related__more"><a href="/?key=discount">More products</a> <span>></span></div>
                    </div>

                    <div class="products__list">
                        <?php
                            $products = $connection -> query("SELECT * FROM `products` WHERE `prev-price` LIMIT 0, 6");

                            if ($products) {
                                while ($product = $products -> fetch_assoc()) {
                                    ?>

                                    <div class="products__item" style="margin-right: 32px;">
                                        <div class="products__item-photo" style="background-image: url(<?= $product['image'] ?>);"></div>
                                        <div class="products__item-title">
                                            <a href="/product/?id=<?= $product['id'] ?>"><?= $product['title'] ?></a>
                                        </div>
                                        
                                        <div class="products__item-text"><?php
                                            $shortDescription = mb_substr($product['description'], 0, 50, 'utf-8');
                                            echo $shortDescription;

                                            if ($shortDescription != $product['description'])
                                                echo ' ...';
                                        ?></div>

                                        <div class="products__item-buy">
                                            <div class="cost" style="line-height: 27px;">
                                                <?= $product['price'] ?> €
                                                <div class="prev-cost"><?= $product['prev-price'] ?> €</div>
                                            </div>

                                            <div class="products__btn button" onclick="addToCart('<?= $product['product-id'] ?>')">Buy now</div>
                                        </div>

                                        <div class="products__item-discount"><?php
                                            $discount = 100 - round($product['price'] / $product['prev-price'] * 100);
                                            echo "-$discount%";
                                        ?></div>
                                    </div>

                                    <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </main>

        <?php require 'includes/footer.php'; ?>

        <script>
            var isProductsShowed = true,
                timer, lastScrollY, scrollPoint

            if (theme == 'dark') {
                const icon = document.querySelector('.header__user-dark-mode')
                document.body.className = 'dark'
                icon.innerHTML = '<i class="far fa-moon"></i>'
            }

            function replaceProducts() {
                const settingsMenuItems = document.querySelectorAll('.settings-menu__item'),
                      products = document.querySelector('.products'),
                      showProducts = document.querySelector('.show-products'),
                      minPrice = document.querySelector('.min-price'),
                      maxPrice = document.querySelector('.max-price'),
                      searchInput = document.querySelector('.header__search input')
                var checkboxFlag = true

                if (minPrice.value && maxPrice.value) {
                    if (products)
                        products.innerHTML = ''
                    if (showProducts)
                        showProducts.remove()

                    settingsMenuItems.forEach(settingsMenuItem => {
                        var checkbox = settingsMenuItem.querySelector('input[type="checkbox"]')
                        
                        if (checkbox.checked && checkbox.className != 'main-category') {
                            if (checkboxFlag) {
                                checkboxFlag = false
                            }

                            $.ajax({
                                url: '/includes/replace-products.php',
                                type: 'post',
                                data: {
                                    'search': searchInput.value,
                                    'category-id': checkbox.getAttribute('category-id'),
                                    'sub-category-id': checkbox.getAttribute('sub-category-id'),
                                    'min-price': minPrice.value,
                                    'max-price': maxPrice.value
                                },
                                success: newProducts => {
                                    const products = document.querySelector('.products')
                                    products.innerHTML += newProducts
                                }
                            })
                        }
                    })
                }
            }

            function preReplaceProducts() {
                if (timer)
                    clearTimeout(timer)
                timer = setTimeout(replaceProducts, 500)
            }

            function priceCheck() {
                const priceLimit = document.querySelector('.price__limit'),
                      minPrice = document.querySelector('.min-price'),
                      maxPrice = document.querySelector('.max-price')
                
                if (parseInt(minPrice.value) < 0)
                    minPrice.value = 0;
                if (parseInt(maxPrice.value) < 0)
                    maxPrice.value = 0;
                if (parseInt(minPrice.value) > <?= $maxPrice ?>)
                    minPrice.value = <?= $maxPrice ?>;
                if (parseInt(maxPrice.value) > <?= $maxPrice ?>)
                    maxPrice.value = <?= $maxPrice ?>;
                if (parseInt(minPrice.value) > parseInt(maxPrice.value))
                    minPrice.value = maxPrice.value;

                const price = document.querySelector('.price'),
                      priceRange0 = price.querySelectorAll('.price__range')[0],
                      priceRange1 = price.querySelectorAll('.price__range')[1]
                
                priceRange0.style.width = minPrice.value / <?= $maxPrice ?> * price.clientWidth + 'px';
                priceRange1.style.width = maxPrice.value / <?= $maxPrice ?> * price.clientWidth + 'px';

                if (minPrice.value && maxPrice.value)
                    preReplaceProducts()
            }

            function categoryCheck(mainCheckbox, key) {
                const allCategories = document.querySelectorAll('.settings-menu')[1]
                                      .querySelectorAll('.settings-menu__item:not(:nth-child(2))')
                var categories, category, checkbox

                if (key == 'all')
                    categories = allCategories
                else {
                    categories = [
                        allCategories[key], allCategories[key + 1], allCategories[key + 2]
                    ]
                }

                for (var i = 0; i < categories.length; i++) {
                    category = categories[i]
                    checkbox = category.querySelector('input[type="checkbox"]')
                    
                    if (mainCheckbox.checked)
                        checkbox.checked = true
                    else
                        checkbox.checked = false
                }

                preReplaceProducts()
            }

            function categoryUncheck(mainCheckbox, key) {
                if (!mainCheckbox.checked) {
                    const categories = document.querySelectorAll('.settings-menu')[1]
                                          .querySelectorAll('.settings-menu__item')

                    var mainCategory = categories[0]
                        checkboxes = [
                            mainCategory.querySelector('input[type="checkbox"]'),
                        ]
                    
                    if (checkboxes[0].checked)
                        checkboxes[0].checked = false

                    if (key != 0) {
                        var category = categories[key]
                        checkboxes[1] = category.querySelector('input[type="checkbox"]')

                        if (checkboxes[1].checked)
                            checkboxes[1].checked = false
                    }
                }

                preReplaceProducts()
            }

            function setNewPage(newPage) {
                window.location.href = `/?page=${newPage}<?php
                    if ($categoryId != '') {
                        echo '&category-id=' . $categoryId;
                        if ($subCategoryId != '')
                            echo '&sub-category-id=' . $subCategoryId;
                    }
                ?>`
            }

            function showAllProducts() {
                if (isProductsShowed) {
                    $.ajax({
                        url: '/includes/show-all-products.php',
                        type: 'post',
                        data: {
                            'key': '<?= $key ?>',
                            'total-count': '<?= $totalCount ?>',
                            'category-id': '<?= $categoryId ?>',
                            'sub-category-id': '<?= $subCategoryId ?>'
                        },
                        success: newProducts => {
                            const products = document.querySelector('.products')
                            products.innerHTML += newProducts
                        }
                    })

                    isProductsShowed = false
                }
            }

            function onlineCounter() {
                $.ajax({
                    url: '/online-counter.php',
                    type: 'post',
                    data: {},
                    success: onlineCount => {
                        const span = document.querySelector('.settings-menu span')
                        span.innerText = onlineCount
                    }
                })
            }
            setInterval(onlineCounter, 2000)

            function setSidebarPosition() {
                if (window.innerWidth > 750) {
                    const mainContent = document.querySelector('.main-content'),
                          settings = document.querySelector('.settings'),
                          mainClientRect = mainContent.getBoundingClientRect(),
                          settingsClientRect = settings.getBoundingClientRect()
                    
                    var mainBottom = mainClientRect.top + mainContent.clientHeight - window.innerHeight,
                        settingsBottom = settingsClientRect.bottom - window.innerHeight

                    if (mainClientRect.top <= 0 && mainBottom >= 0) {
                        if (window.scrollY > lastScrollY) {
                            if (settingsBottom < 0 && mainContent.clientHeight > window.innerHeight)
                                settings.style.marginTop = -mainClientRect.top - (settings.clientHeight - window.innerHeight) - 4 + 'px'
                        }

                        else {
                            if (settingsClientRect.top > 0 && mainContent.clientHeight > window.innerHeight)
                                settings.style.marginTop = -mainClientRect.top + 'px'
                        }

                        lastScrollY = window.scrollY
                    }
                }
            }

            function changePrice(event, element) {
                if (isMouseDown) {
                    priceRange = element.parentNode
                    price = priceRange.parentNode

                    priceRangeX = priceRange.getBoundingClientRect()['x']
                    priceRangeWidth = event.x - priceRangeX

                    if (priceRangeWidth < 10)
                        priceRangeWidth = 10
                    else if (priceRangeWidth > price.clientWidth)
                        priceRangeWidth = price.clientWidth

                    var rangeId = parseInt(priceRange.getAttribute('range-id')),
                        newPriceRange = document.querySelectorAll('.price__range')[1 - rangeId]

                    if (
                        (!rangeId && priceRangeWidth > newPriceRange.clientWidth) ||
                        (rangeId && priceRangeWidth < newPriceRange.clientWidth)
                    )
                        priceRangeWidth = newPriceRange.clientWidth

                    if (!rangeId) {
                        const minPrice = document.querySelector('.min-price')
                        minPrice.value = Math.round((priceRangeWidth - 10) / (price.clientWidth - 10) * <?= $maxPrice ?>)
                    }

                    else {
                        const maxPrice = document.querySelector('.max-price')
                        maxPrice.value = Math.round((priceRangeWidth - 10) / (price.clientWidth - 10) * <?= $maxPrice ?>)
                    }

                    priceRange.style.width = priceRangeWidth + 'px'
                    preReplaceProducts()
                }
            }
            body.setAttribute('onmousemove', 'changePrice(event, element)')

            function setSettingsHeight() {
                const settings = document.querySelector('.settings')

                if (settings) {
                    if (!settings.classList.contains('hide')) {
                        settings.style.removeProperty('height')
                        settings.style.height = settings.clientHeight + 'px'
                    }
                }

                if (window.innerWidth > 750)
                    settings.classList.remove('hide')
            }
            setSettingsHeight()

            function addToCart(productId) {
                if ('<?= $userId ?>') {
                    $.ajax({
                        url: '/includes/add-to-cart.php',
                        type: 'post',
                        data: {
                            userId: '<?= $userId ?>',
                            productId: productId
                        },
                        success: () => {
                            if (!webView)
                                alert('The product was successfully added to the cart.')
                            
                                window.location.href = '/'
                        }
                    })
                }
            }

            document.addEventListener('scroll', setSidebarPosition)
            window.addEventListener('resize', setSettingsHeight)

            $(() => {
                $('.products__list').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: false,
                    responsive: [
                        {
                            breakpoint: 1100,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 800,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                            }
                        },
                        {
                            breakpoint: 500,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ]
                })
            })
        </script>

        <?php
            if (isset($_GET['category-id']) && !isset($_GET['sub-category-id'])) {
                ?>

                <script>
                    categoryCheck(checkbox, <?= $categoryId * 4 + 1 ?>)
                </script>

                <?php
            }
        ?>
    </body>
</html>