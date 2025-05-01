@extends('layouts.app')

@section('content')
    <div class="container">
            @if (Auth::user()->hasRole('Admin'))
                @include('partials.admin_dashboard')
            @elseif (Auth::user()->hasRole('FP'))
                @include('partials.fp_dashboard')
            @endif
    </div>
@endsection
