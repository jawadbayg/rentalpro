<footer class="landing-footer">
    <div class="container py-5">
        <div class="row g-4 g-lg-5">
            <div class="col-md-6 col-lg-4">
                <h3 class="landing-footer__brand">Rental Pro</h3>
                <p class="landing-footer__text mb-4">Reliable vehicles, fair pricing, and support that keeps you moving. Drive with confidence wherever the road takes you.</p>
                <div class="landing-footer__social">
                    <a href="#" class="landing-footer__social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="landing-footer__social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="landing-footer__social-link" aria-label="X"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" class="landing-footer__social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <h4 class="landing-footer__heading">Explore</h4>
                <ul class="landing-footer__list">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    @if (Route::has('about.us.index'))
                        <li><a href="{{ route('about.us.index') }}">About Us</a></li>
                    @endif
                    <li><a href="{{ url('/') }}#featured-fleet">Fleet</a></li>
                    <li><a href="{{ url('/') }}#testimonials">Reviews</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-3 col-lg-3">
                <h4 class="landing-footer__heading">Contact</h4>
                <ul class="landing-footer__list landing-footer__contact">
                    <li><i class="fas fa-phone me-2 opacity-75"></i> 03202629191</li>
                    <li><i class="fas fa-envelope me-2 opacity-75"></i> hello@rentalpro.example</li>
                    <li><i class="fas fa-location-dot me-2 opacity-75"></i> Faisalabad, Pakistan</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h4 class="landing-footer__heading">Newsletter</h4>
                <p class="landing-footer__text small">Get deals and driving tips in your inbox.</p>
                <form class="landing-footer__newsletter" action="#" method="post" onsubmit="return false;">
                    @csrf
                    <label class="visually-hidden" for="footer-newsletter-email">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control landing-footer__input" id="footer-newsletter-email" placeholder="Your email" autocomplete="email">
                        <button class="btn btn-landing-accent" type="button">Join</button>
                    </div>
                </form>
            </div>
        </div>
        <hr class="landing-footer__rule">
        <p class="landing-footer__copy text-center mb-0">&copy; {{ date('Y') }} Rental Pro. All rights reserved.</p>
    </div>
</footer>
