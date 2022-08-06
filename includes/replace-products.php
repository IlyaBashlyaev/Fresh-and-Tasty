<?php
    $connection = new mysqli('127.0.0.1', 'admin', 'admin', 'restaurant');

    if (
        isset($_POST['category-id']) && isset($_POST['sub-category-id']) &&
        isset($_POST['min-price']) && isset($_POST['max-price'])
    ) {
        $search = $_POST['search'];
        $categoryId = $_POST['category-id'];
        $subCategoryId = $_POST['sub-category-id'];
        $minPrice = $_POST['min-price'];
        $maxPrice = $_POST['max-price'];

        $products = $connection -> query("SELECT * FROM `products` WHERE (`title` LIKE '%$search%' OR `description` LIKE '%$search%' OR `ingredients` LIKE '%$search%') AND `category-id` = $categoryId AND `sub-category-id` = $subCategoryId AND `price` >= $minPrice AND `price` <= $maxPrice");
    }

    if (isset($products)) {
        if ($products) {
            while ($product = $products -> fetch_assoc()) {
                ?>

                <div class="products__item" id="<?= $product['id'] ?>">
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
    }
?>