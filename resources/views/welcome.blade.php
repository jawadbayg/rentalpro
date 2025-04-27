@extends('layouts.app')

@section('content')

    @include('partials.section1', ['images' => [
        'https://img.ge/i/W0CKG96.jpg',
        'https://i.ibb.co/d4BqLhzJ/pexels-pixabay-164634.jpg',
        'https://i.ibb.co/gMmGPzQH/pexels-mikebirdy-112460.jpg',
    ]])

    <section class="text-center py-16 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                Welcome to My Application
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                We provide awesome services. Join us today!
            </p>
        </div>
    </section>
@endsection