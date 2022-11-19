<?php
    if (isset($_POST['payment_details'])) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Payment with PayPal</title>
            </head>

            <body>
                <form action="/" method="post" style="display: none;">
                    <textarea name="payment_details"><?= $_POST['payment_details'] ?></textarea>
                </form>

                <script>
                    document.querySelector('form').submit()
                </script>
            </body>
        </html>

        <?php
    }
?>