@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center ">
    <div class="checkout-container col-5">
        <form id="payment-form" method="POST">
            <input id="card-holder-name" type="text" class="form-control" placeholder="Card Holder Name" required><br>

            <div id="card-element" class="form-control"></div>
            <br>

            <div id="card-errors" role="alert" style="color:red;"></div>

            <div class="text-center">
                <button id="submit" class="btn-blue">Submit Payment</button>
            </div>
        </form>
    </div>
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
                document.getElementById('card-errors').textContent = error.message;
            } else if (paymentIntent.status === 'succeeded') {
                const bookingId = window.location.pathname.split('/').pop(); // Extract ID from URL

                // AJAX call to backend
                fetch(`/payment-success/${bookingId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: 'Payment Successful!',
                        text: 'Thank you for your payment.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    }).then(() => {
                        window.location.href = '/';
                    });
                });

            } else if (paymentIntent.status === 'requires_action') {
                alert('Redirecting for authentication...');
            }
        });
    });
</script>
@endsection