paypal.Buttons({
    style : {
        color: 'blue',
        shape: 'pill'
    },

    createOrder: (data, actions) => {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    currency_code: 'EUR',
                    value: totalPrice
                }
            }]
        });
    },

    onApprove: (data, actions) => {
        return actions.order.capture().then(details => {
            const form = document.createElement('form')
            form.action = '/includes/paypal.php'
            form.method = 'post'

            const textarea = document.createElement('textarea')
            textarea.name = 'payment_details'
            textarea.innerText = JSON.stringify(details)
            form.appendChild(textarea)

            document.body.appendChild(form)
            form.submit()
        })
    },

    onCancel: data => {
        if (!webView)
            alert('The payment was unfortunately interrupted.')
    }
}).render('.paypal-payment-button')