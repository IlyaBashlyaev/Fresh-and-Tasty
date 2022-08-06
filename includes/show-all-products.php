<?php
    $connection = new mysqli('127.0.0.1', 'admin', 'admin', 'restaurant');
    $queryStr = ''; $length = 0;

    if (isset($_POST['total-count'])) {
        if ($_POST['total-count'])
            $length = (int) $_POST['total-count'] - 6;
    }

    if (isset($_POST['key'])) {
        if ($_POST['key'] == 'discount') 
            $queryStr = ' WHERE `prev-price`';
    }

    if (isset($_POST['category-id'])) {
        if ($_POST['category-id']) {
            $queryStr = ' WHERE `category-id` = ' . (int) $_POST['category-id'];

            if (isset($_POST['sub-category-id'])) {
                if ($_POST['sub-category-id'])
                    $queryStr .= ' AND `sub-category-id` = ' . (int) $_POST['sub-category-id'];
            }
        }
    }

    $products = $connection -> query(
        "SELECT * FROM `products`$queryStr ORDER BY `id` LIMIT 6, $length"
    );

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
                        <?= $product['price'] ?> ₴
                        <div class="prev-cost"><?php
                            $hasDiscount = false;

                            if ($product['prev-price']) {
                                $hasDiscount = true;
                                echo $product['prev-price'] . ' ₴';
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