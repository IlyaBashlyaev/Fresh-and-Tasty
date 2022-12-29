<script src="https://cdn.jsdelivr.net/npm/jquery.redirect@1.1.4/jquery.redirect.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=sb&disable-funding=credit,card&currency=EUR"></script>

<?php
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']))
        echo '<script>var webView = true;</script>';
    else
        echo '<script>var webView = false;</script>';
?>

<header class="header">
    <div class="container header__container">
        <div class="header__top">
            <div class="header__wrap">
                <ul class="header__contacts">
                    <li class="header__contacts-item chat-with-us link">
                        <a>Chat with us</a>
                    </li>

                    <li class="header__contacts-item tel"><a href="tel:+491234567890">+49 123 456 78 90</a></li>
                    <li class="header__contacts-item email"><a href="mail:info@fresh-and-tasty.com">info@fresh-and-tasty.com</a></li>
                </ul>

                <ul class="header__topics">
                    <li class="header__topics-item link"><a>Blog</a></li>
                    <li class="header__topics-item link"><a>About Us</a></li>
                    <li class="header__topics-item link"><a>Careers</a></li>
                </ul>
            </div>
        </div>

        <div class="header__bottom">
            <div class="header__wrap">
                <a href="/" class="logo">Fresh and Tasty</a>

                <div class="header__search">
                    <div class="header__search-categories">
                        <button class="header__search-drop">
                            <a href="/?page=1">All categories</a>
                        </button>

                        <div class="drop-list main-drop-list">
                            <div><a href="/?page=1&category-id=0">Fast Food</a></div>
                            <div><a href="/?page=1&category-id=1">Snacks</a></div>
                            <div><a href="/?page=1&category-id=2">Drinks</a></div>
                        </div>
                    </div>

                    <input placeholder="Search Products ..." oninput="preReplaceProducts()">
                    <button class="header__search-btn">
                        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.19303 11.4333C11.7704 11.4333 13.8597 9.34394 13.8597 6.76661C13.8597 4.18928 11.7704 2.09995 9.19303 2.09995C6.61571 2.09995 4.52637 4.18928 4.52637 6.76661C4.52637 9.34394 6.61571 11.4333 9.19303 11.4333Z" stroke="#151515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                            <path d="M5.81319 10.24L2.68652 13.3667" stroke="#151515" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                        </svg>
                    </button>
                </div>

                <div class="header__user">
                    <?php
                        $showQuantity = false;
                        $totalPrice = 0;

                        if (isset($_COOKIE['id'])) {
                            $userId = $_COOKIE['id'];
                            $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$userId'")
                                    -> fetch_assoc();
                            $userId = $user['id'];
                        }

                        else if (isset($_COOKIE['guest-id'])) {
                            $user = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'")
                                    -> fetch_assoc();
                        }
                        
                        if (isset($_COOKIE['id']) || isset($_COOKIE['guest-id'])) {
                            $userCart = $connection -> query("SELECT * FROM `user-carts` WHERE `user-id` = '$userId'")
                                        -> fetch_assoc();

                            if ($userCart) {
                                $productsId = json_decode($userCart['products-id']);
                                $productsIdCount = count($productsId);

                                if ($productsIdCount) {
                                    $showQuantity = true;
                                    $repeatedIds = array();

                                    foreach ($productsId as $productId) {
                                        if (isset($repeatedIds[$productId]))
                                            $repeatedIds[$productId]++;
                                        else
                                            $repeatedIds[$productId] = 1;
                                    }
                                    ?>

                                    <div class="popup">
                                        <div class="popup__close">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.3606 14.36L1.64062 1.63995" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                                                <path d="M14.3606 1.63995L1.64062 14.36" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                                            </svg>
                                        </div>

                                        <div class="popup__products-block">
                                            <div class="popup__title">Shopping cart</div>

                                            <?php
                                                foreach ($repeatedIds as $id => $repeatedId) {
                                                    $product = $connection -> query("SELECT * FROM `products` WHERE `product-id` = '$id'")
                                                            -> fetch_assoc();
                                                    $totalPrice += (int) $product['price'] * $repeatedId;
                                                    ?>

                                                    <div class="popup__product" id="<?= $id ?>">
                                                        <input type="number" class="popup__quantity" oninput="preChangeQuantity(this, '<?= $id ?>')" value="<?= $repeatedId ?>">

                                                        <div class="popup__sidebar">
                                                            <img class="popup__image" src="/<?= $product['image'] ?>">

                                                            <div class="popup__remove" onclick="removeProduct('<?= $id ?>')">
                                                                <i class="far"></i>
                                                                <span> Remove</span>
                                                            </div>
                                                        </div>

                                                        <div class="popup__content">
                                                            <div class="popup__product-title"><?= $product['title'] ?></div>
                                                            <div class="popup__product-price"><?= (int) $product['price'] * $repeatedId ?> €</div>

                                                            <?php
                                                                if (isset($product['prev-price'])) {
                                                                    ?>
                                                                    <div class="popup__product-prev-price"><?= (int) $product['prev-price'] * $repeatedId ?> €</div>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <?php
                                                }
                                            ?>
                                        </div>

                                        <div class="popup__price-block">
                                            <div class="popup__total-price">
                                                <div class="popup__price-title">Total price:</div>
                                                <div class="popup__price"><?= $totalPrice ?> €</div>
                                            </div>

                                            <div class="pay">
                                                <div class="button" onclick="window.location.href = '/pay.php'">Go to checkout</div>
                                                <a>Or</a>
                                                <div class="paypal-payment-button"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                            }
                        }
                    ?>

                    <a class="header__user-account" <?php
                        if (isset($_COOKIE['id'])) {
                            $secretId = $_COOKIE['id'];
                            $user = $connection -> query("SELECT * FROM `users` WHERE `secret-id` = '$secretId'")
                                    -> fetch_assoc();
                            
                            if ($user)
                                echo 'onclick="showUserData()"';
                            else
                                echo 'href="/login.php"';
                        }

                        else
                            echo 'href="/login.php"';
                    ?>>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 21.9999L3.79 19.1199C6.4 9.6199 17.6 9.6199 20.21 19.1199L21 21.9999" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 11.98C14.7614 11.98 17 9.74141 17 6.97998C17 4.21856 14.7614 1.97998 12 1.97998C9.23858 1.97998 7 4.21856 7 6.97998C7 9.74141 9.23858 11.98 12 11.98Z" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                        </svg>
                    </a>

                    <a class="header__user-cart" onclick="showPopup()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.8896 20.8499H6.10955C5.79187 20.8501 5.48326 20.7439 5.23309 20.5481C4.98291 20.3523 4.80562 20.0783 4.72955 19.7699L2.07955 9.15986C2.05369 9.05657 2.05174 8.94874 2.07383 8.84458C2.09593 8.74042 2.1415 8.64267 2.20707 8.55878C2.27264 8.47489 2.35649 8.40706 2.45223 8.36046C2.54797 8.31386 2.65307 8.28971 2.75955 8.28986H21.2396C21.346 8.28971 21.4511 8.31386 21.5469 8.36046C21.6426 8.40706 21.7265 8.47489 21.792 8.55878C21.8576 8.64267 21.9032 8.74042 21.9253 8.84458C21.9474 8.94874 21.9454 9.05657 21.9196 9.15986L19.2696 19.7699C19.1935 20.0783 19.0162 20.3523 18.766 20.5481C18.5158 20.7439 18.2072 20.8501 17.8896 20.8499V20.8499Z" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.79941 3.14993L6.89941 8.28993" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.8799 3.14993L16.7899 8.28993" stroke="#151515" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                        <?php
                            if ($showQuantity) {
                                ?>
                                <div class="quantity"><?= $productsIdCount ?></div>
                                <?php
                            }
                        ?>
                    </a>

                    <a class="header__user-dark-mode">
                        <i class="far fa-sun"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<nav class="navigation">
    <div class="container">
        <div class="navigation__list">
            <div class="navigation__list-item">
                <button class="navigation__item-btn drop">
                    Fast Food

                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.81348 6.53336L7.55348 9.27336C7.67838 9.39752 7.84735 9.46722 8.02348 9.46722C8.1996 9.46722 8.36857 9.39752 8.49348 9.27336L11.1601 6.60669" stroke="#6A983C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                    </svg>
                </button>

                <div class="drop-list">
                    <div><a href="/?page=1&category-id=0&sub-category-id=0">Pizzas</a></div>
                    <div><a href="/?page=1&category-id=0&sub-category-id=1">Burgers</a></div>
                    <div><a href="/?page=1&category-id=0&sub-category-id=2">French Fries</a></div>
                </div>
            </div>

            <div class="navigation__list-item">
                <button class="navigation__item-btn drop">
                    Snacks
                    
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.81348 6.53336L7.55348 9.27336C7.67838 9.39752 7.84735 9.46722 8.02348 9.46722C8.1996 9.46722 8.36857 9.39752 8.49348 9.27336L11.1601 6.60669" stroke="#6A983C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                    </svg>
                </button>
                
                <div class="drop-list">
                    <div><a href="/?page=1&category-id=1&sub-category-id=0">Popcorn</a></div>
                    <div><a href="/?page=1&category-id=1&sub-category-id=1">Crisps</a></div>
                    <div><a href="/?page=1&category-id=1&sub-category-id=2">Сrackers</a></div>
                </div>
            </div>

            <div class="navigation__list-item">
                <button class="navigation__item-btn drop">
                    Drinks

                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.81348 6.53336L7.55348 9.27336C7.67838 9.39752 7.84735 9.46722 8.02348 9.46722C8.1996 9.46722 8.36857 9.39752 8.49348 9.27336L11.1601 6.60669" stroke="#6A983C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="bevel"/>
                    </svg>
                </button>
                
                <div class="drop-list">
                    <div><a href="/?page=1&category-id=2&sub-category-id=0">Water</a></div>
                    <div><a href="/?page=1&category-id=2&sub-category-id=1">Cola</a></div>
                    <div><a href="/?page=1&category-id=2&sub-category-id=2">Juice</a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="navigation__info" onclick="showSidebar()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
            <path d="M0 96C0 78.33 14.33 64 32 64H416C433.7 64 448 78.33 448 96C448 113.7 433.7 128 416 128H32C14.33 128 0 113.7 0 96zM0 256C0 238.3 14.33 224 32 224H416C433.7 224 448 238.3 448 256C448 273.7 433.7 288 416 288H32C14.33 288 0 273.7 0 256zM416 448H32C14.33 448 0 433.7 0 416C0 398.3 14.33 384 32 384H416C433.7 384 448 398.3 448 416C448 433.7 433.7 448 416 448z">
        </svg>
    </div>
</nav>

<script>
    const icon = document.querySelector('.header__user-dark-mode')

    function setCookie(name, value) {
        const date = new Date()
        date.setTime(date.getTime() + 315360000000)
        var expires = 'expires=' + date.toUTCString()
        document.cookie = `${name}=${value};${expires};path=/`
    }

    function getCookie(name) {
        name += '='
        var cookie = document.cookie.split(';')

        for(var i = 0; i < cookie.length; i++) {
            var c = cookie[i]

            while (c.charAt(0) == ' ')
                c = c.substring(1)
            
            if (c.indexOf(name) == 0)
                return c.substring(name.length, c.length)
        }

        return ''
    }

    var theme = getCookie('theme'),
        totalPrice = <?= $totalPrice ?>, timerCQ

    if (theme == 'dark' || !theme) {
        document.body.className = 'dark'
        icon.innerHTML = '<i class="far fa-moon"></i>'
        setCookie('theme', 'dark')
    }

    function showPopup() {
        const popup = document.querySelector('.popup')
        if (popup)
            popup.classList.add('active')
    }

    function removeProduct(productId) {
        $.ajax({
            url: '/includes/remove-product.php',
            type: 'post',
            data: {productId: productId},
            success: priceBlock => {
                priceBlock = priceBlock.split(' ')

                const popupProduct = document.querySelector('.popup__product#' + productId),
                        popupTotalPrice = document.querySelector('.popup__price'),
                        quantity = document.querySelector('.quantity')
                
                popupProduct.remove()
                popupTotalPrice.innerText = parseInt(popupTotalPrice.innerText) - parseInt(priceBlock[0]) + ' €'
            
                if (priceBlock[1] != '0')
                    quantity.innerText = priceBlock[1]
                else
                    quantity.remove()
            }
        })
    }

    function changeQuantity(input, productId) {
        var quantity = input.value

        if (quantity > 10) {
            quantity = 10
            input.value = 10
        }

        else if (quantity < 1) {
            quantity = 1
            input.value = 1
        }

        $.ajax({
            url: '/includes/change-quantity.php',
            type: 'post',
            
            data: {
                productId: productId,
                quantity: quantity
            },

            success: priceBlock => {
                priceBlock = priceBlock.split(' ')

                const popupProduct = document.querySelector('.popup__product#' + productId),
                      popupQuantity = document.querySelector('.popup__quantity'),
                      popupPrice = popupProduct.querySelector('.popup__product-price'),
                      popupPrevPrice = popupProduct.querySelector('.popup__product-prev-price'),
                      popupTotalPrice = document.querySelector('.popup__price'),
                      quantity = document.querySelector('.quantity'),
                      paypalPaymentButton = document.querySelector('.paypal-payment-button')
                
                popupQuantity.removeAttribute('readonly')
                totalPrice -= popupPrice.innerText
                popupPrice.innerText = priceBlock[0] + ' €'
                totalPrice += priceBlock[0]
                totalPrice = totalPrice.split('NaN')[1]
                quantity.innerText = priceBlock[1]

                if (popupPrevPrice)
                    popupPrevPrice.innerText = priceBlock[2]
                popupTotalPrice.innerText = totalPrice + ' €'

                document.querySelector('script.paypal').remove()
                paypalPaymentButton.innerHTML = ''

                var paypal = document.createElement('script')
                paypal.className = 'paypal'
                paypal.type = 'text/javascript'
                paypal.src = 'includes/paypal.js'
                document.head.appendChild(paypal)
            }
        })
    }

    function preChangeQuantity(input, productId) {
        timerCQ = setTimeout(changeQuantity(input, productId), 500)
        const popupQuantity = document.querySelector('.popup__quantity')
        popupQuantity.setAttribute('readonly', '')
    }

    function showUserData() {
        $.ajax({
            url: '/includes/show-user-data.php',
            type: 'post',
            data: {},
            success: userData => {
                userData = JSON.parse(userData)

                var username = userData[0],
                    email = userData[1],
                    phone = userData[2]
                
                if (!webView)
                    alert(`Username: ${username}\nEmail: ${email}\nPhone: ${phone}`)
            }
        })
    }

    function validatePhone(phone) {
        var re = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/
        return re.test(phone)
    }

    function showSidebar() {
        const settings = document.querySelector('.settings')

        if (settings)
            settings.classList.toggle('hide')
    }

    document.body.onclick = event => {
        var el = event.target

        while (true) {
            if (el) {
                if (el.className == 'header__user-cart' || el.classList.contains('popup'))
                    return
                el = el.parentNode

                if (el) {
                    if (el.id == 'body' || el.className == 'popup__close') {
                        const popup = document.querySelector('.popup')
                        if (popup)
                            popup.classList.remove('active')
                        return
                    }
                }
            }
        }
    }

    icon.onclick = () => {
        document.body.classList.toggle('dark')

        if (document.body.className == 'dark')
            setCookie('theme', 'dark')

        else
            setCookie('theme', 'light')
        
        location.reload()
    }

    document.addEventListener('keydown', event => {
        if (event.key == 'Escape') {
            const popup = document.querySelector('.popup')
            if (popup)
                popup.classList.remove('active')
        }
    })
</script>