<div id="section1Carousal" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($images as $index => $image)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ $image }}" class="d-block w-100" alt="..." style="max-height: 500px; object-fit: cover;">
            </div>
        @endforeach
    </div>
    <!-- Static caption for all images -->
    <div class="carousel-caption">
        <h1>We Provide Best service</h1>
        <p>Description or any text you want to display on all images</p>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#section1Carousal" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#section1Carousal" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
