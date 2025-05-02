<form id="payment-form" method="POST">

<input id="card-holder-name" type="text" class="form-control" placeholder="Card Holder Name" required><br>

<div id="card-element" class="form-control"></div>
<br>
<div id="card-errors" role="alert" style="color:red;"></div>

<div class="text-center">

<button id="submit" class="btn btn-lg btn-success">Submit Payment</button> 

</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stripe = Stripe('{{ config("services.stripe.key") }}');
        const elements = stripe.elements();

        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const submitButton = document.getElementById('submit');

        submitButton.addEventListener('click', async (event) => {
            event.preventDefault();
            const clientSecret = '{{ $intent->client_secret }}';

            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('card-holder-name').value,
                    }
                }
            });

            if (error) {
                // Display error message
                document.getElementById('card-errors').textContent = error.message;
            } else if (paymentIntent.status === 'succeeded') {
                alert('Payment successful!');
                // Optionally redirect or update UI
            } else if (paymentIntent.status === 'requires_action') {
                alert('Redirecting for authentication...');
            }
        });
    });
</script>
