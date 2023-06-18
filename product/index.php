<?php 
    require '../db.php';
    ini_set('display_errors', 0);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
        <link rel="shortcut icon" href="../images/icon.png">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css" integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" crossorigin="anonymous">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-LGSS1RFWS4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-LGSS1RFWS4');
        </script>

        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="../css/global.css">
        <title>Fresh and Tasty - Product</title>
    </head>

    <body id='body'>
        <?php require '../includes/header.php'; ?>

        <?php
            if (isset($_GET['id'])) {
                $products = $connection -> query('SELECT * FROM `products` WHERE `id` = ' . $_GET['id']);

                if ($products) {
                    $product = $products -> fetch_assoc();

                    if ($product) {
                        if (isset($_COOKIE['id'])) {
                            $userId = $_COOKIE['id'];
                            $users = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'");

                            if ($users)
                                $userId = $users -> fetch_assoc()['id'];
                            else
                                $userId = '';
                        }

                        else
                            $userId = '';

                        $productId = $product['product-id'];
                        $categoryId = $product['category-id'];
                        $subCategoryId = $product['sub-category-id'];

                        if ($categoryId == 0) {
                            $category = 'Fast Food';

                            if ($subCategoryId == 0)
                                $subCategory = 'Pizzas';
                            else if ($subCategoryId == 1)
                                $subCategory = 'Burgers';
                            else if ($subCategoryId == 2)
                                $subCategory = 'French Fries';
                        }

                        else if ($categoryId == 1) {
                            $category = 'Snacks';

                            if ($subCategoryId == 0)
                                $subCategory = 'Popcorn';
                            else if ($subCategoryId == 1)
                                $subCategory = 'Crisps';
                            else if ($subCategoryId == 2)
                                $subCategory = 'Crackers';
                        }

                        else if ($categoryId == 2) {
                            $category = 'Fast Food';

                            if ($subCategoryId == 0)
                                $subCategory = 'Water';
                            else if ($subCategoryId == 1)
                                $subCategory = 'Cola';
                            else if ($subCategoryId == 2)
                                $subCategory = 'Juice';
                        }
                        ?>
    
                        <main class="main">
                            <div class="container">
                                <div class="location">
                                    <a href="/" class="location-item">Homepage</a>
                                    <a href="/?category-id=<?= $product['category-id'] ?>" class="location-item"><?= $category ?></a>
                                    <a href="/?category-id=<?= $product['category-id'] ?>&sub-category-id=<?= $product['sub-category-id'] ?>" class="location-item current"><?= $subCategory ?></a>
                                </div>
    
                                <div class="product">
                                    <div class="product__images w100">
                                        <div class="product-image">
                                            <img src="../<?= $product['image'] ?>">
                                            <div class="detail-image-block" style="display: none;"></div>
                                            <canvas class="detail-image" style="display: none;"></canvas>
                                        </div>
                                    </div>
    
                                    <div class="product__details">
                                        <div class="product__title"><?= $product['title'] ?></div>
                                        <div class="product__descr-title">Description</div>
                                        <div class="product__text"><?= $product['description'] ?></div>
    
                                        <div class="product__info">
                                            <div class="product__info-item">
                                                <div class="product__info-categorie">Category:</div>
                                                <div class="product__info-content"><?= $category ?></div>
                                            </div>
    
                                            <div class="product__info-item">
                                                <div class="product__info-categorie">Subcategory:</div>
                                                <div class="product__info-content"><?= $subCategory ?></div>
                                            </div>
                                        </div>
    
                                        <div class="product__buy">
                                            <div class="cost" <?php
                                                if ($product['prev-price'])
                                                    echo 'style="line-height: 27px;"';
                                            ?>>
                                                <?= $product['price'] ?> $
    
                                                <?php
                                                    if ($product['prev-price']) {
                                                        ?>
                                                        <div class="prev-cost"><?= $product['prev-price'] ?> $</div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <button class="product__btn button" onclick="addToCart()">+ Add to cart</button>
                                        </div>
    
                                        <div class="product__descr">
                                            <div class="product__descr-main">
                                                <div class="product__descr-topic">Ingredients</div>
                                                <div class="product__descr-text"><?= $product['ingredients'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    var theme = localStorage.getItem('theme')
                                    const detailImage = document.querySelector('.detail-image')
                                    detailImage.width = 300
                                    detailImage.height = 300

                                    if (theme == 'dark') {
                                        const icon = document.querySelector('.header__user-dark-mode')
                                        document.body.className = 'dark'
                                        icon.innerHTML = '<i class="far fa-moon"></i>'
                                    }

                                    const navigationInfo = document.querySelector('.navigation__info')
                                    navigationInfo.remove()

                                    function removeWaterMark() {
                                        const waterMark = document.querySelector('a[title]')
                                        waterMark ? waterMark.remove() : requestAnimationFrame(removeWaterMark)
                                    }
                                    removeWaterMark()

                                    function changeTheme(icon) {
                                        document.body.classList.toggle('dark')

                                        if (document.body.className == 'dark') {
                                            icon.innerHTML = '<i class="far fa-moon"></i>'
                                            localStorage.setItem('theme', 'dark')
                                        }

                                        else if (!document.body.className) {
                                            icon.innerHTML = '<i class="far fa-sun"></i>'
                                            localStorage.setItem('theme', 'light')
                                        }
                                    }

                                    function onlineCounter() {
                                        $.ajax({
                                            url: '/online-counter.php',
                                            type: 'post',
                                            data: {},
                                            success: () => {}
                                        })
                                    }
                                    setInterval(onlineCounter, 2000)

                                    function showDetailImage(event) {
                                        const product = document.querySelector('.product-image'),
                                            productImage = product.querySelector('.img'),
                                            detailImageBlock = document.querySelector('.detail-image-block'),
                                            detailImage = document.querySelector('.detail-image'),
                                            ctx = detailImage.getContext('2d')

                                        detailImageBlock.style.display = 'block'
                                        detailImage.style.display = 'block'

                                        var {top, left, width, height} = product.getBoundingClientRect()
                                        top += window.scrollY
                                        left += window.screenX

                                        var diffX = event.pageX - left,
                                            diffY = event.pageY - top

                                        if (diffX < 100)
                                            diffX = 100
                                        if (diffY < 100)
                                            diffY = 100
                                        if (diffX > width - 100)
                                            diffX = width - 100
                                        if (diffY > height - 100)
                                            diffY = height - 100
                                            
                                        detailImageBlock.style.top = diffY - 100 + 'px'
                                        detailImageBlock.style.left = diffX - 100 + 'px'

                                        const x1 = diffX - 100, y1 = diffY - 100,
                                              img = new Image()
                                        img.src = '../<?= $product['image'] ?>'

                                        var ratio = img.width / img.height,
                                            lastWidth = width, lastHeight = height
                                        
                                        if (ratio < 1) {
                                            if (width >= height)
                                                height = width * ratio
                                        }

                                        else if (ratio > 1) {
                                            if (height >= width)
                                                width = height * ratio
                                        }

                                        else {
                                            if (width != height)
                                                width = height
                                        }

                                        var widthDiff = (width - lastWidth) / 2 + x1,
                                            heightDiif = (height - lastHeight) / 2 + y1
                                        ctx.drawImage(img, -1.5 * widthDiff, -1.5 * heightDiif, 1.5 * width, 1.5 * height)
                                    }

                                    function hideDetailImage() {
                                        const detailImageBlock = document.querySelector('.detail-image-block'),
                                            detailImage = document.querySelector('.detail-image')

                                        detailImageBlock.style.display = 'none'
                                        detailImage.style.display = 'none'
                                    }

                                    function addToCart() {
                                        if ('<?= $userId ?>') {
                                            $.ajax({
                                                url: '/includes/add-to-cart.php',
                                                type: 'post',
                                                data: {
                                                    userId: '<?= $userId ?>',
                                                    productId: '<?= $productId ?>'
                                                },
                                                success: () => {
                                                    alert('The product was successfully added to the cart.')
                                                    location.reload()
                                                }
                                            })
                                        }

                                        else
                                            window.location.href = '/login.php'
                                    }

                                    const productImage = document.querySelector('.product-image')
                                    productImage.addEventListener('mousemove', showDetailImage)
                                    productImage.addEventListener('mouseleave', hideDetailImage)
                                </script>

                                <div class="related">
                                    <div class="related__top">
                                        <div class="related__title title">Related products</div>
                                        <div class="related__more"><a href="/?page=1&category-id=<?= $categoryId ?>&sub-category-id=<?= $subCategoryId ?>">More products</a> <span>></span></div>
                                    </div>

                                    <div class="products__list">
                                        <?php
                                            $products = $connection -> query("SELECT * FROM `products` WHERE `category-id` = $categoryId AND `sub-category-id` = $subCategoryId LIMIT 0, 6");

                                            if ($products) {
                                                while ($product = $products -> fetch_assoc()) {
                                                    ?>

                                                    <div class="products__item" style="margin-right: 32px;">
                                                        <div class="products__item-photo" style="background-image: url(../<?= $product['image'] ?>);"></div>
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
                                                                <?= $product['price'] ?> $
                                                                <?php
                                                                    if ($product['prev-price'])
                                                                        echo '<div class="prev-cost">' . $product['prev-price'] . ' $</div>';
                                                                ?>
                                                            </div>

                                                            <div class="products__btn button" onclick="addToCart('<?= $product['product-id'] ?>')">Buy now</div>
                                                        </div>

                                                        <?php
                                                            if ($product['prev-price']) {
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
                                </div>
                            </div>
                        </main>
                        
                        <script>
                            $(() => {
                                $('.products__list').slick({
                                    slidesToShow: 4,
                                    slidesToScroll: 1,
                                    arrows: false,
                                    responsive: [
                                        {
                                            breakpoint: 1100,
                                            settings:{
                                                slidesToShow: 3,
                                                slidesToScroll: 3,
                                            }
                                        },
                                        {
                                            breakpoint: 800,
                                            settings:{
                                                slidesToShow: 2,
                                                slidesToScroll: 2,
                                            }
                                        },
                                        {
                                            breakpoint: 500,
                                            settings:{
                                                slidesToShow: 1,
                                                slidesToScroll: 1,
                                            }
                                        }
                                    ]
                                })
                            })
                        </script>
    
                        <?php
                    }
                }
            }
        ?>

        <?php require '../includes/footer.php'; ?>
    </body>
</html>